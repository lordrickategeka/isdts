<?php

namespace App\Database\Helpers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ForeignKeyHelper
{
    /**
     * Safely drop a foreign key if it exists on a table.
     *
     * @param string $table Table name
     * @param string|array $columns Column name(s) for the foreign key
     */
    public static function dropForeignIfExists(string $table, $columns): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }
        $columnsArr = (array) $columns;
        foreach ($columnsArr as $column) {
            $fkName = $table . '_' . $column . '_foreign';
            $hasFk = false;
            try {
                $result = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? AND CONSTRAINT_NAME = ?", [$table, $column, $fkName]);
                $hasFk = count($result) > 0;
            } catch (\Throwable $e) {}
            if ($hasFk) {
                try {
                    Schema::table($table, function (Blueprint $tableBlueprint) use ($columnsArr) {
                        $tableBlueprint->dropForeign($columnsArr);
                    });
                } catch (\Throwable $e) {}
            }
        }
    }
}
