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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_number', 50)->unique();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('inventory_locations')->onDelete('cascade');

            // Adjustment details
            $table->enum('adjustment_type', [
                'physical_count',   // Physical inventory count
                'damage',           // Damaged goods
                'loss',             // Lost/stolen items
                'found',            // Found items (surplus)
                'expiry',           // Expired items
                'quality',          // Quality issues
                'correction',       // Data correction
                'write_off',        // Write-off
                'write_on'          // Write-on (adding stock)
            ]);

            $table->decimal('quantity_before', 15, 2);
            $table->decimal('quantity_counted', 15, 2)->nullable();
            $table->decimal('quantity_adjusted', 15, 2);
            $table->decimal('quantity_after', 15, 2);

            // Cost impact
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('total_cost_impact', 15, 2)->default(0);

            // Reason and documentation
            $table->enum('reason', [
                'physical_count',
                'damage',
                'theft',
                'obsolete',
                'expired',
                'data_error',
                'quality_issue',
                'other'
            ]);
            $table->text('notes')->nullable();
            $table->string('document_reference')->nullable();

            // Approval workflow
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected'])->default('draft');

            // Batch and serial tracking
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();

            $table->timestamps();

            $table->index(['adjustment_type', 'status']);
            $table->index(['inventory_item_id', 'location_id']);
            $table->index('adjustment_number');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
