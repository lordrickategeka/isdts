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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable()->default('Uganda');
            $table->string('company')->nullable();
            $table->string('category')->nullable();
            $table->string('category_type')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending_approval', 'approved'])->default('active');
            $table->enum('payment_type', ['prepaid', 'postpaid'])->default('postpaid');
            $table->string('proof_of_payment')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('nature_of_business')->nullable();
            $table->string('designation')->nullable();
            $table->string('alternative_contact')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('agreement_number')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_email')->nullable();
            $table->string('client_signature_data')->nullable();
            $table->timestamp('client_signed_at')->nullable();

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
