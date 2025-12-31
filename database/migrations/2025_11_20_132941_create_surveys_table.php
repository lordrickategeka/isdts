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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            // Client Information
            $table->string('company_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('designation')->nullable();
            $table->string('nature_of_business')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('telephone_number')->nullable();
            $table->string('alternative_contact')->nullable();
            $table->string('email_address')->nullable();
            // Coordinates
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('serving_site')->nullable();
            // Service Details
            $table->string('microwave')->nullable();
            $table->string('fibre')->nullable();
            $table->string('service_type')->nullable();
            $table->string('capacity')->nullable();
            $table->string('installation_charge')->nullable();
            $table->string('monthly_charge')->nullable();
            $table->string('router')->nullable();
            $table->string('other_equipment')->nullable();
            $table->date('contract_start_date')->nullable();
            // Acceptance
            $table->text('acceptance')->nullable();
            $table->string('client_signature')->nullable();
            $table->date('client_signature_date')->nullable();
            $table->string('sales_person_name')->nullable();
            $table->string('sales_person_signature')->nullable();
            $table->date('sales_person_signature_date')->nullable();
            // Official Use
            $table->string('sales_manager')->nullable();
            $table->string('cco')->nullable();
            $table->string('credit_control_manager')->nullable();
            $table->string('cfo')->nullable();
            $table->string('business_analysis')->nullable();
            $table->string('network_planning')->nullable();
            $table->string('implementation_manager')->nullable();
            $table->string('director')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
