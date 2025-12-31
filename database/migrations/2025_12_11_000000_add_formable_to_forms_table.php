<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->unsignedBigInteger('formable_id')->nullable()->after('user_id');
            $table->string('formable_type')->nullable()->after('formable_id');
            $table->index(['formable_id', 'formable_type']);
        });
    }

    public function down()
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropIndex(['formable_id', 'formable_type']);
            $table->dropColumn(['formable_id', 'formable_type']);
        });
    }
};
