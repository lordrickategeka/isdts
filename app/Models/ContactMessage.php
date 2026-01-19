<?php

namespace App\Models;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'tenant_id',
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'national_id',
        'passport_number',
        'date_of_birth',
        'gender',
        'whatsapp_number',
        'linkedin',
        'facebook',
        'website',
        'address',
        'city',
        'state_region',
        'postal_code',
        'country',
        'building_name',
        'floor_number',
        'landmark',
        'latitude',
        'longitude',
        'organization_name',
        'department',
        'job_title',
        'is_billing_contact',
        'billing_email',
        'billing_phone',
        'TIN_number',
        'invoice_delivery_method',
        'data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'data' => 'array',
        'is_billing_contact' => 'boolean',
        'date_of_birth' => 'date',
    ];
}
