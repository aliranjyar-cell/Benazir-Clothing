<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    /**
     * Checkout form. Guests and authenticated customers may both
     * place orders; the orders table stores a nullable user_id.
     */
    public function show(): View|RedirectResponse
    {
        $lines = $this->cart->lines();

        if ($lines->isEmpty()) {
            return redirect()
                ->route('store.cart.index')
                ->with('error', 'Your shopping bag is empty.');
        }

        return view('storefront.checkout', [
            'lines' => $lines,
            'subtotal' => $this->cart->subtotal(),
            'shippingTotal' => $this->shippingTotal(),
            'grandTotal' => $this->cart->subtotal() + $this->shippingTotal(),
            'paymentMethods' => $this->paymentMethods(),
            'defaultPaymentMethod' => config('storefront.checkout.default_payment_method'),
            'customer' => auth('web')->user(),
        ]);
    }

    /**
     * Place the order. Stock is verified under row locks and all
     * monetary values are calculated from the database inside a
     * transaction. Payment stays "unpaid": cash-on-delivery orders are
     * only ever marked paid later through the admin panel.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => [
                'required',
                'string',
                'max:30',
                'regex:/^[0-9+()\- ]{7,30}$/',
            ],
            'shipping_address_line_1' => ['required', 'string', 'max:255'],
            'shipping_address_line_2' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_state' => ['nullable', 'string', 'max:100'],
            'shipping_postal_code' => ['nullable', 'string', 'max:20'],
            'shipping_country' => ['required', 'string', 'max:100'],
            'payment_method' => [
                'required',
                Rule::in(array_keys($this->paymentMethods())),
            ],
            'customer_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $lines = $this->cart->lines();

        if ($lines->isEmpty()) {
            return redirect()
                ->route('store.cart.index')
                ->with('error', 'Your shopping bag is empty.');
        }

        $order = DB::transaction(function () use ($lines, $validated) {
            // Lock rows in a stable order to avoid deadlocks.
            $variantIds = $lines->pluck('variant.id')->sort()->values()->all();

            $variants = ProductVariant::query()
                ->with('product:id,slug,name,is_active')
                ->whereIn('id', $variantIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = 0.0;
            $items = [];

            foreach ($lines as $line) {
                $variant = $variants->get($line['variant']->id);

                if (
                    ! $variant
                    || ! $variant->is_active
                    || ! $variant->product
                    || ! $variant->product->is_active
                ) {
                    throw ValidationException::withMessages([
                        'cart' => 'An item in your bag is no longer available. Please review your cart.',
                    ]);
                }

                if ((int) $variant->stock_quantity < $line['quantity']) {
                    throw ValidationException::withMessages([
                        'cart' => "Insufficient stock for {$variant->product->name} (SKU {$variant->sku}). "
                            . "Only {$variant->stock_quantity} left.",
                    ]);
                }

                $unitPrice = (float) $variant->price;
                $lineTotal = $unitPrice * $line['quantity'];
                $subtotal += $lineTotal;

                $items[] = [
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'sku' => $variant->sku,
                    'size' => $variant->size,
                    'color' => $variant->color,
                    'quantity' => $line['quantity'],
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $shippingTotal = $this->shippingTotal();

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => auth('web')->id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_phone' => $validated['customer_phone'],
                'shipping_address_line_1' => $validated['shipping_address_line_1'],
                'shipping_address_line_2' => $validated['shipping_address_line_2'] ?? null,
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'] ?? null,
                'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,
                'shipping_country' => $validated['shipping_country'],
                'currency' => config('storefront.checkout.currency', 'PKR'),
                'subtotal' => $subtotal,
                'discount_total' => 0,
                'shipping_total' => $shippingTotal,
                'tax_total' => 0,
                'grand_total' => $subtotal + $shippingTotal,
                'status' => Order::STATUS_PENDING,
                // Cash on delivery: never mark as paid without verified payment.
                'payment_status' => Order::PAYMENT_UNPAID,
                'payment_method' => $validated['payment_method'],
                'payment_reference' => null,
                'paid_at' => null,
                'customer_note' => $validated['customer_note'] ?? null,
            ]);

            foreach ($items as $item) {
                $order->items()->create($item);
            }

            // Stock is deducted by the existing admin confirmation
            // workflow (OrderWorkflowService), which this checkout
            // intentionally does not pre-empt.
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'admin_id' => null,
                'from_status' => null,
                'to_status' => Order::STATUS_PENDING,
                'note' => 'Order placed through the storefront (cash on delivery).',
                'created_at' => now(),
            ]);

            return $order;
        }, 3);

        $this->cart->clear();

        // Let guests view their own confirmation page.
        $recent = session('storefront.recent_orders', []);
        $recent[] = $order->id;
        session(['storefront.recent_orders' => array_values(array_unique(array_map('intval', $recent)))]);

        return redirect()
            ->route('store.orders.show', $order)
            ->with('success', "Thank you! Your order {$order->order_number} has been placed.");
    }

    /**
     * @return array<string, string>
     */
    private function paymentMethods(): array
    {
        return config('storefront.checkout.payment_methods', [
            'cash_on_delivery' => 'Cash on Delivery',
        ]);
    }

    private function shippingTotal(): float
    {
        return (float) config('storefront.checkout.shipping_flat', 0);
    }

    private function generateOrderNumber(): string
    {
        return 'BNZ' . now()->format('ymdHis') . Str::upper(Str::random(4));
    }
}
