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
        Schema::create('project_persons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            
            // Role type: client, project_manager, sponsor, staff
            $table->enum('role_type', ['client', 'project_manager', 'sponsor', 'staff']);
            
            // Person references - can be from clients or users table
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            
            // Additional metadata
            $table->string('responsibility')->nullable(); // Specific role/responsibility description
            $table->date('assigned_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            // Ensure at least one of client_id or user_id is set
            // Unique constraint to prevent duplicate assignments
            $table->unique(['project_id', 'role_type', 'client_id', 'user_id'], 'unique_project_person_assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_persons');
    }
};
