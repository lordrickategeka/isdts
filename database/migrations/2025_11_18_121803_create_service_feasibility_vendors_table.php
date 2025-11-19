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
        Schema::create('service_feasibility_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_feasibility_id')->constrained('service_feasibilities')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('vendor_service_id')->nullable()->constrained('vendor_services')->onDelete('set null');
            $table->decimal('nrc_cost', 12, 2)->default(0); // Non-Recurring Cost
            $table->decimal('mrc_cost', 12, 2)->default(0); // Monthly Recurring Cost
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_feasibility_vendors');
    }
};
