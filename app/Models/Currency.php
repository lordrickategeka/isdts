<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'numeric_code',
        'decimal_places',
        'decimal_separator',
        'thousand_separator',
        'is_active',
        'is_default',
        'meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'meta' => 'array',
    ];

    // Optionally, you can define constants for the supported currencies
    public const UGX = 'UGX';
    public const USD = 'USD';

    // Static method to get supported currencies
    public static function supportedCurrencies()
    {
        return [
            [
                'code' => self::UGX,
                'name' => 'Ugandan Shilling',
                'symbol' => 'USh',
            ],
            [
                'code' => self::USD,
                'name' => 'US Dollar',
                'symbol' => '$',
            ],
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Relationships
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_currency')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    // Get the default currency
    public static function getDefault()
    {
        return self::where('is_default', true)->first();
    }

    // Get all active currencies (cached)
    public static function getAllActive()
    {
        return Cache::rememberForever('currencies', fn () =>
            self::where('is_active', true)->orderBy('code')->get()
        );
    }

    // Format amount with currency
    public function format($amount)
    {
        return $this->symbol . ' ' . number_format(
            $amount,
            $this->decimal_places,
            $this->decimal_separator,
            $this->thousand_separator
        );
    }
}
