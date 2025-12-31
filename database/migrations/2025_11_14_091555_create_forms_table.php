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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->json('settings')->nullable();
            $table->integer('form_version')->default(1);

            $table->timestamps();
        });

        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->string('field_type');
            $table->string('name');
            $table->string('label');
            $table->string('placeholder')->nullable();
            $table->boolean('is_required')->default(false);
            $table->text('help_text')->nullable();
            $table->json('options')->nullable();
            $table->json('validation_rules')->nullable();
            $table->json('settings')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['form_id', 'order']);
            $table->index('field_type');
        });

        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->json('data');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->string('owner_type')->nullable(); // party | party_association
            $table->unsignedBigInteger('owner_id')->nullable();

            $table->enum('status', ['draft', 'submitted', 'validated', 'archived'])->default('submitted');
            $table->timestamp('submitted_at')->nullable();
            $table->string('source')->default('ui');
            $table->integer('form_version')->default(1);
            $table->timestamps();
            $table->index(['owner_type', 'owner_id']);
        });

        Schema::create('profile_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained();
            $table->string('owner_type');   // party | party_association
            $table->unsignedBigInteger('owner_id');
            $table->json('data');
            $table->integer('form_version')->default(1);
            $table->enum('status', ['active', 'superseded', 'archived']);
            $table->timestamps();

            $table->index(['owner_type', 'owner_id']);
        });

        Schema::create('form_role_mappings', function (Blueprint $table) {
            $table->id();

            // Role context (what the form applies to)
            $table->string('role');
            // examples: client, employee, vendor, subscriber

            // Optional subtype (refines meaning)
            $table->string('subtype')->nullable();
            // examples: home, corporate, sme, enterprise

            // The form that defines the profile
            $table->foreignId('form_id')->constrained()->onDelete('cascade');

            // Profile behavior
            $table->boolean('is_required')->default(true);
            $table->boolean('allows_multiple')->default(false);

            // Ordering (when multiple forms apply)
            $table->integer('order')->default(0);

            // Lifecycle
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            // Indexes
            $table->index(['role', 'subtype']);
            $table->unique(['role', 'subtype', 'form_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
        Schema::dropIfExists('form_fields');
        Schema::dropIfExists('forms');
        Schema::dropIfExists('profile_records');
        Schema::dropIfExists('form_role_mappings');
    }
};
