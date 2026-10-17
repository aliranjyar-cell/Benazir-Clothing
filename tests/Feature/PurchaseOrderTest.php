<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_purchase_order_for_product_variants(): void
    {
        $this->actingAs($this->makeAdmin());
        $variant = $this->makeVariant();

        $this->get(route('admin.purchase-orders.create'))
            ->assertOk()
            ->assertSee('Variants and quantities');

        $response = $this->post(route('admin.purchase-orders.store'), [
            'supplier_name' => 'Lahore Textiles',
            'supplier_email' => 'orders@textiles.example',
            'expected_at' => now()->addDays(5)->toDateString(),
            'items' => [
                ['variant_id' => $variant->id, 'quantity_ordered' => 8, 'unit_cost' => '750.00'],
            ],
        ]);

        $order = PurchaseOrder::query()->firstOrFail();

        $response->assertRedirect(route('admin.purchase-orders.show', $order));
        $this->assertSame(PurchaseOrder::STATUS_ORDERED, $order->status);
        $this->assertSame('Lahore Textiles', $order->supplier_name);
        $this->assertSame($this->admin->id, $order->admin_id);
        $this->assertDatabaseHas('purchase_order_items', [
            'purchase_order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'sku' => $variant->sku,
            'quantity_ordered' => 8,
            'quantity_received' => 0,
            'unit_cost' => '750.00',
        ]);

        $this->get(route('admin.purchase-orders.index'))
            ->assertOk()
            ->assertSee($order->reference)
            ->assertSee('Lahore Textiles');

        $this->get(route('admin.purchase-orders.show', $order))
            ->assertOk()
            ->assertSee($order->reference)
            ->assertSee('Lahore Textiles')
            ->assertSee($variant->sku);
    }

    public function test_purchase_order_receipts_add_stock_and_update_partial_status(): void
    {
        $this->actingAs($this->makeAdmin());
        $variant = $this->makeVariant(['stock_quantity' => 2]);
        $order = $this->createPurchaseOrder($variant, 5);
        $item = $order->items()->firstOrFail();

        $this->post(route('admin.purchase-orders.receive', $order), [
            'items' => [$item->id => ['quantity' => 2]],
        ])->assertRedirect(route('admin.purchase-orders.show', $order));

        $this->assertSame(4, $variant->fresh()->stock_quantity);
        $this->assertSame(2, $item->fresh()->quantity_received);
        $this->assertSame(PurchaseOrder::STATUS_PARTIALLY_RECEIVED, $order->fresh()->status);
        $this->assertDatabaseHas('inventory_movements', [
            'product_variant_id' => $variant->id,
            'admin_id' => $this->admin->id,
            'type' => 'restock',
            'quantity_change' => 2,
            'reference' => $order->reference,
        ]);

        $this->post(route('admin.purchase-orders.receive', $order), [
            'items' => [$item->id => ['quantity' => 3]],
        ])->assertRedirect(route('admin.purchase-orders.show', $order));

        $this->assertSame(7, $variant->fresh()->stock_quantity);
        $this->assertSame(5, $item->fresh()->quantity_received);
        $this->assertSame(PurchaseOrder::STATUS_RECEIVED, $order->fresh()->status);
        $this->assertNotNull($order->fresh()->received_at);
        $this->assertSame(2, InventoryMovement::where('reference', $order->reference)->count());
    }

    public function test_purchase_order_cannot_receive_more_than_the_remaining_quantity(): void
    {
        $this->actingAs($this->makeAdmin());
        $variant = $this->makeVariant(['stock_quantity' => 2]);
        $order = $this->createPurchaseOrder($variant, 5);
        $item = $order->items()->firstOrFail();

        $this->from(route('admin.purchase-orders.show', $order))
            ->post(route('admin.purchase-orders.receive', $order), [
                'items' => [$item->id => ['quantity' => 6]],
            ])
            ->assertSessionHasErrors("items.{$item->id}.quantity");

        $this->assertSame(2, $variant->fresh()->stock_quantity);
        $this->assertSame(0, $item->fresh()->quantity_received);
        $this->assertDatabaseCount('inventory_movements', 0);
    }

    private Admin $admin;

    private function makeAdmin(): Admin
    {
        return $this->admin = Admin::create([
            'first_name' => 'Simran',
            'last_name' => 'Kaur',
            'username' => 'simran',
            'email' => 'simran@example.com',
            'password' => 'password',
            'role' => 'superadmin',
            'status' => 'active',
        ]);
    }

    private function makeVariant(array $overrides = []): ProductVariant
    {
        $product = Product::create([
            'name' => 'Punjabi Suit',
            'slug' => 'punjabi-suit-' . Str::lower(Str::random(6)),
            'is_active' => true,
        ]);

        return ProductVariant::create(array_merge([
            'product_id' => $product->id,
            'sku' => 'BEN-' . Str::upper(Str::random(8)),
            'size' => 'M',
            'color' => 'Burgundy',
            'price' => '1299.95',
            'stock_quantity' => 5,
            'is_active' => true,
        ], $overrides));
    }

    private function createPurchaseOrder(ProductVariant $variant, int $quantity): PurchaseOrder
    {
        $this->post(route('admin.purchase-orders.store'), [
            'supplier_name' => 'Lahore Textiles',
            'items' => [
                ['variant_id' => $variant->id, 'quantity_ordered' => $quantity],
            ],
        ])->assertRedirect();

        return PurchaseOrder::query()->firstOrFail();
    }
}
