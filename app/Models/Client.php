<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    // Category constants
    const CATEGORY_HOME = 'Home';
    const CATEGORY_SME = 'SME';
    const CATEGORY_GOVERNMENT = 'Government';
    const CATEGORY_NGO = 'NGO';
    const CATEGORY_BUSINESS = 'Business';

    const CATEGORIES = [
        self::CATEGORY_HOME,
        self::CATEGORY_SME,
        self::CATEGORY_GOVERNMENT,
        self::CATEGORY_NGO,
        self::CATEGORY_BUSINESS,
    ];

    const CORPORATE_CATEGORIES = [
        self::CATEGORY_SME,
        self::CATEGORY_GOVERNMENT,
        self::CATEGORY_NGO,
        self::CATEGORY_BUSINESS,
    ];

    protected $fillable = [
        // System
        'user_id',
        'client_code',

        // Step 1: Client Information
        'category',
        'category_type',
        'contact_person',
        'company',
        'nature_of_business',
        'tin_no',

        // Step 2: Contact Information & Location
        'phone',
        'email',
        'business_phone',
        'business_email',
        'alternative_contact',
        'designation',
        'address',
        'latitude',
        'longitude',

        // Step 4: Additional Information
        'agreement_number',
        'notes',
        'status',
        'payment_type',
        'proof_of_payment',
        'client_signature_data',
        'client_signed_at',

        // Legacy fields (set programmatically, not from UI)
        'first_name',
        'last_name',
        'city',
        'state',
        'zip_code',
        'country',
    ];

    protected $casts = [
        'client_signed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            if (empty($client->client_code)) {
                $client->client_code = self::generateClientCode();
            }
        });
    }

    /**
     * Generate unique client code in format: CLI-YYYYMMDD-XXXX
     */
    private static function generateClientCode()
    {
        $date = date('Ymd');
        $lastClient = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastClient && preg_match('/CLI-\d{8}-(\d{4})/', $lastClient->client_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        } else {
            $sequence = 1;
        }

        return 'CLI-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(ClientService::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
