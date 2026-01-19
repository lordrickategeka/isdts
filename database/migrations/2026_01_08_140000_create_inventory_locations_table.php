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
        Schema::create('inventory_locations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->enum('type', ['warehouse', 'store', 'office', 'vehicle', 'site', 'other'])->default('warehouse');
            $table->text('description')->nullable();

            // Address information
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();

            // Contact information
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();

            // Capacity and status
            $table->decimal('capacity', 15, 2)->nullable()->comment('Storage capacity in cubic meters or units');
            $table->decimal('current_utilization', 5, 2)->default(0)->comment('Percentage of capacity used');
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_negative_stock')->default(false);

            // Parent location for hierarchical structure
            $table->foreignId('parent_location_id')->nullable()->constrained('inventory_locations')->onDelete('set null');

            // Manager
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'type']);
            $table->index('parent_location_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_locations');
    }
};
