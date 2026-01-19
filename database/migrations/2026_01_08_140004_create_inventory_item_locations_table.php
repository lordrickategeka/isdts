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
        Schema::create('inventory_item_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('inventory_locations')->onDelete('cascade');

            // Stock levels at this location
            $table->decimal('quantity_on_hand', 15, 2)->default(0);
            $table->decimal('quantity_reserved', 15, 2)->default(0);
            $table->decimal('quantity_available', 15, 2)->storedAs('quantity_on_hand - quantity_reserved');

            // Location-specific settings
            $table->decimal('reorder_level', 15, 2)->nullable();
            $table->decimal('max_stock_level', 15, 2)->nullable();
            $table->string('bin_location')->nullable();
            $table->string('aisle')->nullable();
            $table->string('shelf')->nullable();

            // Last activity
            $table->timestamp('last_stock_take_date')->nullable();
            $table->timestamp('last_movement_date')->nullable();

            $table->timestamps();

            $table->unique(['inventory_item_id', 'location_id']);
            $table->index('quantity_on_hand');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_item_locations');
    }
};
