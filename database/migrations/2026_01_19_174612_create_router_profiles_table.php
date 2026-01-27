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
        Schema::create('router_profiles', function (Blueprint $table) {
            $table->id();

            // Ownership & Context
            $table->foreignId('tenant_id')->nullable()->index(); // ISP / organization
            $table->string('name');                              // Friendly name
            $table->string('site')->nullable();                  // Branch / location
            $table->enum('ownership', ['managed', 'customer_owned'])
                ->default('managed');

            // Network Identity (stable identifiers)
            $table->string('identity')->nullable();        // /system identity
            $table->string('serial_number')->nullable();   // hardware serial
            $table->string('board_name')->nullable();      // model / board

            // Connectivity & Access
            $table->string('management_ip');                // API / mgmt IP
            $table->unsignedInteger('api_port')->default(8728);
            $table->enum('connection_method', [
                'api',
                'ssh',
                'radius'
            ])->default('api');

            $table->boolean('use_tls')->default(false);     // API-SSL (8729)
            $table->unsignedSmallInteger('timeout_seconds')->default(5);

            // Authentication (credentials or indirection)
            $table->string('username')->nullable();
            $table->text('password')->nullable();           // encrypted at rest
            $table->string('credential_ref')->nullable();   // vault / secret manager
            $table->boolean('rotate_credentials')->default(false);
            $table->timestamp('credentials_rotated_at')->nullable();

            $table->enum('role', [
                'core',
                'distribution',
                'access',
                'cpe',
                'test'
            ])->default('access'); //Router Role & Network Position
            $table->string('uplink_type')->nullable(); // fiber, lte, wireless, etc

            $table->json('capabilities')->nullable();
            /*
                Example:
                {
                    "hotspot": true,
                    "pppoe": true,
                    "dhcp": true,
                    "queues": true,
                    "radius": false,
                    "api_ssl": false
                }
            */

            $table->boolean('is_active')->default(true);
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('last_polled_at')->nullable();

            $table->unsignedSmallInteger('poll_failures')->default(0);
            $table->timestamp('disabled_at')->nullable();

            $table->enum('os_type', ['routeros'])->default('routeros');
            $table->string('os_version')->nullable();

            $table->json('metadata')->nullable(); // SIM ICCID, antenna height, notes
            $table->timestamps();

            $table->unique(['serial_number', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('router_profiles');
    }
};
