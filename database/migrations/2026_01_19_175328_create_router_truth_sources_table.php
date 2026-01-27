<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // This table defines which subsystems are authoritative per router.

    public function up(): void
    {
        Schema::create('router_truth_sources', function (Blueprint $table) {
            $table->id();

            $table->foreignId('router_id')->constrained('router_profiles')->cascadeOnDelete();

            $table->enum('source', [
                'hotspot_active',
                'ppp_active',
                'dhcp_lease',
                'bridge_host',
                'firewall_connection',
                'radius_accounting'
            ]);

            $table->boolean('enabled')->default(true);

            // Polling behavior
            $table->unsignedSmallInteger('poll_interval_seconds')->default(10);
            $table->timestamp('last_polled_at')->nullable();

            // Health
            $table->unsignedSmallInteger('failures')->default(0);
            $table->timestamp('disabled_at')->nullable();

            $table->timestamps();

            $table->unique(['router_id', 'source']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('router_truth_sources');
    }
};
