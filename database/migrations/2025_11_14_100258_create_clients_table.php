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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_code')->unique()->nullable();

            // Classification
            $table->string('category')->nullable(); // individual, company, government

            // Business / contact
            $table->string('customer_name')->unique()->nullable();
            $table->string('contact_person')->nullable();
            $table->string('nature_of_business')->nullable();
            $table->string('tin_no')->nullable();

            // Phones & emails
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('business_phone')->nullable();
            $table->string('business_email')->nullable();
            $table->string('alternative_contact')->nullable();

            // Address / location
            $table->text('address')->nullable();
            $table->string('region')->nullable();
            $table->string('district')->nullable();
            $table->string('country')->nullable()->default('Uganda');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            // Status & ownership
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
