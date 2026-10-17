<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('supplier_name');
            $table->string('supplier_email')->nullable();
            $table->date('expected_at')->nullable();
            $table->string('status', 30)->default('ordered');
            $table->text('notes')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('sku');
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->unsignedInteger('quantity_ordered');
            $table->unsignedInteger('quantity_received')->default(0);
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
    }
};
