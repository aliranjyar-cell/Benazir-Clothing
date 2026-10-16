<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_customer_login_screen_can_be_rendered(): void
    {
        $this->get(route('store.login'))
            ->assertOk()
            ->assertSee('Customer sign in')
            ->assertSee(route('register'))
            ->assertSee(route('password.request'));
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        // /login is the admin portal sign-in: it authenticates against
        // the admin guard using a username, then lands on the dashboard.
        $admin = Admin::create([
            'first_name' => 'Simran',
            'last_name' => 'Kaur',
            'username' => Str::slug('simran.kaur'),
            'email' => 'simran@example.com',
            'password' => 'password',
            'role' => 'superadmin',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'username' => $admin->username,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_admin_can_still_logout_through_the_existing_route(): void
    {
        $admin = Admin::create([
            'first_name' => 'Simran',
            'last_name' => 'Kaur',
            'username' => 'admin-simran',
            'email' => 'admin.simran@example.com',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('logout'))
            ->assertRedirect('/');

        $this->assertGuest('admin');
    }

    public function test_customer_can_sign_in_through_the_storefront_route(): void
    {
        $user = User::factory()->create();

        $this->post(route('store.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertRedirect(route('store.account'));

        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_customer_login_rejects_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $this->post(route('store.login'), [
            'email' => $user->email,
            'password' => 'incorrect-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('web');
    }

    public function test_customer_account_pages_redirect_to_customer_sign_in(): void
    {
        $this->get(route('store.account'))
            ->assertRedirect(route('store.login'));
    }

    public function test_customer_logout_preserves_an_authenticated_admin_guard(): void
    {
        $admin = Admin::create([
            'first_name' => 'Simran',
            'last_name' => 'Kaur',
            'username' => 'simran-kaur',
            'email' => 'simran.admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);
        $customer = User::factory()->create();

        $this->actingAs($admin, 'admin')
            ->actingAs($customer, 'web')
            ->post(route('store.logout'))
            ->assertRedirect(route('store.home'));

        $this->assertGuest('web');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_customer_links_are_available_in_the_storefront_header(): void
    {
        $this->get(route('store.home'))
            ->assertOk()
            ->assertSee(route('store.login'))
            ->assertSee(route('register'));
    }
}
