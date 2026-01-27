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
        Schema::create('router_ip_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->constrained()->cascadeOnDelete();
            $table->foreignId('router_interface_id')->constrained()->cascadeOnDelete();

            $table->string('address');   // 192.168.99.1/24
            $table->string('network');   // 192.168.99.0
            $table->string('vrf')->nullable();

            $table->json('attributes')->nullable();

            $table->string('interface_name')->nullable()->index();
            $table->foreignId('router_interface_id')
                ->nullable()
                ->constrained('router_interfaces')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('router_ip_addresses');
    }
};
