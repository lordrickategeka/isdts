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
        Schema::create('router_bridge_ports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_bridge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('router_interface_id')->constrained()->cascadeOnDelete();

            $table->boolean('disabled')->default(false);
            $table->json('attributes')->nullable();

            $table->timestamps();

            $table->unique(['router_bridge_id', 'router_interface_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('router_bridge_ports');
    }
};
