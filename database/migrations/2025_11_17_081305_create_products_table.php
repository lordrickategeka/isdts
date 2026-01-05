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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('vendor_service_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();

            // Optional pricing fields (nullable for products without pricing)
            $table->decimal('price', 10, 2)->nullable();
            $table->string('capacity')->nullable();
            $table->decimal('installation_charge', 10, 2)->nullable();
            $table->decimal('monthly_charge', 10, 2)->nullable();
            $table->string('billing_cycle')->nullable()->comment('monthly, quarterly, annually');

            $table->json('specifications')->nullable()->comment('Technical specs, features, etc.');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
