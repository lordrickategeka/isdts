@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Survey</h1>
    <form method="POST" action="{{ route('surveys.store') }}">
        @csrf
        <!-- Client Information -->
        <div class="mb-3">
            <label for="company_name" class="form-label">Company Name</label>
            <input type="text" class="form-control" id="company_name" name="company_name">
        </div>
        <div class="mb-3">
            <label for="contact_person" class="form-label">Contact Person</label>
            <input type="text" class="form-control" id="contact_person" name="contact_person">
        </div>
        <div class="mb-3">
            <label for="designation" class="form-label">Designation</label>
            <input type="text" class="form-control" id="designation" name="designation">
        </div>
        <div class="mb-3">
            <label for="nature_of_business" class="form-label">Nature of Business</label>
            <input type="text" class="form-control" id="nature_of_business" name="nature_of_business">
        </div>
        <div class="mb-3">
            <label for="physical_address" class="form-label">Physical Address</label>
            <input type="text" class="form-control" id="physical_address" name="physical_address">
        </div>
        <div class="mb-3">
            <label for="telephone_number" class="form-label">Telephone Number</label>
            <input type="text" class="form-control" id="telephone_number" name="telephone_number">
        </div>
        <div class="mb-3">
            <label for="alternative_contact" class="form-label">Alternative Contact</label>
            <input type="text" class="form-control" id="alternative_contact" name="alternative_contact">
        </div>
        <div class="mb-3">
            <label for="email_address" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email_address" name="email_address">
        </div>
        <!-- Coordinates -->
        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="text" class="form-control" id="latitude" name="latitude">
        </div>
        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="text" class="form-control" id="longitude" name="longitude">
        </div>
        <div class="mb-3">
            <label for="serving_site" class="form-label">Serving Site</label>
            <input type="text" class="form-control" id="serving_site" name="serving_site">
        </div>
        <!-- Service Details -->
        <div class="mb-3">
            <label for="microwave" class="form-label">Microwave</label>
            <input type="text" class="form-control" id="microwave" name="microwave">
        </div>
        <div class="mb-3">
            <label for="fibre" class="form-label">Fibre</label>
            <input type="text" class="form-control" id="fibre" name="fibre">
        </div>
        <div class="mb-3">
            <label for="service_type" class="form-label">Service Type</label>
            <input type="text" class="form-control" id="service_type" name="service_type">
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="text" class="form-control" id="capacity" name="capacity">
        </div>
        <div class="mb-3">
            <label for="installation_charge" class="form-label">Installation/One-time Charge</label>
            <input type="text" class="form-control" id="installation_charge" name="installation_charge">
        </div>
        <div class="mb-3">
            <label for="monthly_charge" class="form-label">Monthly Charge</label>
            <input type="text" class="form-control" id="monthly_charge" name="monthly_charge">
        </div>
        <div class="mb-3">
            <label for="router" class="form-label">Router</label>
            <input type="text" class="form-control" id="router" name="router">
        </div>
        <div class="mb-3">
            <label for="other_equipment" class="form-label">Other Equipment</label>
            <input type="text" class="form-control" id="other_equipment" name="other_equipment">
        </div>
        <div class="mb-3">
            <label for="contract_start_date" class="form-label">Contract Start Date</label>
            <input type="date" class="form-control" id="contract_start_date" name="contract_start_date">
        </div>
        <!-- Acceptance -->
        <div class="mb-3">
            <label for="acceptance" class="form-label">Acceptance</label>
            <textarea class="form-control" id="acceptance" name="acceptance"></textarea>
        </div>
        <div class="mb-3">
            <label for="client_signature" class="form-label">Client Signature</label>
            <input type="text" class="form-control" id="client_signature" name="client_signature">
        </div>
        <div class="mb-3">
            <label for="client_signature_date" class="form-label">Client Signature Date</label>
            <input type="date" class="form-control" id="client_signature_date" name="client_signature_date">
        </div>
        <div class="mb-3">
            <label for="sales_person_name" class="form-label">Sales Person Name</label>
            <input type="text" class="form-control" id="sales_person_name" name="sales_person_name">
        </div>
        <div class="mb-3">
            <label for="sales_person_signature" class="form-label">Sales Person Signature</label>
            <input type="text" class="form-control" id="sales_person_signature" name="sales_person_signature">
        </div>
        <div class="mb-3">
            <label for="sales_person_signature_date" class="form-label">Sales Person Signature Date</label>
            <input type="date" class="form-control" id="sales_person_signature_date" name="sales_person_signature_date">
        </div>
        <!-- Official Use -->
        <div class="mb-3">
            <label for="sales_manager" class="form-label">Sales Manager</label>
            <input type="text" class="form-control" id="sales_manager" name="sales_manager">
        </div>
        <div class="mb-3">
            <label for="cco" class="form-label">CCO</label>
            <input type="text" class="form-control" id="cco" name="cco">
        </div>
        <div class="mb-3">
            <label for="credit_control_manager" class="form-label">Credit Control Manager</label>
            <input type="text" class="form-control" id="credit_control_manager" name="credit_control_manager">
        </div>
        <div class="mb-3">
            <label for="cfo" class="form-label">CFO</label>
            <input type="text" class="form-control" id="cfo" name="cfo">
        </div>
        <div class="mb-3">
            <label for="business_analysis" class="form-label">Business Analysis</label>
            <input type="text" class="form-control" id="business_analysis" name="business_analysis">
        </div>
        <div class="mb-3">
            <label for="network_planning" class="form-label">Network Planning</label>
            <input type="text" class="form-control" id="network_planning" name="network_planning">
        </div>
        <div class="mb-3">
            <label for="implementation_manager" class="form-label">Implementation Manager</label>
            <input type="text" class="form-control" id="implementation_manager" name="implementation_manager">
        </div>
        <div class="mb-3">
            <label for="director" class="form-label">Director</label>
            <input type="text" class="form-control" id="director" name="director">
        </div>
        <button type="submit" class="btn btn-primary">Submit Survey</button>
    </form>
</div>
@endsection
