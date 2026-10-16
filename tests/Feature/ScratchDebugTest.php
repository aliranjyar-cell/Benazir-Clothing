<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ScratchDebugTest extends TestCase
{
    use RefreshDatabase;

    public function test_debug_duplicate_slug(): void
    {
        $admin = Admin::create([
            'first_name' => 'Simran',
            'last_name' => 'Kaur',
            'username' => 'simran',
            'email' => 'simran@example.com',
            'password' => 'password',
            'role' => 'superadmin',
            'status' => 'active',
        ]);

        Category::create([
            'name' => 'Suits',
            'slug' => 'suits',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($admin);
        $this->withoutExceptionHandling();

        try {
            $response = $this->post(route('categories.store'), [
                'name' => 'More Suits',
                'slug' => 'suits',
            ]);

            dump('status: '.$response->getStatusCode());
            dump('location: '.$response->headers->get('Location'));
            dump('errors: '.json_encode(session('errors')?->toArray()));
            dump('categories: '.json_encode(Category::all(['id', 'name', 'slug'])->toArray()));
        } catch (\Throwable $e) {
            dump('EXCEPTION: '.get_class($e).': '.$e->getMessage());
            dump('file: '.$e->getFile().':'.$e->getLine());
        }

        $this->assertTrue(true);
    }
}
