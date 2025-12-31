<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop any existing foreign key on survey_id (use information_schema to be safe)
        $dbName = DB::getDatabaseName();
        $existingFk = DB::selectOne(
            'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL',
            [$dbName, 'survey_engineer', 'survey_id']
        );
        if ($existingFk && isset($existingFk->CONSTRAINT_NAME)) {
            DB::statement(sprintf('ALTER TABLE `survey_engineer` DROP FOREIGN KEY `%s`', $existingFk->CONSTRAINT_NAME));
        }

        // Remove any rows in survey_engineer that reference non-existing survey_tickets
        DB::table('survey_engineer as se')
            ->leftJoin('survey_tickets as st', 'se.survey_id', '=', 'st.id')
            ->whereNull('st.id')
            ->delete();

        Schema::table('survey_engineer', function (Blueprint $table) {
            $table->foreign('survey_id')
                ->references('id')
                ->on('survey_tickets')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop any existing foreign key on survey_id before reverting
        $dbName = DB::getDatabaseName();
        $existingFkDown = DB::selectOne(
            'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL',
            [$dbName, 'survey_engineer', 'survey_id']
        );
        if ($existingFkDown && isset($existingFkDown->CONSTRAINT_NAME)) {
            DB::statement(sprintf('ALTER TABLE `survey_engineer` DROP FOREIGN KEY `%s`', $existingFkDown->CONSTRAINT_NAME));
        }

        // When reverting, remove rows that reference non-existing surveys
        DB::table('survey_engineer as se')
            ->leftJoin('surveys as s', 'se.survey_id', '=', 's.id')
            ->whereNull('s.id')
            ->delete();

        Schema::table('survey_engineer', function (Blueprint $table) {
            $table->foreign('survey_id')
                ->references('id')
                ->on('surveys')
                ->onDelete('cascade');
        });
    }
};
