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
        Schema::create('project_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();

            $table->string('service_type'); // internet, fiber, vpn, cloud, etc
            $table->string('transmission')->nullable();
            $table->string('vlan')->nullable();
            $table->string('capacity')->nullable();

            $table->decimal('nrc', 15, 2)->nullable();
            $table->decimal('mrc', 15, 2)->nullable();

            $table->date('installation_date')->nullable();
            $table->foreignId('installation_engineer_id')->nullable()
                  ->constrained('users')->nullOnDelete();

            $table->enum('status', [
                'pending',
                'scheduled',
                'installed',
                'active',
                'suspended',
                'terminated'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_services');
    }
};
