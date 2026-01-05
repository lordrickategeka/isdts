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
        Schema::create('client_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->string('vendor_name')->nullable();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('product_name')->nullable();
            $table->foreignId('service_feasibility_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('service_type')->nullable();
            $table->string('service_code')->unique()->nullable();
            $table->string('username')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('capacity')->nullable();
            $table->string('capacity_type')->nullable();
            $table->string('vlan')->nullable();
            $table->decimal('nrc', 12, 2)->nullable();
            $table->decimal('mrc', 12, 2)->nullable();
            $table->date('contract_start_date')->nullable();
            $table->date('installation_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_services');
    }
};
