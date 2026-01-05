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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            // ISO 3166
            $table->string('name');
            $table->string('official_name')->nullable();
            $table->string('alpha2', 2)->unique();   // UG
            $table->string('alpha3', 3)->unique();   // UGA
            $table->string('numeric_code', 3)->nullable();

            // Geography
            $table->string('region')->nullable();    // Africa
            $table->string('subregion')->nullable(); // Eastern Africa

            // Optional
            $table->string('flag_svg')->nullable();
            $table->string('flag_png')->nullable();

            $table->string('idd_root')->nullable(); // +2
            $table->json('idd_suffixes')->nullable(); // ["56"]

            $table->boolean('is_default')->default(false);


            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
