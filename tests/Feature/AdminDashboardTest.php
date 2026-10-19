<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_sees_the_admin_dashboard(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('AURELLE')
            ->assertSee('Fashion Operations')
            ->assertSee('Welcome back, Simran')
            ->assertSee('Products')
            ->assertSee('Categories')
            ->assertSee('Low stock')
            ->assertSee('Orders')
            ->assertSee('Quick actions')
            ->assertSee('Recent activity')
            ->assertSee('aria-current="page"', false);
    }

    public function test_admin_can_log_in_and_reach_the_dashboard(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->post(route('login'), [
            'username' => $admin->username,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Welcome back, Simran');
    }

    public function test_dashboard_shows_flash_messages_when_present(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->withSession(['success' => 'Profile updated successfully.'])
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Profile updated successfully.', false)
            ->assertSee('role="status"', false);
    }

    public function test_dashboard_displays_live_order_data_and_sales_chart(): void
    {
        $admin = $this->makeAdmin();

        Order::create([
            'order_number' => 'ORD-1001',
            'checkout_token' => 'token-1',
            'customer_name' => 'Asha Singh',
            'customer_email' => 'asha@example.com',
            'customer_phone' => '5550001111',
            'shipping_address_line_1' => '12 Market Street',
            'shipping_city' => 'Lahore',
            'shipping_country' => 'Pakistan',
            'currency' => 'USD',
            'subtotal' => 120.00,
            'discount_total' => 0,
            'shipping_total' => 15.00,
            'tax_total' => 8.00,
            'grand_total' => 143.00,
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_PAID,
            'payment_method' => 'card',
            'customer_note' => 'Quick test order',
        ]);

        Order::create([
            'order_number' => 'ORD-1002',
            'checkout_token' => 'token-2',
            'customer_name' => 'Rohan Mehta',
            'customer_email' => 'rohan@example.com',
            'customer_phone' => '5550002222',
            'shipping_address_line_1' => '8 Gulshan Road',
            'shipping_city' => 'Karachi',
            'shipping_country' => 'Pakistan',
            'currency' => 'USD',
            'subtotal' => 90.00,
            'discount_total' => 0,
            'shipping_total' => 10.00,
            'tax_total' => 6.00,
            'grand_total' => 106.00,
            'status' => Order::STATUS_CONFIRMED,
            'payment_status' => Order::PAYMENT_UNPAID,
            'payment_method' => 'cash_on_delivery',
            'customer_note' => 'Second test order',
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Sales overview')
            ->assertSee('ORD-1001')
            ->assertSee('ORD-1002');
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
}
