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
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('milestone_id')->nullable()->constrained('project_milestones')->cascadeOnDelete();
            
            $table->string('task_code')->nullable(); // e.g., T-001, T-002
            $table->string('name');
            $table->text('description')->nullable();
            
            // Timeline
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->integer('actual_hours')->nullable();
            
            // Status & Progress
            $table->enum('status', ['todo', 'in_progress', 'review', 'completed', 'blocked', 'cancelled'])->default('todo');
            $table->integer('progress_percentage')->default(0); // 0-100
            
            // Priority & Type
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->string('task_type')->nullable(); // development, testing, documentation, etc.
            
            // Dependencies
            $table->foreignId('depends_on_task_id')->nullable()->constrained('project_tasks')->nullOnDelete();
            
            // Assignment
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('assigned_date')->nullable();
            
            // Additional Info
            $table->text('acceptance_criteria')->nullable();
            $table->text('notes')->nullable();
            $table->string('tags')->nullable(); // JSON or comma-separated tags
            
            // Tracking
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['project_id', 'status']);
            $table->index(['milestone_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_tasks');
    }
};
