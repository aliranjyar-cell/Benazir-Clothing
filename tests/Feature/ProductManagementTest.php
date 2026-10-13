<?php
namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductManagementTest extends TestCase
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

    private function makeCategory(string $name = 'Suits'): Category
    {
        return Category::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }

    private function makeProduct(array $overrides = []): Product
    {
        $name = $overrides['name'] ?? 'Phulkari Anarkali Suit';

        return Product::create(array_merge([
            'category_id' => null,
            'name' => $name,
            'slug' => Str::slug($name),
            'is_active' => true,
            'is_featured' => false,
        ], $overrides));
    }

    public function test_guests_are_redirected_from_product_pages(): void
    {
        $this->get(route('products.index'))->assertRedirect(route('login'));
        $this->get(route('products.create'))->assertRedirect(route('login'));
    }

    public function test_guests_cannot_toggle_product_status(): void
    {
        $product = $this->makeProduct();

        $this->patch(route('products.toggle-status', $product))->assertRedirect(route('login'));

        $this->assertTrue($product->fresh()->is_active);
    }

    public function test_product_listing_renders_for_admin(): void
    {
        $category = $this->makeCategory('Suits');
        $this->makeProduct(['category_id' => $category->id]);

        $this->actingAs($this->admin)
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee('Phulkari Anarkali Suit')
            ->assertSee('Suits')
            ->assertSee('Add Product');
    }

    public function test_product_search_filters_results(): void
    {
        $this->makeProduct(['name' => 'Phulkari Anarkali Suit']);
        $this->makeProduct(['name' => 'Patiala Salwar Set']);

        $this->actingAs($this->admin)
            ->get(route('products.index', ['search' => 'Patiala']))
            ->assertOk()
            ->assertSee('Patiala Salwar Set')
            ->assertDontSee('Phulkari Anarkali Suit');
    }

    public function test_category_and_status_filters_work(): void
    {
        $suits = $this->makeCategory('Suits');
        $kurtis = $this->makeCategory('Kurtis');

        $this->makeProduct(['name' => 'Rose Anarkali', 'category_id' => $suits->id]);
        $this->makeProduct(['name' => 'Mint Kurti', 'category_id' => $kurtis->id, 'is_active' => false]);

        $this->actingAs($this->admin)
            ->get(route('products.index', ['category' => $kurtis->id]))
            ->assertOk()
            ->assertSee('Mint Kurti')
            ->assertDontSee('Rose Anarkali');

        $this->actingAs($this->admin)
            ->get(route('products.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSee('Mint Kurti')
            ->assertDontSee('Rose Anarkali');
    }

    public function test_filtered_empty_state_offers_clear_filters(): void
    {
        $this->actingAs($this->admin)
            ->get(route('products.index', ['search' => 'nothing-matches']))
            ->assertOk()
            ->assertSee('No products match your filters')
            ->assertSee('Clear filters');
    }

    public function test_admin_can_create_a_product_with_auto_generated_slug(): void
    {
        $category = $this->makeCategory('Suits');

        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'Phulkari Anarkali Suit Rose',
            'slug' => '',
            'category_id' => $category->id,
            'description' => 'Hand-embroidered phulkari work.',
            'fabric' => 'Georgette',
            'care_instructions' => 'Dry clean only.',
            'is_active' => '1',
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Phulkari Anarkali Suit Rose',
            'slug' => 'phulkari-anarkali-suit-rose',
            'category_id' => $category->id,
            'is_active' => true,
            'is_featured' => true,
        ]);

        $response->assertSessionHas('success');
    }

    public function test_duplicate_names_produce_suffixed_slugs(): void
    {
        $this->makeProduct(['name' => 'Patiala Set', 'slug' => 'patiala-set']);

        $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'Patiala Set',
            'slug' => '',
        ]);

        $this->assertDatabaseHas('products', ['slug' => 'patiala-set-2']);
    }

    public function test_duplicate_custom_slug_is_rejected(): void
    {
        $this->makeProduct(['slug' => 'patiala-set']);

        $this->actingAs($this->admin)
            ->post(route('products.store'), ['name' => 'Another Product', 'slug' => 'patiala-set'])
            ->assertSessionHasErrors('slug');
    }

    public function test_invalid_product_data_is_rejected(): void
    {
        $this->actingAs($this->admin)
            ->post(route('products.store'), [
                'name' => '',
                'category_id' => 999999,
            ])
            ->assertSessionHasErrors(['name', 'category_id']);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_unchecked_boxes_deactivate_new_products(): void
    {
        // Hidden inputs send "0" when the checkboxes are left unchecked.
        $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'Hidden Kurti',
            'is_active' => '0',
            'is_featured' => '0',
        ]);

        $this->assertDatabaseHas('products', ['name' => 'Hidden Kurti', 'is_active' => false]);
    }

    public function test_admin_can_update_a_product_and_blank_slug_is_preserved(): void
    {
        $product = $this->makeProduct();

        $response = $this->actingAs($this->admin)->patch(route('products.update', $product), [
            'name' => 'Updated Kurti Name',
            'slug' => '',
            'category_id' => '',
            'description' => 'Updated description.',
            'fabric' => 'Cotton',
            'care_instructions' => 'Hand wash.',
            'is_active' => '1',
            'is_featured' => '0',
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Kurti Name',
            'slug' => 'phulkari-anarkali-suit',
            'is_active' => true,
            'is_featured' => false,
        ]);
    }

    public function test_update_rejects_slug_used_by_another_product(): void
    {
        $product = $this->makeProduct(['name' => 'One', 'slug' => 'one']);
        $this->makeProduct(['name' => 'Two', 'slug' => 'two']);

        $this->actingAs($this->admin)
            ->patch(route('products.update', $product), [
                'name' => 'One',
                'slug' => 'two',
                'is_active' => '1',
            ])
            ->assertSessionHasErrors('slug');
    }

    public function test_toggle_status_flips_activation_both_ways(): void
    {
        $product = $this->makeProduct();

        $this->actingAs($this->admin)
            ->patch(route('products.toggle-status', $product))
            ->assertSessionHas('success');

        $this->assertFalse($product->fresh()->is_active);

        $this->actingAs($this->admin)->patch(route('products.toggle-status', $product));

        $this->assertTrue($product->fresh()->is_active);
    }

    public function test_product_show_page_renders(): void
    {
        $product = $this->makeProduct();

        $this->actingAs($this->admin)
            ->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Phulkari Anarkali Suit')
            ->assertSee('Variants')
            ->assertSee('Status');
    }
}
