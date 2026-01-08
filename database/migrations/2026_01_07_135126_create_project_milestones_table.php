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
        Schema::create('project_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('milestone_code')->nullable(); // e.g., M-001, M-002
            $table->string('name');
            $table->text('description')->nullable();
            
            // Timeline
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            
            // Financial
            $table->decimal('amount', 15, 2)->nullable(); // for milestone billing
            $table->decimal('percentage', 5, 2)->nullable(); // % of total project budget
            $table->boolean('is_billable')->default(false);
            $table->date('invoiced_date')->nullable();
            
            // Status & Progress
            $table->enum('status', ['pending', 'in_progress', 'completed', 'delayed', 'cancelled', 'invoiced'])->default('pending');
            $table->integer('progress_percentage')->default(0); // 0-100
            
            // Dependencies
            $table->foreignId('depends_on_milestone_id')->nullable()->constrained('project_milestones')->nullOnDelete();
            
            // Priority & Deliverables
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('deliverables')->nullable(); // Expected deliverables for this milestone
            $table->text('notes')->nullable();
            
            // Assignment
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            
            // Approval
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('approved_date')->nullable();
            
            // Tracking
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            // Index for performance
            $table->index(['project_id', 'status']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_milestones');
    }
};
