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
        Schema::create('project_item_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_budget_item_id')->constrained('project_budget_items')->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->integer('available_quantity')->default(0);
            $table->integer('required_quantity');
            $table->enum('availability_status', ['available', 'partial', 'unavailable', 'ordered', 'out_of_stock'])->default('unavailable');
            $table->enum('status', ['available', 'partial', 'unavailable', 'ordered', 'out_of_stock'])->default('unavailable');
            $table->integer('lead_time_days')->nullable();
            $table->decimal('unit_price', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->date('expected_availability_date')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_item_availabilities');
    }
};
