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
        // Drop all foreign keys referencing projects before dropping the table, but only if the table exists
        $tables = [
            'client_services',
            'project_budget_items',
            'project_item_availabilities',
            'project_approvals',
            'project_persons',
            'survey_tickets',
            'surveys',
            'projects', // for parent_id
        ];
        $foreignKeys = [
            'client_services' => ['project_id'],
            'project_budget_items' => ['project_id'],
            'project_item_availabilities' => ['project_id'],
            'project_approvals' => ['project_id'],
            'project_persons' => ['project_id'],
            'survey_tickets' => ['project_id'],
            'surveys' => ['project_id'],
            'projects' => ['parent_id'],
        ];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                foreach ($foreignKeys[$table] as $fk) {
                    // Check if the foreign key exists before dropping
                    $fkName = $table . '_' . $fk . '_foreign';
                    $hasFk = false;
                    try {
                        $result = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? AND CONSTRAINT_NAME = ?", [$table, $fk, $fkName]);
                        $hasFk = count($result) > 0;
                    } catch (\Throwable $e) {}
                    if ($hasFk) {
                        try {
                            Schema::table($table, function (Blueprint $tableBlueprint) use ($fk) {
                                $tableBlueprint->dropForeign([$fk]);
                            });
                        } catch (\Throwable $e) {}
                    }
                }
            }
        }
        // Now drop the projects table
        Schema::dropIfExists('projects');
    }
};
