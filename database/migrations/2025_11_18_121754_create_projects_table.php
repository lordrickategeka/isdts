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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            // Classification
                $table->string('project_type');       // internal, client, R&D
                $table->string('category')->nullable(); // infra, software, consulting
                $table->string('methodology')->nullable(); // agile, waterfall, hybrid

            // Timeline
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('completion_date')->nullable();

            // Financials
            $table->decimal('estimated_budget', 15, 2)->nullable();
            $table->decimal('actual_budget', 15, 2)->nullable();
            $table->string('currency', 3)->default('UGX');
            $table->string('billing_type'); // fixed, milestone, T&M

            // Lifecycle
            $table->enum('status', [
                'draft',
                'budget_planning',
                'pending_approval',
                'approved',
                'rejected',
                'in_progress',
                'checking_availability',
                'on_hold',
                'completed',
                'cancelled'
            ])->default('draft');
            $table->string('phase'); // Initiation, Planning, Execution

            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // Risk & Controls
            $table->string('risk_level')->nullable(); // low, medium, high
            $table->text('objectives')->nullable();
            $table->text('deliverables')->nullable();

            // Client Context
            $table->string('client_reference')->nullable(); // PO / Contract
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
