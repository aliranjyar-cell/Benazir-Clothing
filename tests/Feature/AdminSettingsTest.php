<?php
namespace Tests\Feature;
use App\Models\Admin;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | Overview & section navigation
    |--------------------------------------------------------------------------
    */

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get(route('admin.settings.index'))->assertRedirect(route('login'));
    }

    public function test_overview_lists_all_eight_sections(): void
    {
        $response = $this->actingAs($this->makeAdmin())->get(route('admin.settings.index'));

        $response->assertOk()
            ->assertSee('General Store')
            ->assertSee('Storefront & Branding')
            ->assertSee('Products & Inventory')
            ->assertSee('Shipping & Delivery')
            ->assertSee('Checkout & Payments')
            ->assertSee('Orders & Notifications')
            ->assertSee('Policies & Support')
            ->assertSee('Account & Security');
    }

    public function test_every_implemented_section_page_renders(): void
    {
        $admin = $this->makeAdmin();

        foreach (['general', 'products', 'shipping', 'checkout'] as $section) {
            $this->actingAs($admin)
                ->get(route("admin.settings.{$section}"))
                ->assertOk()
                ->assertSee('Save settings', false);
        }
    }

    public function test_unimplemented_sections_show_roadmap_and_unknown_slugs_404(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->get(route('admin.settings.section', 'storefront-branding'))
            ->assertOk()
            ->assertSee('Coming soon', false);

        $this->actingAs($admin)
            ->get('/settings/not-a-section')
            ->assertNotFound();
    }

    /*
    |--------------------------------------------------------------------------
    | Saving & validation
    |--------------------------------------------------------------------------
    */

    public function test_general_settings_can_be_saved(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post(route('admin.settings.general.update'), [
                'store_name' => 'BENAZIR Couture',
                'store_tagline' => 'Lahore • London',
                'contact_email' => 'care@benazir.test',
                'contact_phone' => '+92 300 1112223',
                'address' => 'Gulberg, Lahore',
                'currency' => 'PKR',
                'timezone' => 'Asia/Karachi',
                'store_status' => 'open',
            ])
            ->assertRedirect(route('admin.settings.general'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('settings', ['key' => 'store_name', 'value' => 'BENAZIR Couture']);
        $this->assertDatabaseHas('settings', ['key' => 'contact_email', 'value' => 'care@benazir.test']);
    }

    public function test_general_settings_require_valid_input(): void
    {
        $this->actingAs($this->makeAdmin())
            ->from(route('admin.settings.general'))
            ->post(route('admin.settings.general.update'), [
                'store_name' => '',
                'contact_email' => 'not-an-email',
                'currency' => 'EUR',
                'timezone' => 'Mars/Olympus',
                'store_status' => 'open',
            ])
            ->assertSessionHasErrors(['store_name', 'contact_email', 'currency', 'timezone']);
    }

    public function test_legacy_post_settings_endpoint_still_updates_general_settings(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post(route('admin.settings.update'), [
                'store_name' => 'BENAZIR Legacy',
                'store_tagline' => 'Kept working',
                'contact_email' => 'legacy@benazir.test',
                'contact_phone' => '',
                'address' => '',
                'currency' => 'PKR',
                'timezone' => 'Asia/Karachi',
                'store_status' => 'open',
            ])
            ->assertRedirect(route('admin.settings.general'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('settings', ['key' => 'store_name', 'value' => 'BENAZIR Legacy']);
    }

    public function test_shipping_settings_can_be_saved_and_validated(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->post(route('admin.settings.shipping.update'), [
                'shipping_flat_fee' => '150',
                'shipping_free_over' => '5000',
                'shipping_estimated_days' => '2–4 business days',
            ])
            ->assertRedirect(route('admin.settings.shipping'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('settings', ['key' => 'shipping_flat_fee', 'value' => '150']);
        $this->assertDatabaseHas('settings', ['key' => 'shipping_free_over', 'value' => '5000']);

        // Fees must be numeric and the estimate is required; a blank
        // threshold (never free) passes the nullable rule.
        $this->actingAs($admin)
            ->post(route('admin.settings.shipping.update'), [
                'shipping_flat_fee' => 'abc',
                'shipping_free_over' => '',
                'shipping_estimated_days' => '',
            ])
            ->assertSessionHasErrors(['shipping_flat_fee', 'shipping_estimated_days']);
    }

    public function test_checkout_settings_save_payment_methods_and_toggles(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post(route('admin.settings.checkout.update'), [
                'payment_methods_enabled' => ['cash_on_delivery', 'bank_transfer'],
                'checkout_default_payment_method' => 'bank_transfer',
                'checkout_allow_guest' => '0',
                'checkout_order_notes' => '0',
                'checkout_bank_details' => "Meezan Bank\nIBAN PK00TEST",
            ])
            ->assertRedirect(route('admin.settings.checkout'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('settings', [
            'key' => 'payment_methods_enabled',
            'value' => json_encode(['cash_on_delivery', 'bank_transfer']),
        ]);
        $this->assertDatabaseHas('settings', ['key' => 'checkout_default_payment_method', 'value' => 'bank_transfer']);
        $this->assertDatabaseHas('settings', ['key' => 'checkout_allow_guest', 'value' => '0']);
    }

    public function test_checkout_default_method_must_be_enabled(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post(route('admin.settings.checkout.update'), [
                'payment_methods_enabled' => ['cash_on_delivery'],
                'checkout_default_payment_method' => 'bank_transfer',
            ])
            ->assertSessionHasErrors('checkout_default_payment_method');
    }

    public function test_checkout_requires_at_least_one_payment_method(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post(route('admin.settings.checkout.update'), [
                'checkout_default_payment_method' => 'cash_on_delivery',
            ])
            ->assertSessionHasErrors('payment_methods_enabled');
    }

    public function test_products_settings_save_and_dashboard_reads_threshold(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->post(route('admin.settings.products.update'), [
                'low_stock_threshold' => '7',
                'products_per_page' => '9',
            ])
            ->assertRedirect(route('admin.settings.products'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('settings', ['key' => 'low_stock_threshold', 'value' => '7']);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Variants with 7 units or fewer');
    }

    public function test_products_settings_validation_bounds(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post(route('admin.settings.products.update'), [
                'low_stock_threshold' => '0',
                'products_per_page' => '500',
            ])
            ->assertSessionHasErrors(['low_stock_threshold', 'products_per_page']);
    }

    /*
    |--------------------------------------------------------------------------
    | Settings → config bridge
    |--------------------------------------------------------------------------
    | Providers boot once per application instance; in production every
    | request boots fresh. rebootStoreSettings() re-runs the provider's
    | boot() to mirror that, so saved settings are re-read into config.
    */

    public function test_saved_shipping_settings_flow_into_checkout_config(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post(route('admin.settings.shipping.update'), [
                'shipping_flat_fee' => '250',
                'shipping_free_over' => '4000',
                'shipping_estimated_days' => '3–6 business days',
            ]);

        $this->rebootStoreSettings();

        $this->assertSame(250.0, config('storefront.checkout.shipping_flat'));
        $this->assertSame(4000.0, config('storefront.checkout.shipping_free_over'));
        $this->assertSame('3–6 business days', config('storefront.shipping.estimated_days'));
    }

    public function test_saved_checkout_settings_flow_into_checkout_config(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post(route('admin.settings.checkout.update'), [
                'payment_methods_enabled' => ['bank_transfer'],
                'checkout_default_payment_method' => 'bank_transfer',
                'checkout_allow_guest' => '0',
                'checkout_order_notes' => '0',
                'checkout_bank_details' => 'IBAN PK11BANK',
            ]);

        $this->rebootStoreSettings();

        $this->assertSame(
            ['bank_transfer' => 'Bank Transfer'],
            config('storefront.checkout.payment_methods')
        );
        $this->assertSame('bank_transfer', config('storefront.checkout.default_payment_method'));
        $this->assertFalse(config('storefront.checkout.allow_guest_checkout'));
        $this->assertFalse(config('storefront.checkout.order_notes_enabled'));
        $this->assertSame('IBAN PK11BANK', config('storefront.checkout.bank_details'));
    }

    public function test_saved_products_settings_flow_into_catalog_config(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post(route('admin.settings.products.update'), [
                'low_stock_threshold' => '7',
                'products_per_page' => '9',
            ]);

        $this->rebootStoreSettings();

        $this->assertSame(9, config('storefront.catalog.per_page'));
    }

    /*
    |--------------------------------------------------------------------------
    | Storefront behaviour driven by the settings
    |--------------------------------------------------------------------------
    */

    public function test_disabling_guest_checkout_redirects_guests_to_login(): void
    {
        config(['storefront.checkout.allow_guest_checkout' => false]);

        $this->get(route('store.checkout.index'))->assertRedirect(route('store.login'));
    }

    public function test_checkout_shipping_row_reflects_flat_fee_and_free_threshold(): void
    {
        $variant = $this->makeVariant();

        // Below the threshold: the flat fee applies and the "Free over"
        // hint is shown.
        config([
            'storefront.checkout.shipping_flat' => 200.0,
            'storefront.checkout.shipping_free_over' => 4000.0,
            'storefront.shipping.estimated_days' => '2–4 business days',
        ]);

        $this->withSession(['cart' => [$variant->id => 1]])
            ->get(route('store.checkout.index'))
            ->assertOk()
            ->assertSee('2–4 business days')
            ->assertSee('Free over 4,000.00');

        // At or above the threshold: shipping becomes free.
        config(['storefront.checkout.shipping_free_over' => 1000.0]);

        $this->withSession(['cart' => [$variant->id => 1]])
            ->get(route('store.checkout.index'))
            ->assertOk()
            ->assertSee('Free');
    }

    public function test_inventory_pages_reflect_low_stock_threshold(): void
    {
        $admin = $this->makeAdmin();
        $variant = $this->makeVariant(['stock_quantity' => 2]);

        $this->actingAs($admin)
            ->post(route('admin.settings.products.update'), [
                'low_stock_threshold' => '2',
                'products_per_page' => '12',
            ]);

        $this->actingAs($admin)
            ->get(route('admin.inventory.index'))
            ->assertOk()
            ->assertSee('Between 1 and 2 units', false)
            ->assertSee('Low stock (1–2)', false);

        $this->actingAs($admin)
            ->get(route('admin.inventory.adjust', $variant))
            ->assertOk()
            ->assertSee('Low stock', false);
    }

    /*
    |--------------------------------------------------------------------------
    | Account & Security
    |--------------------------------------------------------------------------
    */

    public function test_account_security_page_renders(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get(route('admin.settings.account-security'))
            ->assertOk()
            ->assertSee('Account & Security')
            ->assertSee('Change password', false)
            ->assertSee('Simran', false)
            ->assertSee('simran@example.com', false);
    }

    public function test_admin_can_change_password(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->post(route('admin.settings.account-security.password'), [
                'current_password' => 'password',
                'password' => 'NewSecret123',
                'password_confirmation' => 'NewSecret123',
            ])
            ->assertRedirect(route('admin.settings.account-security'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('NewSecret123', Admin::find($admin->id)->password));
        $this->assertFalse(Hash::check('password', Admin::find($admin->id)->password));
    }

    public function test_password_change_rejects_wrong_current_password(): void
    {
        $this->actingAs($this->makeAdmin())
            ->from(route('admin.settings.account-security'))
            ->post(route('admin.settings.account-security.password'), [
                'current_password' => 'wrong-password',
                'password' => 'NewSecret123',
                'password_confirmation' => 'NewSecret123',
            ])
            ->assertSessionHasErrors('current_password');
    }

    public function test_password_change_requires_confirmation(): void
    {
        $this->actingAs($this->makeAdmin())
            ->from(route('admin.settings.account-security'))
            ->post(route('admin.settings.account-security.password'), [
                'current_password' => 'password',
                'password' => 'NewSecret123',
            ])
            ->assertSessionHasErrors('password');
    }

    public function test_password_change_rejects_reusing_current_password(): void
    {
        $this->actingAs($this->makeAdmin())
            ->from(route('admin.settings.account-security'))
            ->post(route('admin.settings.account-security.password'), [
                'current_password' => 'password',
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertSessionHasErrors('password');
    }

    /*
    |--------------------------------------------------------------------------
    | Orders & Notifications
    |--------------------------------------------------------------------------
    */

    public function test_orders_notifications_page_renders(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->get(route('admin.settings.orders-notifications'))
            ->assertOk()
            ->assertSee('Orders & Notifications')
            ->assertSee($admin->first_name, false)
            ->assertSee($admin->email, false);
    }

    public function test_placing_an_order_creates_a_notification_for_each_active_admin(): void
    {
        $admin = $this->makeAdmin();
        $variant = $this->makeVariant(['price' => '1299.95', 'stock_quantity' => 4]);
        $checkoutToken = $this->startCheckout($variant, 1);

        $this->post(route('store.checkout.place'), $this->checkoutData([
            'checkout_token' => $checkoutToken,
            'payment_method' => 'cash_on_delivery',
            'unit_price' => '1299.95',
            'subtotal' => '1299.95',
            'discount_total' => '0',
            'grand_total' => '1299.95',
        ]))->assertRedirect();

        $order = Order::query()->firstOrFail();

        $this->assertDatabaseHas('notifications', [
            'type' => 'new_order',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $admin->id,
        ]);

        $notification = Notification::where('notifiable_id', $admin->id)
            ->where('data->order_number', $order->order_number)
            ->first();

        $this->assertNotNull($notification);
        $this->assertNull($notification->read_at);
        $this->assertSame((float) $order->grand_total, $notification->data['grand_total']);
    }

    public function test_notification_can_be_marked_as_read(): void
    {
        $admin = $this->makeAdmin();
        $notification = Notification::create([
            'type' => 'new_order',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $admin->id,
            'data' => ['order_number' => 'BNZ-0001'],
        ]);

        $this->assertNull($notification->read_at);

        $this->actingAs($admin)
            ->post(route('admin.settings.orders-notifications.read', $notification))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_inactive_admin_does_not_receive_order_notifications(): void
    {
        $activeAdmin = $this->makeAdmin();
        Admin::create([
            'first_name' => 'Inactive',
            'last_name' => 'Admin',
            'username' => 'inactive',
            'email' => 'inactive@example.com',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'inactive',
        ]);

        $variant = $this->makeVariant(['price' => '1299.95', 'stock_quantity' => 4]);
        $checkoutToken = $this->startCheckout($variant, 1);

        $this->post(route('store.checkout.place'), $this->checkoutData([
            'checkout_token' => $checkoutToken,
            'payment_method' => 'cash_on_delivery',
            'unit_price' => '1299.95',
            'subtotal' => '1299.95',
            'discount_total' => '0',
            'grand_total' => '1299.95',
        ]))->assertRedirect();

        $this->assertDatabaseHas('notifications', [
            'type' => 'new_order',
            'notifiable_id' => $activeAdmin->id,
        ]);
        $this->assertDatabaseMissing('notifications', [
            'type' => 'new_order',
            'notifiable_id' => Admin::where('status', 'inactive')->first()->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function rebootStoreSettings(): void
    {
        (new AppServiceProvider($this->app))->boot();
    }

    private function makeVariant(array $overrides = []): ProductVariant
    {
        $product = Product::create([
            'name' => 'Punjabi Suit',
            'slug' => Str::slug('Punjabi Suit-' . Str::random(6)),
            'is_active' => true,
            'is_featured' => false,
        ]);

        return ProductVariant::create(array_merge([
            'product_id' => $product->id,
            'sku' => 'BEN-' . Str::upper(Str::random(6)),
            'size' => 'M',
            'color' => 'Burgundy',
            'price' => '1299.95',
            'stock_quantity' => 5,
            'is_active' => true,
        ], $overrides));
    }

    private function makeAdmin(): Admin
    {
        return Admin::create([
            'first_name' => 'Simran',
            'last_name' => 'Kaur',
            'username' => 'simran',
            'email' => 'simran@example.com',
            'password' => 'password',
            'role' => 'superadmin',
            'status' => 'active',
        ]);
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
}
