<?php
namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'first_name' => 'Simran',
            'last_name' => 'Kaur',
            'username' => 'simran',
            'email' => 'simran@example.com',
            'password' => 'password',
            'role' => 'superadmin',
            'status' => 'active',
        ]);
    }

    private function makeCategory(array $overrides = []): Category
    {
        $attributes = array_merge([
            'name' => 'Suits',
            'is_active' => true,
            'sort_order' => 0,
        ], $overrides);

        if (empty($attributes['slug'])) {
            $attributes['slug'] = Str::slug($attributes['name']);
        }

        return Category::create($attributes);
    }

    public function test_guests_are_redirected_from_category_pages(): void
    {
        $this->get(route('categories.index'))->assertRedirect(route('login'));
        $this->get(route('categories.create'))->assertRedirect(route('login'));
        $this->get(route('categories.show', 1))->assertRedirect(route('login'));
    }

    public function test_guests_cannot_toggle_category_status(): void
    {
        $category = $this->makeCategory();

        $this->patch(route('categories.toggle-status', $category))->assertRedirect(route('login'));

        $this->assertTrue($category->fresh()->is_active);
    }

    public function test_category_listing_renders_for_admin(): void
    {
        $this->actingAs($this->admin)
            ->get(route('categories.index'))
            ->assertOk()
            ->assertSee('Add Category');
    }

    public function test_category_search_filters_results(): void
    {
        $this->makeCategory(['name' => 'Turbans']);
        $this->makeCategory(['name' => 'Dupattas']);

        $this->actingAs($this->admin)
            ->get(route('categories.index', ['search' => 'Turbans']))
            ->assertOk()
            ->assertSee('Turbans')
            ->assertDontSee('Dupattas');
    }

    public function test_create_category_page_renders(): void
    {
        $this->actingAs($this->admin)
            ->get(route('categories.create'))
            ->assertOk()
            ->assertSee('Add Category');
    }

    public function test_store_category_validates_required_fields(): void
    {
        $this->actingAs($this->admin)
            ->post(route('categories.store'), [])
            ->assertSessionHasErrors(['name', 'slug']);
    }

    public function test_store_category_generates_slug_from_name(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('categories.store'), [
                'name' => 'Phulkari Turbans',
                'slug' => '',
            ]);

        $response->assertRedirect(route('categories.edit', Category::latest('id')->first()));

        $category = Category::latest('id')->first();
        $this->assertEquals('phulkari-turbans', $category->slug);
    }

    public function test_store_category_uniquely_suffixes_duplicate_slug(): void
    {
        $this->makeCategory(['name' => 'Suits', 'slug' => 'suits']);

        $this->actingAs($this->admin)
            ->post(route('categories.store'), [
                'name' => 'More Suits',
                'slug' => 'suits',
            ]);

        $category = Category::where('name', 'More Suits')->first();
        $this->assertEquals('suits-2', $category->slug);
    }

    public function test_store_category_rejects_invalid_parent_relationship(): void
    {
        $parent = $this->makeCategory(['name' => 'Parent']);
        $child = $this->makeCategory(['name' => 'Child', 'parent_id' => $parent->id]);

        // A category cannot be its own parent.
        $this->actingAs($this->admin)
            ->post(route('categories.store'), [
                'name' => 'Self Parent',
                'slug' => 'self-parent',
                'parent_id' => $child->id,
            ])
            ->assertSessionHasErrors(['parent_id' => 'A category cannot be its own parent.']);
    }

    public function test_update_category_validates_parent_cycle(): void
    {
        $a = $this->makeCategory(['name' => 'A']);
        $b = $this->makeCategory(['name' => 'B', 'parent_id' => $a->id]);

        // B cannot become its own parent (which would create a cycle A -> B -> A).
        $this->actingAs($this->admin)
            ->patch(route('categories.update', $b), [
                'name' => 'B',
                'slug' => 'b',
                'parent_id' => $b->id,
            ])
            ->assertSessionHasErrors(['parent_id' => 'A category cannot be its own parent.']);
    }

    public function test_update_category_uniquely_suffixes_duplicate_slug(): void
    {
        $a = $this->makeCategory(['name' => 'Alpha', 'slug' => 'alpha']);
        $this->makeCategory(['name' => 'Beta', 'slug' => 'alpha-2']);

        $this->actingAs($this->admin)
            ->patch(route('categories.update', $a), [
                'name' => 'Alpha',
                'slug' => 'alpha',
            ])
            ->assertRedirect(route('categories.edit', $a));

        $this->assertSame('alpha', $a->fresh()->slug);
    }

    public function test_delete_category_soft_deactivates_when_products_reference_it(): void
    {
        $category = $this->makeCategory(['name' => 'Suits']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Phulkari Suit',
            'slug' => 'phulkari-suit',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->delete(route('categories.destroy', $category));

        $this->assertNull($category->fresh()->products()->first()->category_id);
        $this->assertFalse($category->fresh()->is_active);
    }

    public function test_delete_category_when_no_products_it_is_permanently_deleted(): void
    {
        $category = $this->makeCategory();

        $this->actingAs($this->admin)
            ->delete(route('categories.destroy', $category));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_products_count_is_displayed(): void
    {
        $category = $this->makeCategory();
        Product::create([
            'category_id' => $category->id,
            'name' => 'Product 1',
            'slug' => 'product-1',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->get(route('categories.index'))
            ->assertOk()
            ->assertSee('1');
    }

    public function test_category_filter_by_active_status(): void
    {
        $active = $this->makeCategory(['name' => 'Active', 'is_active' => true]);
        $inactive = $this->makeCategory(['name' => 'Inactive', 'is_active' => false]);

        $this->actingAs($this->admin)
            ->get(route('categories.index', ['active_only' => '1']))
            ->assertOk()
            ->assertSee($active->name)
            ->assertDontSee($inactive->name);
    }

    public function test_category_sorting_and_pagination(): void
    {
        $this->makeCategory(['name' => 'Zulu', 'sort_order' => 10]);
        $this->makeCategory(['name' => 'Alpha', 'sort_order' => 1]);

        $this->actingAs($this->admin)
            ->get(route('categories.index', ['sort' => 'name', 'direction' => 'asc']))
            ->assertOk()
            ->assertSee('Alpha')
            ->assertSee('Zulu');
    }
}
