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
        Schema::create('party_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable();

            $table->unsignedBigInteger('party_id');

            $table->enum('type', ['physical', 'postal', 'billing', 'work', 'temporary'])->default('physical');

            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Uganda');
            $table->string('postal_code')->nullable();

            $table->timestamps();

            $table->foreign('party_id')->references('id')->on('parties')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('party_addresses');
    }
};
