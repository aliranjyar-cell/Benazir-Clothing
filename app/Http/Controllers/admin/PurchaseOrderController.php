<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = PurchaseOrder::query()
            ->with('admin:id,first_name,last_name')
            ->withCount('items')
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = trim($request->string('search')->toString());
                $query->where(function ($query) use ($term) {
                    $query->where('reference', 'like', "%{$term}%")
                        ->orWhere('supplier_name', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'open' => PurchaseOrder::whereIn('status', [
                PurchaseOrder::STATUS_ORDERED,
                PurchaseOrder::STATUS_PARTIALLY_RECEIVED,
            ])->count(),
            'partial' => PurchaseOrder::where('status', PurchaseOrder::STATUS_PARTIALLY_RECEIVED)->count(),
            'received' => PurchaseOrder::where('status', PurchaseOrder::STATUS_RECEIVED)->count(),
        ];

        return view('admin.purchase-orders.index', compact('orders', 'summary'));
    }

    public function create(): View
    {
        $variants = ProductVariant::query()
            ->with('product:id,name')
            ->orderBy('sku')
            ->get();

        return view('admin.purchase-orders.create', compact('variants'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'supplier_name' => ['required', 'string', 'max:120'],
            'supplier_email' => ['nullable', 'email', 'max:254'],
            'expected_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1', 'max:50'],
            'items.*.variant_id' => ['required', 'integer', 'distinct', 'exists:product_variants,id'],
            'items.*.quantity_ordered' => ['required', 'integer', 'min:1', 'max:100000'],
            'items.*.unit_cost' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
        ]);

        $order = DB::transaction(function () use ($data) {
            $variants = ProductVariant::query()
                ->with('product:id,name')
                ->whereIn('id', collect($data['items'])->pluck('variant_id'))
                ->get()
                ->keyBy('id');

            $order = PurchaseOrder::create([
                'reference' => 'PO-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
                'supplier_name' => $data['supplier_name'],
                'supplier_email' => $data['supplier_email'] ?? null,
                'expected_at' => $data['expected_at'] ?? null,
                'status' => PurchaseOrder::STATUS_ORDERED,
                'notes' => $data['notes'] ?? null,
                'admin_id' => Auth::guard('admin')->id(),
            ]);

            foreach ($data['items'] as $line) {
                $variant = $variants->get((int) $line['variant_id']);

                if (! $variant) {
                    throw ValidationException::withMessages([
                        'items' => 'A selected product variant is no longer available. Please try again.',
                    ]);
                }

                $order->items()->create([
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product?->name ?? 'Product unavailable',
                    'sku' => $variant->sku,
                    'size' => $variant->size,
                    'color' => $variant->color,
                    'quantity_ordered' => $line['quantity_ordered'],
                    'unit_cost' => $line['unit_cost'] ?? 0,
                ]);
            }

            return $order;
        });

        return redirect()
            ->route('admin.purchase-orders.show', $order)
            ->with('success', 'Purchase order created. Receive stock as deliveries arrive.');
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['items.variant.product', 'admin']);

        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1', 'max:100000'],
        ]);

        $receipts = collect($data['items'])
            ->filter(fn($item) => filled($item['quantity'] ?? null));

        if ($receipts->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Enter a quantity for at least one line to receive stock.',
            ]);
        }

        DB::transaction(function () use ($purchaseOrder, $receipts) {
            $order = PurchaseOrder::query()
                ->lockForUpdate()
                ->findOrFail($purchaseOrder->id);

            if ($order->status === PurchaseOrder::STATUS_RECEIVED) {
                throw ValidationException::withMessages([
                    'items' => 'This purchase order has already been fully received.',
                ]);
            }

            foreach ($receipts as $itemId => $receipt) {
                $item = $order->items()->lockForUpdate()->find($itemId);

                if (! $item) {
                    throw ValidationException::withMessages([
                        'items' => 'A selected line does not belong to this purchase order.',
                    ]);
                }

                $quantity = (int) $receipt['quantity'];
                $remaining = $item->quantity_ordered - $item->quantity_received;

                if ($quantity > $remaining) {
                    throw ValidationException::withMessages([
                        "items.{$itemId}.quantity" => "Only {$remaining} units remain on this purchase order line.",
                    ]);
                }

                $variant = ProductVariant::query()
                    ->lockForUpdate()
                    ->find($item->product_variant_id);

                if (! $variant) {
                    throw ValidationException::withMessages([
                        "items.{$itemId}.quantity" => 'The product variant for this line no longer exists.',
                    ]);
                }

                $variant->increment('stock_quantity', $quantity);
                $item->increment('quantity_received', $quantity);

                InventoryMovement::create([
                    'product_variant_id' => $variant->id,
                    'admin_id' => Auth::guard('admin')->id(),
                    'type' => 'restock',
                    'quantity_change' => $quantity,
                    'reference' => $order->reference,
                    'notes' => 'Received against purchase order ' . $order->reference,
                    'created_at' => now(),
                ]);
            }

            $items = $order->items()->get();
            $allReceived = $items->every(fn($item) => $item->quantity_received >= $item->quantity_ordered);
            $anyReceived = $items->contains(fn($item) => $item->quantity_received > 0);

            $order->update([
                'status' => $allReceived
                    ? PurchaseOrder::STATUS_RECEIVED
                    : ($anyReceived ? PurchaseOrder::STATUS_PARTIALLY_RECEIVED : PurchaseOrder::STATUS_ORDERED),
                'received_at' => $allReceived ? now() : null,
            ]);
        });

        return redirect()
            ->route('admin.purchase-orders.show', $purchaseOrder)
            ->with('success', 'Received quantities were added to inventory and recorded in stock movements.');
    }
}
