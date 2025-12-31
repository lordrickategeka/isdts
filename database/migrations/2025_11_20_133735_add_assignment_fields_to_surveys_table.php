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
        Schema::table('surveys', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_user_id')->nullable()->after('id');
            $table->unsignedBigInteger('engineer_user_id')->nullable()->after('assigned_user_id');
            $table->unsignedBigInteger('project_id')->nullable()->after('engineer_user_id');
            $table->unsignedBigInteger('client_id')->nullable()->after('project_id');

            $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('engineer_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropForeign(['assigned_user_id']);
            $table->dropForeign(['engineer_user_id']);
            $table->dropForeign(['project_id']);
            $table->dropForeign(['client_id']);
            $table->dropColumn(['assigned_user_id', 'engineer_user_id', 'project_id', 'client_id']);
        });
    }
};
