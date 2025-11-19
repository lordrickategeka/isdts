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
        Schema::create('service_feasibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_service_id')->constrained('client_services')->onDelete('cascade');
            $table->boolean('is_feasible')->default(false);
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_feasibilities');
    }
};
