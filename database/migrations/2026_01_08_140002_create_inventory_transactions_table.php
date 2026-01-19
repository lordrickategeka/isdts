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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number', 50)->unique();
            $table->enum('transaction_type', [
                'receipt',          // Incoming stock (purchase, return, transfer-in)
                'issue',            // Outgoing stock (sale, consumption, transfer-out)
                'transfer',         // Location to location transfer
                'adjustment',       // Stock adjustment (increase/decrease)
                'return',           // Return to vendor
                'damage',           // Damaged/write-off
                'production',       // Manufacturing consumption/output
                'count'             // Physical count adjustment
            ]);

            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('inventory_locations')->onDelete('cascade');
            $table->foreignId('to_location_id')->nullable()->constrained('inventory_locations')->onDelete('set null');

            // Quantity and cost
            $table->decimal('quantity', 15, 2);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);

            // Balance after transaction
            $table->decimal('balance_before', 15, 2)->nullable();
            $table->decimal('balance_after', 15, 2)->nullable();

            // Reference information
            $table->string('reference_type')->nullable(); // project, client_feasibility, vendor, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();

            // Batch and serial tracking
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('expiry_date')->nullable();

            // User and approval
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('completed');

            $table->timestamps();

            $table->index(['transaction_type', 'created_at']);
            $table->index(['inventory_item_id', 'location_id']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('transaction_number');
            $table->index('batch_number');
            $table->index('serial_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
