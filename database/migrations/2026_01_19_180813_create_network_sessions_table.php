<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // This table represents a living network session, continuously updated as new truths arrive from snapshots.

    public function up(): void
    {
        Schema::create('network_sessions', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Ownership
            |--------------------------------------------------------------------------
            */
            $table->foreignId('router_id')
                  ->constrained('router_profiles')
                  ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Identity Anchors (correlation keys)
            |--------------------------------------------------------------------------
            */
            $table->string('mac_address')->nullable()->index();
            $table->string('username')->nullable()->index();
            $table->ipAddress('ip_address')->nullable()->index();

            /*
            |--------------------------------------------------------------------------
            | Session Classification
            |--------------------------------------------------------------------------
            */
            $table->enum('access_type', [
                'hotspot',
                'ppp',
                'dhcp_only',
                'unknown'
            ])->default('unknown');

            $table->boolean('authenticated')->default(false);
            $table->boolean('active')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Usage Counters (best-effort)
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('bytes_in')->default(0);
            $table->unsignedBigInteger('bytes_out')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Session Timing
            |--------------------------------------------------------------------------
            */
            $table->timestamp('started_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Confidence & Provenance
            |--------------------------------------------------------------------------
            */
            $table->unsignedTinyInteger('confidence_score')->default(0);

            $table->json('sources')->nullable();
            /*
                Example:
                {
                    "dhcp_lease": true,
                    "bridge_host": true,
                    "hotspot_active": true,
                    "firewall_connection": true
                }
            */

            /*
            |--------------------------------------------------------------------------
            | Diagnostics
            |--------------------------------------------------------------------------
            */
            $table->json('attributes')->nullable(); // hostname, interface, vlan, etc

            /*
            |--------------------------------------------------------------------------
            | Indexing & Lifecycle
            |--------------------------------------------------------------------------
            */
            $table->timestamps();

            $table->index(['router_id', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_sessions');
    }
};
