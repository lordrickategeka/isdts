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
        Schema::create('client_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('agreement_number')->nullable();
            $table->enum('payment_type', ['prepaid', 'postpaid'])->nullable();
            $table->string('proof_of_payment')->nullable();
            $table->text('client_signature_data')->nullable();
            $table->timestamp('client_signed_at')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'suspended', 'archived'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_agreements');
    }
};
