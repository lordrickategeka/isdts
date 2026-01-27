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
        Schema::create('router_interfaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->constrained('router_profiles')->cascadeOnDelete();

            $table->string('name');                 // ether1, wlan1, bridge
            $table->string('type')->nullable();     // ethernet, bridge, vlan, wireless
            $table->string('mac_address')->nullable();
            $table->boolean('running')->default(false);
            $table->boolean('disabled')->default(false);

            $table->json('attributes')->nullable(); // mtu, speed, comments, etc

            $table->timestamps();

            $table->unique(['router_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('router_interfaces');
    }
};
