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
        Schema::create('project_item_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_budget_item_id')->constrained('project_budget_items')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->integer('available_quantity')->default(0);
            $table->integer('required_quantity');
            $table->enum('availability_status', ['available', 'partial', 'unavailable', 'ordered'])->default('unavailable');
            $table->text('notes')->nullable();
            $table->date('expected_availability_date')->nullable();
            $table->foreignId('checked_by')->constrained('users')->cascadeOnDelete(); // Stores team member
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_item_availability');
    }
};
