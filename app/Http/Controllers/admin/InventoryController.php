<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInventoryAdjustmentRequest;
use App\Models\InventoryMovement;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductVariant::query()
            ->with('product:id,name')
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = trim($request->string('search')->toString());
                $query->where(function ($q) use ($term) {
                    $q->where('sku', 'like', "%{$term}%")
                        ->orWhere('size', 'like', "%{$term}%")
                        ->orWhere('color', 'like', "%{$term}%")
                        ->orWhereHas('product', fn ($p) => $p->where('name', 'like', "%{$term}%"));
                });
            })
            ->when($request->filled('stock'), function ($query) use ($request) {
                match ($request->input('stock')) {
                    'out' => $query->where('stock_quantity', 0),
                    'low' => $query->whereBetween('stock_quantity', [1, 5]),
                    'in' => $query->where('stock_quantity', '>', 5),
                    default => null,
                };
            })
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->orderByRaw('CASE WHEN stock_quantity = 0 THEN 0 WHEN stock_quantity <= 5 THEN 1 ELSE 2 END')
            ->orderBy('stock_quantity')
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'variants' => ProductVariant::count(),
            'units' => (int) ProductVariant::sum('stock_quantity'),
            'low_stock' => ProductVariant::whereBetween('stock_quantity', [1, 5])->count(),
            'out_of_stock' => ProductVariant::where('stock_quantity', 0)->count(),
        ];

        $movements = InventoryMovement::query()
            ->with(['variant.product:id,name', 'admin:id,first_name,last_name'])
            ->latest('created_at')
            ->limit(8)
            ->get();

        return view('admin.inventory.index', compact('query', 'summary', 'movements'));
    }

    public function adjust(ProductVariant $variant)
    {
        $variant->load('product:id,name');

        return view('admin.inventory.adjust', compact('variant'));
    }

    public function storeAdjustment(StoreInventoryAdjustmentRequest $request, ProductVariant $variant)
    {
        DB::transaction(function () use ($request, $variant) {
            $lockedVariant = ProductVariant::query()->lockForUpdate()->findOrFail($variant->id);
            $data = $request->validated();
            $current = (int) $lockedVariant->stock_quantity;
            $change = (int) $data['quantity_change'];
            $next = $current + $change;

            if ($next < 0) {
                throw ValidationException::withMessages([
                    'quantity_change' => 'This adjustment would make stock negative. Check the current quantity and try again.',
                ]);
            }

            $lockedVariant->update(['stock_quantity' => $next]);

            InventoryMovement::create([
                'product_variant_id' => $lockedVariant->id,
                'admin_id' => Auth::guard('admin')->id(),
                'type' => $data['type'],
                'quantity_change' => $change,
                'reference' => $data['reference'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Inventory adjustment saved and movement recorded.');
    }
}
