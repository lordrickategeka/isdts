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
        Schema::create('user_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('signature_code')->unique(); // BCC_HD8U9X format
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('agreement_number');
            $table->string('position'); // Sales Manager, CCO, etc.
            $table->string('signature_data')->nullable(); // Stored signature image/data
            $table->timestamp('signed_at')->nullable();
            $table->enum('status', ['pending', 'signed', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'client_id']);
            $table->index('agreement_number');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_signatures');
    }
};
