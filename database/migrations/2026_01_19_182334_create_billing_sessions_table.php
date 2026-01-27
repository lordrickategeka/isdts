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
        Schema::create('billing_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('network_session_id')
                  ->constrained()
                  ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Customer Context
            |--------------------------------------------------------------------------
            */
            $table->foreignId('customer_id')->nullable()->index();
            $table->string('billing_identifier')->nullable(); // voucher, phone, account

            /*
            |--------------------------------------------------------------------------
            | Billing Model
            |--------------------------------------------------------------------------
            */
            $table->enum('billing_type', [
                'voucher',
                'subscription',
                'payg',
                'free'
            ]);

            /*
            |--------------------------------------------------------------------------
            | Time & Usage
            |--------------------------------------------------------------------------
            */
            $table->timestamp('billable_start_at');
            $table->timestamp('billable_end_at')->nullable();

            $table->unsignedBigInteger('bytes_in')->default(0);
            $table->unsignedBigInteger('bytes_out')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Financials
            |--------------------------------------------------------------------------
            */
            $table->decimal('amount_charged', 12, 2)->default(0);
            $table->string('currency', 3)->default('UGX');

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'pending',
                'active',
                'suspended',
                'completed',
                'disputed'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_sessions');
    }
};
