@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Survey Details</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>Company Name:</strong> {{ $survey->company_name }}</p>
            <p><strong>Contact Person:</strong> {{ $survey->contact_person }}</p>
            <p><strong>Designation:</strong> {{ $survey->designation }}</p>
            <p><strong>Nature of Business:</strong> {{ $survey->nature_of_business }}</p>
            <p><strong>Physical Address:</strong> {{ $survey->physical_address }}</p>
            <p><strong>Telephone Number:</strong> {{ $survey->telephone_number }}</p>
            <p><strong>Alternative Contact:</strong> {{ $survey->alternative_contact }}</p>
            <p><strong>Email Address:</strong> {{ $survey->email_address }}</p>
            <p><strong>Latitude:</strong> {{ $survey->latitude }}</p>
            <p><strong>Longitude:</strong> {{ $survey->longitude }}</p>
            <p><strong>Serving Site:</strong> {{ $survey->serving_site }}</p>
            <p><strong>Microwave:</strong> {{ $survey->microwave }}</p>
            <p><strong>Fibre:</strong> {{ $survey->fibre }}</p>
            <p><strong>Service Type:</strong> {{ $survey->service_type }}</p>
            <p><strong>Capacity:</strong> {{ $survey->capacity }}</p>
            <p><strong>Installation/One-time Charge:</strong> {{ $survey->installation_charge }}</p>
            <p><strong>Monthly Charge:</strong> {{ $survey->monthly_charge }}</p>
            <p><strong>Router:</strong> {{ $survey->router }}</p>
            <p><strong>Other Equipment:</strong> {{ $survey->other_equipment }}</p>
            <p><strong>Contract Start Date:</strong> {{ $survey->contract_start_date }}</p>
            <p><strong>Acceptance:</strong> {{ $survey->acceptance }}</p>
            <p><strong>Client Signature:</strong> {{ $survey->client_signature }}</p>
            <p><strong>Client Signature Date:</strong> {{ $survey->client_signature_date }}</p>
            <p><strong>Sales Person Name:</strong> {{ $survey->sales_person_name }}</p>
            <p><strong>Sales Person Signature:</strong> {{ $survey->sales_person_signature }}</p>
            <p><strong>Sales Person Signature Date:</strong> {{ $survey->sales_person_signature_date }}</p>
            <p><strong>Sales Manager:</strong> {{ $survey->sales_manager }}</p>
            <p><strong>CCO:</strong> {{ $survey->cco }}</p>
            <p><strong>Credit Control Manager:</strong> {{ $survey->credit_control_manager }}</p>
            <p><strong>CFO:</strong> {{ $survey->cfo }}</p>
            <p><strong>Business Analysis:</strong> {{ $survey->business_analysis }}</p>
            <p><strong>Network Planning:</strong> {{ $survey->network_planning }}</p>
            <p><strong>Implementation Manager:</strong> {{ $survey->implementation_manager }}</p>
            <p><strong>Director:</strong> {{ $survey->director }}</p>
        </div>
    </div>
</div>
@endsection
