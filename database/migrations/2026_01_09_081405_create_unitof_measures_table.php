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
        Schema::create('unit_of_measures', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // m, kg, pcs, etc.
            $table->string('name', 100); // Meter, Kilogram, Pieces
            $table->string('symbol', 20)->nullable(); // m, kg, pcs
            $table->text('description')->nullable();

            // Category grouping
            $table->enum('category', [
                'length',      // meters, feet, inches, km, miles
                'weight',      // kg, g, lb, oz, ton
                'volume',      // liters, ml, gallons, cubic meters
                'area',        // sq meters, sq feet, acres
                'quantity',    // units, pieces, packs, boxes
                'time',        // hours, days, weeks, months
                'temperature', // celsius, fahrenheit
                'other'
            ])->default('quantity');

            // Base unit for conversions
            $table->foreignId('base_unit_id')->nullable()->constrained('unit_of_measures')->onDelete('set null');
            $table->decimal('conversion_factor', 15, 6)->default(1); // Factor to convert to base unit
            $table->string('conversion_formula')->nullable(); // For complex conversions

            // Display and formatting
            $table->integer('decimal_places')->default(2);
            $table->boolean('allow_fractional')->default(true);

            // Status and usage
            $table->boolean('is_active')->default(true);
            $table->boolean('is_base_unit')->default(false); // Is this the base unit for its category?
            $table->boolean('is_default')->default(false); // Default for its category
            $table->integer('sort_order')->default(0);

            // Additional info
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'is_active']);
            $table->index('is_base_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_of_measures');
    }
};
