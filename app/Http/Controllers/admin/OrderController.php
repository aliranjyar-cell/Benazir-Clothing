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
use App\Models\User;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'status' => ['nullable', Rule::in(Order::STATUSES)],
            'payment_status' => [
                'nullable',
                Rule::in(Order::PAYMENT_STATUSES),
            ],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_from'],
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
                fn($query, $status) => $query->where('status', $status)
            )
            ->when(
                $filters['payment_status'] ?? null,
                fn($query, $status) => $query->where(
                    'payment_status',
                    $status
                )
            )
            ->when(
                $filters['date_from'] ?? null,
                fn($query, $date) => $query->whereDate('created_at', '>=', $date)
            )
            ->when(
                $filters['date_to'] ?? null,
                fn($query, $date) => $query->whereDate('created_at', '<=', $date)
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


    public function show(Request $request, Order $order): View
    {
        $this->authorizeAdmin($request);

        $order->load([
            'user',
            'paymentCollector',
            'items.product',
            'items.variant',
            'statusHistories.admin',
        ]);

        $customers = $order->user_id
            ? collect()
            : User::query()
            ->orderBy('name')
            ->orderBy('email')
            ->get(['id', 'name', 'email']);

        return view('admin.orders.show', compact('order', 'customers'));
    }


    public function updateStatus(
        Request $request,
        Order $order,
        OrderWorkflowService $workflow
    ): RedirectResponse {
        $this->authorizeAdmin($request);

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
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'payment_status' => [
                'required',
                Rule::in([Order::PAYMENT_PAID]),
            ],
            'payment_reference' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $adminId = $request->user('admin')->id;

        DB::transaction(function () use ($order, $validated, $adminId) {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if (
                $lockedOrder->payment_status !== Order::PAYMENT_UNPAID
                || ! in_array($lockedOrder->payment_method, ['cash_on_delivery', 'bank_transfer'], true)
                || $lockedOrder->status === Order::STATUS_CANCELLED
            ) {
                throw ValidationException::withMessages([
                    'payment_status' => 'Only an unpaid cash-on-delivery order can be marked collected. Already processed payments require a dedicated correction or refund workflow.',
                ]);
            }

            $lockedOrder->payment_status = Order::PAYMENT_PAID;
            $lockedOrder->payment_reference = $validated['payment_reference']
                ?? $lockedOrder->payment_reference;
            $lockedOrder->paid_at = now();
            $lockedOrder->payment_collected_by_admin_id = $adminId;

            $lockedOrder->save();
        }, 3);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Payment status updated successfully.');
    }

    public function linkCustomer(Request $request, Order $order)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $result = DB::transaction(function () use ($order, $validated) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Never silently replace an existing customer.
            if ($lockedOrder->user_id !== null) {
                return false;
            }

            $customer = User::findOrFail($validated['customer_id']);

            // Only attach the customer. Do not touch items, totals, or stock.
            $lockedOrder->user()->associate($customer);
            $lockedOrder->save();

            return true;
        });

        if (! $result) {
            return back()->with(
                'error',
                'This order is already linked to a customer.'
            );
        }

        return back()->with(
            'success',
            'Guest order linked to the customer successfully.'
        );
    }

    private function authorizeAdmin(Request $request): void
    {
        $admin = $request->user('admin');

        abort_unless(
            $admin !== null
                && $admin->status === 'active'
                && in_array($admin->role, ['admin', 'superadmin'], true),
            403
        );
    }
}
