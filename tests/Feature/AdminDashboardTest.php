<?php

namespace Tests\Feature;

use App\Models\Admin;
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
