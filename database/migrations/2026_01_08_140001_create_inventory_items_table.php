<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 100)->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('name');
            $table->text('description')->nullable();

            // Category and type
            $table->enum('type', ['product', 'material', 'equipment', 'consumable', 'spare_part', 'other'])->default('material');
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();

            // Unit of measure
            $table->string('unit_of_measure', 50)->default('units'); // units, kg, meters, liters, etc.
            $table->decimal('unit_weight', 10, 3)->nullable();
            $table->decimal('unit_volume', 10, 3)->nullable();

            // Stock tracking
            $table->decimal('quantity_on_hand', 15, 2)->default(0);
            $table->decimal('quantity_reserved', 15, 2)->default(0);
            $table->decimal('quantity_available', 15, 2)->storedAs('quantity_on_hand - quantity_reserved');
            $table->decimal('reorder_level', 15, 2)->default(0);
            $table->decimal('reorder_quantity', 15, 2)->default(0);
            $table->decimal('max_stock_level', 15, 2)->nullable();

            // Costing
            $table->enum('costing_method', ['FIFO', 'LIFO', 'Average', 'Standard'])->default('Average');
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('average_cost', 15, 2)->default(0);
            $table->decimal('standard_cost', 15, 2)->nullable();
            $table->decimal('last_purchase_cost', 15, 2)->nullable();
            $table->date('last_purchase_date')->nullable();

            // Tracking options
            $table->boolean('track_serial_numbers')->default(false);
            $table->boolean('track_batches')->default(false);
            $table->boolean('track_expiry')->default(false);
            $table->integer('shelf_life_days')->nullable();

            // Relationships
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('preferred_vendor_id')->nullable()->constrained('vendors')->onDelete('set null');

            // Status and flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_stockable')->default(true);
            $table->boolean('is_purchasable')->default(true);
            $table->boolean('is_sellable')->default(true);

            // Additional info
            $table->string('storage_location')->nullable();
            $table->text('notes')->nullable();
            $table->json('custom_attributes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'is_active']);
            $table->index(['category', 'subcategory']);
            $table->index('quantity_on_hand');
            $table->index('reorder_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
