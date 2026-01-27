<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // table stores exactly what MikroTik reports for various data sources.

    public function up(): void
    {
        Schema::create('router_snapshots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('router_id')
                  ->constrained('router_profiles')
                  ->cascadeOnDelete();

            $table->enum('source', [
                'hotspot_active',
                'ppp_active',
                'dhcp_lease',
                'bridge_host',
                'firewall_connection',
                'radius_accounting'
            ]);

            
            $table->json('payload');

            $table->timestamp('captured_at');

            $table->unsignedSmallInteger('record_count')->nullable(); // rows returned
            $table->unsignedInteger('duration_ms')->nullable();       // poll time
            $table->boolean('success')->default(true);
            $table->text('error_message')->nullable();

            $table->index(['router_id', 'source', 'captured_at']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('router_snapshots');
    }
};
