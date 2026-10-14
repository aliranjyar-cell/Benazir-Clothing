<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Services\OrderWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'status' => ['nullable', Rule::in(Order::STATUSES)],
            'payment_status' => [
                'nullable',
                Rule::in(Order::PAYMENT_STATUSES),
            ],
        ]);

        $orders = Order::query()
            ->withCount('items')
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->when(
                $filters['status'] ?? null,
                fn ($query, $status) => $query->where('status', $status)
            )
            ->when(
                $filters['payment_status'] ?? null,
                fn ($query, $status) => $query->where(
                    'payment_status',
                    $status
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'total' => Order::count(),
            'pending' => Order::where(
                'status',
                Order::STATUS_PENDING
            )->count(),
            'processing' => Order::whereIn('status', [
                Order::STATUS_CONFIRMED,
                Order::STATUS_PROCESSING,
            ])->count(),
            'revenue' => (float) Order::where(
                'payment_status',
                Order::PAYMENT_PAID
            )->sum('grand_total'),
        ];

        return view('admin.orders.index', compact(
            'orders',
            'summary',
            'filters'
        ));
    }

    public function show(Order $order): View
    {
        $order->load([
            'items.product',
            'items.variant',
            'statusHistories.admin',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(
        Request $request,
        Order $order,
        OrderWorkflowService $workflow
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(Order::STATUSES),
            ],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $workflow->changeStatus(
            $order->id,
            $validated['status'],
            $validated['note'] ?? null
        );

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }

    public function updatePayment(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'payment_status' => [
                'required',
                Rule::in([
                    Order::PAYMENT_PAID,
                    Order::PAYMENT_FAILED,
                ]),
            ],
            'payment_method' => [
                'nullable',
                'string',
                'max:50',
            ],
            'payment_reference' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        DB::transaction(function () use ($order, $validated) {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($lockedOrder->payment_status !== Order::PAYMENT_UNPAID) {
                throw ValidationException::withMessages([
                    'payment_status' => 'Only unpaid orders can be marked paid or failed through this action. Already processed payments require a dedicated correction or refund workflow.',
                ]);
            }

            $lockedOrder->payment_status = $validated['payment_status'];
            $lockedOrder->payment_method =
                $validated['payment_method'] ?? $lockedOrder->payment_method;
            $lockedOrder->payment_reference =
                $validated['payment_reference'] ?? $lockedOrder->payment_reference;
            $lockedOrder->paid_at = $validated['payment_status'] === Order::PAYMENT_PAID
                ? now()
                : null;

            $lockedOrder->save();
        }, 3);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Payment status updated successfully.');
    }
}

