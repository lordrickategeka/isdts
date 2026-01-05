<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    // Category values used in the `category` column
    const CATEGORY_HOME = 'home';
    const CATEGORY_COMPANY = 'company';

    const CATEGORIES = [
        self::CATEGORY_HOME,
        self::CATEGORY_COMPANY,
    ];

    // Status values
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'client_code',

        // Classification
        'category',

        // Business / contact
        'customer_name',
        'contact_person',
        'nature_of_business',
        'tin_no',

        // Phones & emails
        'phone',
        'email',

        'business_phone',
        'business_email',
        'alternative_contact',

        // Address / location
        'address',
        'region',
        'district',
        'country',
        'latitude',
        'longitude',

        // ownership
        'created_by',
    ];

    protected $casts = [
        // Add casts as needed
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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function services(): HasMany
    {
        return $this->hasMany(ClientService::class);
    }

    public function clientServices(): HasMany
    {
        return $this->hasMany(ClientService::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(UserSignature::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the client's display name (company or contact person)
     */
    public function getNameAttribute()
    {
        // For corporate clients, use company name
        if (in_array($this->category, self::CATEGORIES) && !empty($this->company)) {
            return $this->company;
        }

        // For individual clients or if company is not set, use contact person
        if (!empty($this->contact_person)) {
            return $this->contact_person;
        }

        // Fallback to full name if available
        if (!empty($this->first_name) || !empty($this->last_name)) {
            return trim("{$this->first_name} {$this->last_name}");
        }

        // Last resort: use email
        return $this->email;
    }
}
