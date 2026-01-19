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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event'); // created, updated, deleted, viewed, login, logout, etc.
            $table->string('auditable_type'); // Model name
            $table->unsignedBigInteger('auditable_id')->nullable(); // Model ID
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Who performed the action
            $table->string('user_name')->nullable(); // Store user name for history
            $table->text('description')->nullable(); // Human-readable description
            $table->json('old_values')->nullable(); // Original values before change
            $table->json('new_values')->nullable(); // New values after change
            $table->json('properties')->nullable(); // Additional metadata
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('http_method', 10)->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['auditable_type', 'auditable_id']);
            $table->index('user_id');
            $table->index('event');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
