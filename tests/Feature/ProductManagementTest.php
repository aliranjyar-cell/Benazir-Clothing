<?php
namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_admin_can_create_product_with_variants_and_images(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'Chanderi Saree',
            'is_active' => '1',
            'has_variant_editor' => '1',
            'variants' => [
                ['sku' => 'CHN-001', 'size' => 'One Size', 'color' => 'Ivory', 'price' => '4999', 'stock_quantity' => '4', 'is_active' => '1'],
                ['sku' => 'CHN-002', 'size' => 'One Size', 'color' => 'Mint', 'price' => '4999', 'stock_quantity' => '0', 'is_active' => '0'],
            ],
            'images' => [
                UploadedFile::fake()->create('front.jpg', 200, 'image/jpeg'),
                UploadedFile::fake()->create('back.jpg', 200, 'image/jpeg'),
            ],
        ]);

        $response->assertRedirect(route('products.index'));

        $product = Product::firstWhere('name', 'Chanderi Saree');
        $this->assertNotNull($product);
        $this->assertDatabaseHas('product_variants', ['product_id' => $product->id, 'sku' => 'CHN-001', 'stock_quantity' => 4]);
        $this->assertDatabaseHas('product_variants', ['product_id' => $product->id, 'sku' => 'CHN-002', 'is_active' => false]);
        $this->assertSame(2, $product->images()->count());
        $this->assertSame(1, $product->images()->where('is_primary', true)->count());

        foreach ($product->images as $image) {
            Storage::disk('public')->assertExists($image->image_path);
        }
    }

    public function test_negative_price_and_missing_sku_are_rejected(): void
    {
        $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'Broken Row',
            'has_variant_editor' => '1',
            'variants' => [
                ['sku' => '', 'price' => '-5', 'stock_quantity' => '-1'],
            ],
        ])->assertSessionHasErrors(['variants.0.sku', 'variants.0.price', 'variants.0.stock_quantity']);

        $this->assertDatabaseCount('product_variants', 0);
    }

    public function test_duplicate_size_and_colour_combination_is_rejected(): void
    {
        $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'Duplicate Combo',
            'has_variant_editor' => '1',
            'variants' => [
                ['sku' => 'DUP-1', 'size' => 'M', 'color' => 'Rose', 'price' => '100', 'stock_quantity' => '1'],
                ['sku' => 'DUP-2', 'size' => 'M', 'color' => 'Rose', 'price' => '100', 'stock_quantity' => '1'],
            ],
        ])->assertSessionHasErrors('variants.1.color');

        $this->assertDatabaseCount('product_variants', 0);
    }

    public function test_variant_sku_must_be_globally_unique(): void
    {
        $other = $this->makeProduct(['name' => 'Other Product']);
        $this->makeVariant($other, ['sku' => 'TAKEN-SKU']);

        $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'New Product',
            'has_variant_editor' => '1',
            'variants' => [
                ['sku' => 'TAKEN-SKU', 'price' => '100', 'stock_quantity' => '1'],
            ],
        ])->assertSessionHasErrors('variants.0.sku');
    }

    public function test_update_syncs_variants_and_emptied_editor_removes_all(): void
    {
        $product = $this->makeProduct();
        $kept = $this->makeVariant($product, ['sku' => 'KEEP-1', 'price' => 1000, 'stock_quantity' => 3]);
        $removed = $this->makeVariant($product, ['sku' => 'GONE-1', 'price' => 2000, 'stock_quantity' => 1]);

        $this->actingAs($this->admin)->patch(route('products.update', $product), [
            'name' => $product->name,
            'has_variant_editor' => '1',
            'variants' => [
                ['id' => $kept->id, 'sku' => 'KEEP-1', 'size' => 'L', 'price' => '1500', 'stock_quantity' => '7', 'is_active' => '1'],
            ],
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('product_variants', ['id' => $kept->id, 'stock_quantity' => 7, 'size' => 'L']);
        $this->assertDatabaseMissing('product_variants', ['id' => $removed->id]);

        // An emptied editor (sentinel flag only) removes every variant.
        $this->actingAs($this->admin)->patch(route('products.update', $product), [
            'name' => $product->name,
            'has_variant_editor' => '1',
        ]);

        $this->assertSame(0, $product->variants()->count());
    }

    public function test_update_cannot_touch_another_products_variant(): void
    {
        $mine = $this->makeProduct(['name' => 'Mine']);
        $theirs = $this->makeProduct(['name' => 'Theirs']);
        $foreign = $this->makeVariant($theirs, ['sku' => 'FOREIGN-1']);

        $this->actingAs($this->admin)->patch(route('products.update', $mine), [
            'name' => $mine->name,
            'has_variant_editor' => '1',
            'variants' => [
                ['id' => $foreign->id, 'sku' => 'FOREIGN-1', 'price' => '100', 'stock_quantity' => '1'],
            ],
        ])->assertSessionHasErrors('variants.0.id');

        $this->assertDatabaseHas('product_variants', ['id' => $foreign->id, 'product_id' => $theirs->id]);
    }

    public function test_image_upload_validation_rejects_non_images(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'Bad Upload',
            'images' => [UploadedFile::fake()->create('notes.txt', 10, 'text/plain')],
        ])->assertSessionHasErrors('images.0');

        $this->assertDatabaseCount('product_images', 0);
    }

    public function test_update_can_delete_images_and_reassign_primary(): void
    {
        Storage::fake('public');

        $product = $this->makeProduct();
        $first = $this->makeImage($product, 'one.jpg', isPrimary: true);
        $second = $this->makeImage($product, 'two.jpg');

        $this->actingAs($this->admin)->patch(route('products.update', $product), [
            'name' => $product->name,
            'primary_image_id' => (string) $second->id,
            'delete_images' => [$first->id],
            'image_order' => [$second->id],
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('product_images', ['id' => $first->id]);
        Storage::disk('public')->assertMissing($first->image_path);
        Storage::disk('public')->assertExists($second->image_path);

        $this->assertTrue($second->fresh()->is_primary);
    }

    public function test_product_show_page_displays_gallery_and_variants(): void
    {
        Storage::fake('public');

        $product = $this->makeProduct();
        $this->makeVariant($product, ['sku' => 'SHOW-1', 'size' => 'M', 'color' => 'Rose']);
        $image = $this->makeImage($product, 'show.jpg', isPrimary: true);

        $this->actingAs($this->admin)
            ->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('SHOW-1')
            ->assertSee(asset('storage/'.$image->image_path), false)
            ->assertSee('Primary');
    }

    private function makeVariant(Product $product, array $overrides = []): ProductVariant
    {
        return $product->variants()->create(array_merge([
            'sku' => 'SKU-'.uniqid(),
            'size' => 'M',
            'color' => 'Rose',
            'price' => 1999,
            'stock_quantity' => 10,
        ], $overrides));
    }

    private function makeImage(Product $product, string $filename, bool $isPrimary = false): \App\Models\ProductImage
    {
        $path = 'products/'.$product->id.'/'.$filename;
        return $product->images()->create([
            'image_path' => $path,
            'alt_text' => $product->name,
            'sort_order' => $product->images()->count(),
        ]);
    }
}
