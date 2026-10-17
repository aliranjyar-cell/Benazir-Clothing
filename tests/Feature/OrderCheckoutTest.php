<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Mail\OrderNotificationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_checkout_uses_catalogue_prices_and_creates_cod_order(): void
    {
        $variant = $this->makeVariant(['price' => '1299.95', 'stock_quantity' => 4]);
        $checkoutToken = $this->startCheckout($variant, 2);

        $response = $this->post(route('store.checkout.place'), $this->checkoutData([
            'checkout_token' => $checkoutToken,
            'payment_method' => 'cash_on_delivery',
            'unit_price' => '1.00',
            'subtotal' => '1.00',
            'discount_total' => '9999.00',
            'grand_total' => '1.00',
        ]));

        $order = Order::query()->firstOrFail();
        $item = $order->items()->firstOrFail();

        $response->assertRedirect(route('store.orders.show', $order->checkout_token));
        $this->assertNull($order->user_id);
        $this->assertSame('cash_on_delivery', $order->payment_method);
        $this->assertSame(Order::PAYMENT_UNPAID, $order->payment_status);
        $this->assertSame('2599.90', $order->subtotal);
        $this->assertSame('2599.90', $order->grand_total);
        $this->assertSame('1299.95', $item->unit_price);
        $this->assertSame('2599.90', $item->line_total);
        $this->assertSame(2, $item->quantity);
        $this->assertNotSame('', $order->order_number);
        $this->assertFalse(session()->has('cart'));

        $this->get(route('store.orders.show', $order->checkout_token))
            ->assertOk()
            ->assertSee('Order received')
            ->assertSee($order->order_number)
            ->assertSee($order->checkout_token)
            ->assertSee('verification are not configured')
            ->assertSee('Cash on')
            ->assertSee('Delivery')
            ->assertSee('Burgundy')
            ->assertSee('Lahore')
            ->assertSee('54000');

        $this->get(route('store.orders.show', $order->checkout_token))
            ->assertOk()
            ->assertSee($order->order_number);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
    }

    public function test_checkout_queues_customer_and_admin_notifications_once(): void
    {
        Mail::fake();
        config(['storefront.notifications.admin_email' => 'orders@benazir.example']);

        $variant = $this->makeVariant();
        $checkoutToken = $this->startCheckout($variant, 1);
        $payload = $this->checkoutData(['checkout_token' => $checkoutToken]);

        $this->post(route('store.checkout.place'), $payload)->assertRedirect();
        $order = Order::query()->firstOrFail();

        Mail::assertQueued(OrderNotificationMail::class, 2);
        Mail::assertQueued(
            OrderNotificationMail::class,
            fn(OrderNotificationMail $mail) =>
            $mail->notificationType === 'placed' && $mail->hasTo('simran@example.com')
        );
        Mail::assertQueued(
            OrderNotificationMail::class,
            fn(OrderNotificationMail $mail) =>
            $mail->notificationType === 'new_order' && $mail->hasTo('orders@benazir.example')
        );

        $this->post(route('store.checkout.place'), $payload)->assertRedirect();

        Mail::assertQueued(OrderNotificationMail::class, 2);
        $this->assertDatabaseCount('orders', 1);
        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);
    }

    public function test_order_notification_renders_order_and_delivery_details(): void
    {
        $order = $this->makeOrder();
        $order->items()->create([
            'product_name' => 'Snapshot Punjabi Suit',
            'sku' => 'BEN-SNAPSHOT',
            'size' => 'M',
            'color' => 'Burgundy',
            'quantity' => 2,
            'unit_price' => '1299.95',
            'line_total' => '2599.90',
        ]);

        $html = (new OrderNotificationMail($order->load('items'), 'placed'))->render();

        $this->assertStringContainsString('BENAZIR', $html);
        $this->assertStringContainsString($order->order_number, $html);
        $this->assertStringContainsString('Snapshot Punjabi Suit', $html);
        $this->assertStringContainsString('2,599.90', $html);
        $this->assertStringContainsString('Cash on Delivery', $html);
        $this->assertStringContainsString('12 Garden Town', $html);
        $this->assertStringNotContainsString('checkout_token', $html);
    }

    public function test_order_notification_sends_through_the_local_log_mailer(): void
    {
        $order = $this->makeOrder();

        $sentMessage = Mail::mailer('log')->to('preview@example.test')->send(
            new OrderNotificationMail($order, 'placed')
        );

        $this->assertNotNull($sentMessage);
    }

    public function test_authenticated_customer_checkout_links_order_to_customer(): void
    {
        $variant = $this->makeVariant();
        $checkoutToken = $this->startCheckout($variant, 1);
        $customer = User::create([
            'name' => 'Simran Kaur',
            'email' => 'simran@example.com',
            'password' => 'password',
        ]);

        $response = $this->actingAs($customer, 'web')
            ->post(route('store.checkout.place'), $this->checkoutData([
                'checkout_token' => $checkoutToken,
            ]));

        $order = Order::query()->firstOrFail();
        $this->assertSame($customer->id, $order->user_id);
        $response->assertRedirect(route('store.orders.show', $order->checkout_token));

        $this->actingAs($customer, 'web')
            ->get(route('store.account.orders.show', $order))
            ->assertOk()
            ->assertSee($order->order_number);
    }

    public function test_confirmation_rejects_missing_tokens_and_arbitrary_order_ids(): void
    {
        $this->get(route('store.orders.show', Str::uuid()))->assertNotFound();
        $this->get('/order-confirmation/1')->assertNotFound();
    }

    public function test_guest_can_track_order_with_private_token_without_exposing_personal_data(): void
    {
        $order = $this->makeOrder();
        $token = (string) Str::uuid();
        $order->update([
            'checkout_token' => $token,
            'customer_name' => 'Private Recipient',
            'customer_email' => 'private-recipient@example.com',
            'customer_phone' => '+923001112233',
            'shipping_address_line_1' => 'Secret Street 42',
            'shipping_address_line_2' => 'Private Apartment',
            'shipping_city' => 'Lahore',
            'shipping_state' => 'Punjab',
            'shipping_postal_code' => '54000',
            'shipping_country' => 'Pakistan',
            'status' => Order::STATUS_SHIPPED,
        ]);
        $order->items()->create([
            'product_name' => 'Snapshot Punjabi Suit',
            'sku' => 'BEN-TRACK-01',
            'size' => 'M',
            'color' => 'Burgundy',
            'quantity' => 2,
            'unit_price' => '1299.95',
            'line_total' => '2599.90',
        ]);
        $order->statusHistories()->create([
            'from_status' => null,
            'to_status' => Order::STATUS_PENDING,
            'created_at' => $order->created_at,
        ]);
        $order->statusHistories()->create([
            'from_status' => Order::STATUS_PROCESSING,
            'to_status' => Order::STATUS_SHIPPED,
            'created_at' => now(),
        ]);

        $this->get(route('store.orders.track'))
            ->assertOk()
            ->assertSee('Track an order')
            ->assertSee('tracking_token');

        $this->post(route('store.orders.track.submit'), [
            'tracking_token' => $token,
        ])
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('Shipped')
            ->assertSee('Snapshot Punjabi Suit')
            ->assertSee('BEN-TRACK-01')
            ->assertSee('Burgundy')
            ->assertSee('Cash on Delivery')
            ->assertSee('Lahore')
            ->assertSee('Punjab')
            ->assertSee('Pakistan')
            ->assertSee('Status')
            ->assertSee('history')
            ->assertDontSee('Private Recipient')
            ->assertDontSee('private-recipient@example.com')
            ->assertDontSee('+923001112233')
            ->assertDontSee('Secret Street 42')
            ->assertDontSee('Private Apartment')
            ->assertDontSee('54000');
    }

    public function test_invalid_or_unauthorized_tracking_codes_use_a_generic_failure(): void
    {
        $guestOrder = $this->makeOrder();
        $guestToken = (string) Str::uuid();
        $guestOrder->update(['checkout_token' => $guestToken]);

        $registeredCustomer = User::create([
            'name' => 'Registered Customer',
            'email' => 'registered-tracking@example.com',
            'password' => 'password',
        ]);
        $registeredOrder = $this->makeOrder();
        $registeredToken = (string) Str::uuid();
        $registeredOrder->update([
            'checkout_token' => $registeredToken,
            'user_id' => $registeredCustomer->id,
        ]);

        $this->get('/order-tracking/' . $guestOrder->id)->assertNotFound();

        $this->from(route('store.orders.track'))
            ->post(route('store.orders.track.submit'), [
                'tracking_token' => (string) Str::uuid(),
            ])
            ->assertRedirect(route('store.orders.track'))
            ->assertSessionHasErrors('tracking_token');

        $this->get(route('store.orders.track'))
            ->assertOk()
            ->assertSee('We could not verify those tracking details.')
            ->assertDontSee($guestOrder->order_number)
            ->assertDontSee($registeredOrder->order_number);

        $this->from(route('store.orders.track'))
            ->actingAs($registeredCustomer, 'web')
            ->post(route('store.orders.track.submit'), [
                'tracking_token' => $guestToken,
            ])
            ->assertRedirect(route('store.orders.track'))
            ->assertSessionHasErrors('tracking_token');

        $this->get(route('store.orders.track'))
            ->assertOk()
            ->assertSee('We could not verify those tracking details.')
            ->assertDontSee($guestOrder->order_number)
            ->assertDontSee($registeredOrder->order_number);
    }

    public function test_registered_order_confirmation_is_only_visible_to_its_owner(): void
    {
        $variant = $this->makeVariant();
        $checkoutToken = $this->startCheckout($variant, 1);
        $owner = User::create([
            'name' => 'Order Owner',
            'email' => 'owner@example.com',
            'password' => 'password',
        ]);
        $otherCustomer = User::create([
            'name' => 'Other Customer',
            'email' => 'other@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($owner, 'web')
            ->post(route('store.checkout.place'), $this->checkoutData([
                'checkout_token' => $checkoutToken,
            ]))
            ->assertRedirect();

        $order = Order::query()->firstOrFail();

        $this->actingAs($owner, 'web')
            ->get(route('store.orders.show', $order->checkout_token))
            ->assertOk()
            ->assertSee($order->order_number);

        $this->actingAs($otherCustomer, 'web')
            ->get(route('store.orders.show', $order->checkout_token))
            ->assertNotFound();
    }

    public function test_customer_account_pages_only_expose_their_own_orders_and_profile(): void
    {
        $owner = User::create([
            'name' => 'Order Owner',
            'email' => 'account-owner@example.com',
            'password' => 'password',
        ]);
        $otherCustomer = User::create([
            'name' => 'Other Customer',
            'email' => 'account-other@example.com',
            'password' => 'password',
        ]);
        $order = $this->makeOrder();
        $order->update(['user_id' => $owner->id]);
        $order->items()->create([
            'product_name' => 'Snapshot Punjabi Suit',
            'sku' => 'BEN-SNAPSHOT',
            'size' => 'M',
            'color' => 'Burgundy',
            'quantity' => 2,
            'unit_price' => '1299.95',
            'line_total' => '2599.90',
        ]);
        $order->statusHistories()->create([
            'from_status' => Order::STATUS_PENDING,
            'to_status' => Order::STATUS_CONFIRMED,
            'created_at' => now(),
        ]);
        $guestOrder = $this->makeOrder();
        $guestOrder->update(['checkout_token' => (string) Str::uuid()]);

        $this->actingAs($owner, 'web')
            ->get(route('store.account'))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertDontSee($guestOrder->order_number);

        $this->get(route('store.account.profile'))
            ->assertOk()
            ->assertSee('account-owner@example.com')
            ->assertDontSee('account-other@example.com');

        $this->get(route('store.account.orders'))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertDontSee($guestOrder->order_number);

        $this->get(route('store.account.orders.show', $order))
            ->assertOk()
            ->assertSee('Snapshot Punjabi Suit')
            ->assertSee('BEN-SNAPSHOT')
            ->assertSee('Cash on Delivery')
            ->assertSee('Payment status: Unpaid')
            ->assertSee('Status history')
            ->assertSee('Pending to')
            ->assertSee('Confirmed')
            ->assertSee('Discount');

        $this->patch(route('store.account.update'), [
            'name' => $owner->name,
            'email' => $owner->email,
            'status' => Order::STATUS_DELIVERED,
            'payment_status' => Order::PAYMENT_PAID,
            'grand_total' => '0.01',
        ])->assertRedirect(route('store.account'));

        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);
        $this->assertSame(Order::PAYMENT_UNPAID, $order->fresh()->payment_status);
        $this->assertSame('2599.90', $order->fresh()->grand_total);

        $this->actingAs($otherCustomer, 'web')
            ->get(route('store.account'))
            ->assertOk()
            ->assertDontSee($order->order_number)
            ->assertDontSee('account-owner@example.com');

        $this->get(route('store.account.profile'))
            ->assertOk()
            ->assertSee('account-other@example.com')
            ->assertDontSee('account-owner@example.com');

        $this->get(route('store.account.orders'))
            ->assertOk()
            ->assertDontSee($order->order_number)
            ->assertDontSee($guestOrder->order_number);

        $this->get(route('store.account.orders.show', $order))->assertNotFound();
        $this->get(route('store.account.orders.show', $guestOrder))->assertNotFound();
        $this->get(route('store.orders.show', $guestOrder->checkout_token))->assertNotFound();
    }

    public function test_order_management_requires_an_active_admin_account(): void
    {
        $order = $this->makeOrder();
        $customer = User::create([
            'name' => 'Store Customer',
            'email' => 'store-customer@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($customer, 'web')
            ->get(route('admin.orders.show', $order))
            ->assertRedirect();

        $inactiveAdmin = Admin::create([
            'first_name' => 'Inactive',
            'last_name' => 'Admin',
            'username' => 'inactive-admin',
            'email' => 'inactive-admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'inactive',
        ]);

        $this->actingAs($inactiveAdmin, 'admin')
            ->get(route('admin.orders.index'))
            ->assertForbidden();

        $this->patch(route('admin.orders.status.update', $order), [
            'status' => Order::STATUS_CONFIRMED,
        ])->assertForbidden();

        $this->patch(route('admin.orders.payment.update', $order), [
            'payment_status' => Order::PAYMENT_PAID,
        ])->assertForbidden();

        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);
        $this->assertSame(Order::PAYMENT_UNPAID, $order->fresh()->payment_status);
    }

    public function test_admin_order_list_filters_by_search_status_payment_and_date(): void
    {
        $admin = Admin::create([
            'first_name' => 'Order',
            'last_name' => 'Admin',
            'username' => 'order-admin',
            'email' => 'order-admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);
        $matchingOrder = $this->makeOrder();
        $matchingOrder->update([
            'customer_name' => 'Filter Match',
            'customer_email' => 'match@example.com',
            'created_at' => now()->setTime(12, 0),
        ]);
        $otherOrder = $this->makeOrder();
        $otherOrder->update(['created_at' => now()->subDays(2)]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.orders.index', [
                'search' => 'match@example.com',
                'status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_UNPAID,
                'date_from' => now()->toDateString(),
                'date_to' => now()->toDateString(),
            ]))
            ->assertOk()
            ->assertSee($matchingOrder->order_number)
            ->assertDontSee($otherOrder->order_number);
    }

    public function test_only_active_admin_can_collect_cod_and_collection_is_audited_once(): void
    {
        $admin = Admin::create([
            'first_name' => 'Payment',
            'last_name' => 'Admin',
            'username' => 'payment-admin',
            'email' => 'payment-admin@example.com',
            'password' => 'password',
            'role' => 'superadmin',
            'status' => 'active',
        ]);
        $order = $this->makeOrder();

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.orders.payment.update', $order), [
                'payment_status' => Order::PAYMENT_PAID,
                'payment_reference' => 'COD-RECEIPT-19',
                'grand_total' => '0.01',
            ])
            ->assertRedirect(route('admin.orders.show', $order));

        $order->refresh();
        $this->assertSame(Order::PAYMENT_PAID, $order->payment_status);
        $this->assertSame('cash_on_delivery', $order->payment_method);
        $this->assertSame('COD-RECEIPT-19', $order->payment_reference);
        $this->assertSame($admin->id, $order->payment_collected_by_admin_id);
        $this->assertNotNull($order->paid_at);
        $this->assertSame('2599.90', $order->grand_total);

        $this->patch(route('admin.orders.status.update', $order), [
            'status' => Order::STATUS_CANCELLED,
        ])->assertSessionHasErrors('status');

        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);

        $this->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee('12 Garden Town')
            ->assertSee('Payment Admin')
            ->assertSee('Collected by');

        $this->patch(route('admin.orders.payment.update', $order), [
            'payment_status' => Order::PAYMENT_PAID,
        ])->assertSessionHasErrors('payment_status');

        $nonCodOrder = $this->makeOrder();
        $nonCodOrder->update(['payment_method' => 'bank_transfer']);

        $this->patch(route('admin.orders.payment.update', $nonCodOrder), [
            'payment_status' => Order::PAYMENT_PAID,
        ])->assertSessionHasErrors('payment_status');

        $this->assertSame(Order::PAYMENT_UNPAID, $nonCodOrder->fresh()->payment_status);

        $cancelledOrder = $this->makeOrder();
        $cancelledOrder->update(['status' => Order::STATUS_CANCELLED]);

        $this->patch(route('admin.orders.payment.update', $cancelledOrder), [
            'payment_status' => Order::PAYMENT_PAID,
        ])->assertSessionHasErrors('payment_status');

        $this->assertSame(Order::PAYMENT_UNPAID, $cancelledOrder->fresh()->payment_status);
    }

    public function test_checkout_rejects_payment_methods_other_than_cash_on_delivery(): void
    {
        $variant = $this->makeVariant();
        $checkoutToken = $this->startCheckout($variant, 1);

        $this->post(route('store.checkout.place'), $this->checkoutData([
            'checkout_token' => $checkoutToken,
            'payment_method' => 'credit_card',
        ]))
            ->assertSessionHasErrors('payment_method');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_rejects_quantities_above_available_stock(): void
    {
        $variant = $this->makeVariant(['stock_quantity' => 1]);
        $checkoutToken = $this->startCheckout($variant, 2);

        $this->post(route('store.checkout.place'), $this->checkoutData([
            'checkout_token' => $checkoutToken,
        ]))
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(1, $variant->fresh()->stock_quantity);
        $this->assertSame([$variant->id => 2], session('cart'));
    }

    public function test_checkout_rejects_unavailable_variant_without_pruning_cart(): void
    {
        $variant = $this->makeVariant();
        $checkoutToken = $this->startCheckout($variant, 1);
        $variant->update(['is_active' => false]);

        $this->post(route('store.checkout.place'), $this->checkoutData([
            'checkout_token' => $checkoutToken,
        ]))->assertSessionHasErrors('cart');

        $this->assertSame([$variant->id => 1], session('cart'));
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_rejects_product_that_becomes_unavailable_without_pruning_cart(): void
    {
        $variant = $this->makeVariant();
        $checkoutToken = $this->startCheckout($variant, 1);
        $variant->product->update(['is_active' => false]);

        $this->post(route('store.checkout.place'), $this->checkoutData([
            'checkout_token' => $checkoutToken,
        ]))->assertSessionHasErrors('cart');

        $this->assertSame([$variant->id => 1], session('cart'));
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_empty_cart_cannot_open_checkout(): void
    {
        $this->get(route('store.checkout.index'))
            ->assertRedirect(route('store.cart.index'));

        $variant = $this->makeVariant();
        $checkoutToken = $this->startCheckout($variant, 1);

        $this->withSession(['cart' => []])
            ->post(route('store.checkout.place'), $this->checkoutData([
                'checkout_token' => $checkoutToken,
            ]))
            ->assertRedirect(route('store.cart.index'));

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_invalid_customer_and_address_input_keeps_cart_and_old_values(): void
    {
        $variant = $this->makeVariant();
        $checkoutToken = $this->startCheckout($variant, 1);

        $this->post(route('store.checkout.place'), [
            'checkout_token' => $checkoutToken,
            'customer_name' => 'Aman Kaur',
            'customer_email' => 'not-an-email',
            'customer_phone' => 'bad',
            'shipping_address_line_1' => '',
            'shipping_city' => '',
            'shipping_state' => '',
            'shipping_postal_code' => '',
            'shipping_country' => '',
            'payment_method' => 'cash_on_delivery',
        ])->assertSessionHasErrors([
            'customer_email',
            'customer_phone',
            'shipping_address_line_1',
            'shipping_city',
            'shipping_state',
            'shipping_postal_code',
            'shipping_country',
        ]);

        $this->assertSame([$variant->id => 1], session('cart'));

        $this->get(route('store.checkout.index'))
            ->assertOk()
            ->assertSee('Aman Kaur')
            ->assertSee('not-an-email')
            ->assertSee('must be a valid email address');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_repeated_submission_with_same_token_returns_the_existing_order(): void
    {
        $variant = $this->makeVariant();
        $checkoutToken = $this->startCheckout($variant, 1);
        $data = $this->checkoutData(['checkout_token' => $checkoutToken]);

        $firstResponse = $this->post(route('store.checkout.place'), $data);
        $firstResponse->assertRedirect();

        $this->withSession(['cart' => [$variant->id => 3]])
            ->post(route('store.checkout.place'), $data)
            ->assertRedirect($firstResponse->headers->get('Location'));

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertSame([$variant->id => 3], session('cart'));

        $order = Order::query()->firstOrFail();
        $this->get(route('store.orders.show', $order->checkout_token))
            ->assertOk()
            ->assertSee($order->order_number);
        $this->assertDatabaseCount('orders', 1);
    }

    public function test_all_order_items_keep_checkout_price_snapshots_after_catalogue_price_changes(): void
    {
        $firstVariant = $this->makeVariant([
            'price' => '100.00',
            'sku' => 'BEN-SNAPSHOT-01',
            'stock_quantity' => 5,
        ]);
        $secondVariant = ProductVariant::create([
            'product_id' => $firstVariant->product_id,
            'sku' => 'BEN-SNAPSHOT-02',
            'size' => 'L',
            'color' => 'Ivory',
            'price' => '200.00',
            'stock_quantity' => 5,
            'is_active' => true,
        ]);

        $this->withSession(['cart' => [
            $firstVariant->id => 1,
            $secondVariant->id => 2,
        ]]);
        $checkoutResponse = $this->get(route('store.checkout.index'));
        $checkoutResponse->assertOk();
        preg_match('/name="checkout_token" value="([^"]+)"/', $checkoutResponse->getContent(), $matches);
        $this->assertNotEmpty($matches[1] ?? null);

        $this->post(route('store.checkout.place'), $this->checkoutData([
            'checkout_token' => $matches[1],
        ]))->assertRedirect();

        $order = Order::query()->with('items')->firstOrFail();
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 2);
        $this->assertSame('500.00', $order->subtotal);
        $this->assertSame(500.0 + (float) $order->shipping_total, (float) $order->grand_total);

        $firstVariant->update(['price' => '9999.00']);
        $secondVariant->update(['price' => '8888.00']);

        $this->assertSame('100.00', $order->items->firstWhere('sku', 'BEN-SNAPSHOT-01')->unit_price);
        $this->assertSame('200.00', $order->items->firstWhere('sku', 'BEN-SNAPSHOT-02')->unit_price);
        $this->assertSame('400.00', $order->items->firstWhere('sku', 'BEN-SNAPSHOT-02')->line_total);

        $this->get(route('store.orders.show', $order->checkout_token))
            ->assertOk()
            ->assertSee('500.00')
            ->assertSee('100.00')
            ->assertSee('400.00');
    }

    public function test_database_failure_does_not_report_success_or_clear_cart(): void
    {
        $variant = $this->makeVariant();
        $checkoutToken = $this->startCheckout($variant, 1);

        DB::unprepared("CREATE TRIGGER fail_order_insert BEFORE INSERT ON orders BEGIN SELECT RAISE(ABORT, 'forced order failure'); END");

        $this->post(route('store.checkout.place'), $this->checkoutData([
            'checkout_token' => $checkoutToken,
        ]))
            ->assertSessionHasErrors('checkout');

        $this->assertSame([$variant->id => 1], session('cart'));
        $this->assertFalse(session()->has('success'));
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_order_item_snapshot_survives_product_deletion(): void
    {
        $variant = $this->makeVariant();
        $order = $this->makeOrder();
        $order->items()->create([
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'product_name' => $variant->product->name,
            'sku' => $variant->sku,
            'size' => $variant->size,
            'color' => $variant->color,
            'quantity' => 1,
            'unit_price' => $variant->price,
            'line_total' => $variant->price,
        ]);

        $variant->product->delete();

        $item = $order->items()->firstOrFail();
        $this->assertNull($item->product_id);
        $this->assertNull($item->product_variant_id);
        $this->assertSame('Punjabi Suit', $item->product_name);
        $this->assertSame('BEN-001', $item->sku);
        $this->assertSame('1299.95', $item->unit_price);
    }

    public function test_confirmation_deducts_stock_and_cancellation_restores_it(): void
    {
        Mail::fake();
        $variant = $this->makeVariant(['stock_quantity' => 5]);
        $order = $this->makeOrder();
        $order->update(['customer_email' => 'simran@example.com']);
        $order->items()->create([
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'product_name' => $variant->product->name,
            'sku' => $variant->sku,
            'size' => $variant->size,
            'color' => $variant->color,
            'quantity' => 2,
            'unit_price' => $variant->price,
            'line_total' => (float) $variant->price * 2,
        ]);
        $admin = Admin::create([
            'first_name' => 'Aman',
            'last_name' => 'Kaur',
            'username' => 'aman',
            'email' => 'aman@example.com',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status.update', $order), [
                'status' => Order::STATUS_CONFIRMED,
            ])
            ->assertRedirect();

        $this->assertSame(3, $variant->fresh()->stock_quantity);
        $this->assertNotNull($order->fresh()->stock_deducted_at);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status.update', $order), [
                'status' => Order::STATUS_CANCELLED,
                'note' => 'Customer requested cancellation.',
            ])
            ->assertRedirect();

        $order->refresh();
        $this->assertSame(5, $variant->fresh()->stock_quantity);
        $this->assertNull($order->stock_deducted_at);
        $this->assertNotNull($order->stock_restored_at);

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.orders.status.update', $order), [
                'status' => Order::STATUS_CANCELLED,
                'note' => 'Repeat cancellation attempt.',
            ])
            ->assertSessionHasErrors('status');

        $this->assertSame(5, $variant->fresh()->stock_quantity);
        $this->assertDatabaseCount('order_status_histories', 2);
        Mail::assertQueued(OrderNotificationMail::class, 2);
        Mail::assertQueued(
            OrderNotificationMail::class,
            fn(OrderNotificationMail $mail) =>
            $mail->notificationType === 'confirmed'
        );
        Mail::assertQueued(
            OrderNotificationMail::class,
            fn(OrderNotificationMail $mail) =>
            $mail->notificationType === 'cancelled'
        );
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_CANCELLED,
        ]);
    }

    public function test_shipping_does_not_mark_order_delivered_automatically(): void
    {
        Mail::fake();
        $variant = $this->makeVariant(['stock_quantity' => 5]);
        $order = $this->makeOrder();
        $order->update(['customer_email' => 'simran@example.com']);
        $order->items()->create([
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'product_name' => $variant->product->name,
            'sku' => $variant->sku,
            'size' => $variant->size,
            'color' => $variant->color,
            'quantity' => 1,
            'unit_price' => $variant->price,
            'line_total' => $variant->price,
        ]);
        $admin = Admin::create([
            'first_name' => 'Status',
            'last_name' => 'Admin',
            'username' => 'status-admin',
            'email' => 'status-admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin, 'admin');
        foreach ([Order::STATUS_CONFIRMED, Order::STATUS_PROCESSING, Order::STATUS_SHIPPED] as $status) {
            $this->patch(route('admin.orders.status.update', $order), [
                'status' => $status,
            ])->assertRedirect();
        }

        $this->assertSame(Order::STATUS_SHIPPED, $order->fresh()->status);
        $this->assertNull($order->fresh()->delivered_at);

        $this->patch(route('admin.orders.status.update', $order), [
            'status' => Order::STATUS_DELIVERED,
        ])->assertRedirect();

        $this->assertSame(Order::STATUS_DELIVERED, $order->fresh()->status);
        $this->assertNotNull($order->fresh()->delivered_at);
        Mail::assertQueued(OrderNotificationMail::class, 3);
        foreach ([Order::STATUS_CONFIRMED, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED] as $notificationType) {
            Mail::assertQueued(
                OrderNotificationMail::class,
                fn(OrderNotificationMail $mail) =>
                $mail->notificationType === $notificationType
            );
        }
    }

    public function test_confirmation_rejects_insufficient_stock_without_deducting_it(): void
    {
        $variant = $this->makeVariant(['stock_quantity' => 1]);
        $order = $this->makeOrder();
        $order->items()->create([
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'product_name' => $variant->product->name,
            'sku' => $variant->sku,
            'size' => $variant->size,
            'color' => $variant->color,
            'quantity' => 2,
            'unit_price' => $variant->price,
            'line_total' => (float) $variant->price * 2,
        ]);
        $admin = Admin::create([
            'first_name' => 'Aman',
            'last_name' => 'Kaur',
            'username' => 'aman',
            'email' => 'aman@example.com',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status.update', $order), [
                'status' => Order::STATUS_CONFIRMED,
            ])
            ->assertSessionHasErrors('status');

        $this->assertSame(1, $variant->fresh()->stock_quantity);
        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);
    }

    private function makeVariant(array $overrides = []): ProductVariant
    {
        $product = Product::create([
            'name' => 'Punjabi Suit',
            'slug' => Str::slug('Punjabi Suit'),
            'is_active' => true,
            'is_featured' => false,
        ]);

        return ProductVariant::create(array_merge([
            'product_id' => $product->id,
            'sku' => 'BEN-001',
            'size' => 'M',
            'color' => 'Burgundy',
            'price' => '1299.95',
            'stock_quantity' => 5,
            'is_active' => true,
        ], $overrides));
    }

    private function makeOrder(): Order
    {
        return Order::create([
            'order_number' => 'BNZ' . Str::upper(Str::random(12)),
            'customer_name' => 'Simran Kaur',
            'customer_phone' => '+923001234567',
            'shipping_address_line_1' => '12 Garden Town',
            'shipping_city' => 'Lahore',
            'shipping_country' => 'Pakistan',
            'currency' => 'PKR',
            'subtotal' => 2599.90,
            'discount_total' => 0,
            'shipping_total' => 0,
            'tax_total' => 0,
            'grand_total' => 2599.90,
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_UNPAID,
            'payment_method' => 'cash_on_delivery',
        ]);
    }

    private function checkoutData(array $overrides = []): array
    {
        return array_merge([
            'customer_name' => 'Simran Kaur',
            'customer_email' => 'simran@example.com',
            'customer_phone' => '+923001234567',
            'shipping_address_line_1' => '12 Garden Town',
            'shipping_city' => 'Lahore',
            'shipping_state' => 'Punjab',
            'shipping_postal_code' => '54000',
            'shipping_country' => 'Pakistan',
            'payment_method' => 'cash_on_delivery',
        ], $overrides);
    }

    private function startCheckout(ProductVariant $variant, int $quantity): string
    {
        $this->withSession(['cart' => [$variant->id => $quantity]]);

        $response = $this->get(route('store.checkout.index'));
        $response->assertOk();

        preg_match('/name="checkout_token" value="([^"]+)"/', $response->getContent(), $matches);

        $this->assertNotEmpty($matches[1] ?? null);

        return $matches[1];
    }
}
