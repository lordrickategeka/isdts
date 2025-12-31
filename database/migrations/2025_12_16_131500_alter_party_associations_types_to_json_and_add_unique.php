<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // add a temporary JSON column
        if (! Schema::hasColumn('party_associations', 'association_type_tmp')) {
            Schema::table('party_associations', function (Blueprint $table) {
                $table->json('association_type_tmp')->nullable()->after('related_party_id');
            });
        }

        // backfill existing string values into JSON array
        DB::statement("UPDATE party_associations SET association_type_tmp = JSON_ARRAY(association_type) WHERE association_type IS NOT NULL");

        // drop old string column
        if (Schema::hasColumn('party_associations', 'association_type')) {
            Schema::table('party_associations', function (Blueprint $table) {
                $table->dropColumn('association_type');
            });
        }

        // rename tmp to association_type (JSON)
        DB::statement('ALTER TABLE `party_associations` CHANGE `association_type_tmp` `association_type` JSON NULL');

        // add generated hash column for indexing JSON content
        // MD5(CAST(association_type AS CHAR(10000))) will produce a stable hash of the JSON text
        DB::statement("ALTER TABLE `party_associations` ADD COLUMN `association_type_hash` VARCHAR(64) GENERATED ALWAYS AS (MD5(CAST(association_type AS CHAR(10000)))) STORED");

        // add unique index on party_id, related_party_id and hash
        Schema::table('party_associations', function (Blueprint $table) {
            $table->unique(['party_id','related_party_id','association_type_hash'], 'party_related_assoc_unique');
        });
    }

    public function down(): void
    {
        // drop unique index
        Schema::table('party_associations', function (Blueprint $table) {
            if (Schema::hasColumn('party_associations', 'association_type_hash')) {
                $table->dropUnique('party_related_assoc_unique');
            }
        });

        // drop generated hash column
        if (Schema::hasColumn('party_associations', 'association_type_hash')) {
            DB::statement('ALTER TABLE `party_associations` DROP COLUMN `association_type_hash`');
        }

        // convert JSON back to string (best-effort)
        if (! Schema::hasColumn('party_associations', 'association_type_string')) {
            Schema::table('party_associations', function (Blueprint $table) {
                $table->string('association_type_string')->nullable()->after('related_party_id');
            });
        }

        // take JSON text and store as string
        DB::statement("UPDATE party_associations SET association_type_string = CAST(association_type AS CHAR(10000))");

        // drop JSON column
        if (Schema::hasColumn('party_associations', 'association_type')) {
            Schema::table('party_associations', function (Blueprint $table) {
                $table->dropColumn('association_type');
            });
        }

        // rename back string column
        DB::statement('ALTER TABLE `party_associations` CHANGE `association_type_string` `association_type` VARCHAR(255) NULL');
    }
};
