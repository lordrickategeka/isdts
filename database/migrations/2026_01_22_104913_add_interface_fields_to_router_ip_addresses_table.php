<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('router_ip_addresses', function (Blueprint $table) {
            $table->string('interface_name')->nullable()->index();
            $table->foreignId('router_interface_id')
                ->nullable()
                ->constrained('router_interfaces')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('router_ip_addresses', function (Blueprint $table) {
            $table->dropForeign(['router_interface_id']);
            $table->dropColumn('router_interface_id');
            $table->dropIndex(['interface_name']);
            $table->dropColumn('interface_name');
        });
    }
};
