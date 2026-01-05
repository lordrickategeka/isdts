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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();

            // ISO 4217
            $table->string('code', 3)->unique();        // USD, EUR, UGX
            $table->string('name');                     // US Dollar
            $table->string('symbol', 10)->nullable();   // $, €
            $table->unsignedSmallInteger('numeric_code')->nullable(); // 840 (max 999)

            // Formatting
            $table->unsignedTinyInteger('decimal_places')->default(2);
            $table->string('decimal_separator', 1)->default('.');
            $table->string('thousand_separator', 1)->default(',');

            // Business rules
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamp('last_synced_at')->nullable();

            // Meta
            $table->json('meta')->nullable(); // future extensions
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
