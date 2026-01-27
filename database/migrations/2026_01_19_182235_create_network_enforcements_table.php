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
        Schema::create('network_enforcements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('network_session_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->foreignId('router_id')
                  ->constrained('router_profiles')
                  ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Enforcement Target
            |--------------------------------------------------------------------------
            */
            $table->string('mac_address')->nullable()->index();
            $table->ipAddress('ip_address')->nullable();
            $table->string('username')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Enforcement Action
            |--------------------------------------------------------------------------
            */
            $table->enum('action', [
                'disconnect',
                'limit_speed',
                'limit_time',
                'block',
                'allow'
            ]);

            /*
            |--------------------------------------------------------------------------
            | Parameters
            |--------------------------------------------------------------------------
            */
            $table->json('parameters')->nullable();
            /*
                Examples:
                { "rate_limit": "1M/1M" }
                { "duration_minutes": 30 }
            */

            /*
            |--------------------------------------------------------------------------
            | Lifecycle
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'pending',
                'applied',
                'failed',
                'expired',
                'cancelled'
            ])->default('pending');

            $table->timestamp('applied_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Diagnostics
            |--------------------------------------------------------------------------
            */
            $table->text('failure_reason')->nullable();
            $table->json('router_response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_enforcements');
    }
};
