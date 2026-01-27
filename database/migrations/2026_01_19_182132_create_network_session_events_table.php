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
        Schema::create('network_session_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('network_session_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('router_id')
                  ->constrained('router_profiles')
                  ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Event Classification
            |--------------------------------------------------------------------------
            */
            $table->enum('event_type', [
                'session_created',
                'session_updated',
                'session_authenticated',
                'session_activity_seen',
                'session_idle',
                'session_ended',
                'confidence_increased',
                'confidence_decreased',
                'enforcement_applied',
                'enforcement_removed'
            ]);

            /*
            |--------------------------------------------------------------------------
            | Source of Truth
            |--------------------------------------------------------------------------
            */
            $table->string('source'); // hotspot_active, dhcp_lease, firewall, system

            /*
            |--------------------------------------------------------------------------
            | State Change
            |--------------------------------------------------------------------------
            */
            $table->json('before')->nullable();
            $table->json('after')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Context & Explanation
            |--------------------------------------------------------------------------
            */
            $table->text('reason')->nullable();
            $table->json('metadata')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timing
            |--------------------------------------------------------------------------
            */
            $table->timestamp('occurred_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_session_events');
    }
};
