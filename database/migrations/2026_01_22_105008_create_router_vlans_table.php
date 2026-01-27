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
        Schema::create('router_vlans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->constrained('router_profiles')->cascadeOnDelete();

            $table->string('name')->nullable();
            $table->unsignedSmallInteger('vlan_id');
            $table->foreignId('parent_interface_id')
                ->nullable()
                ->constrained('router_interfaces')
                ->nullOnDelete();

            $table->json('attributes')->nullable();

            $table->timestamps();

            $table->unique(['router_id', 'vlan_id', 'parent_interface_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('router_vlans');
    }
};
