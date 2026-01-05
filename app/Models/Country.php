<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'official_name',
        'alpha2',
        'alpha3',
        'numeric_code',
        'idd_root',
        'idd_suffixes',
        'region',
        'subregion',
        'flag_svg',
        'flag_png',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'idd_suffixes' => 'array',
    ];

    public function currencies()
    {
        return $this->belongsToMany(Currency::class, 'country_currency')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function primaryCurrency()
    {
        return $this->belongsToMany(Currency::class, 'country_currency')
            ->wherePivot('is_primary', true)
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Get the default country
    public static function getDefault()
    {
        return self::where('is_default', true)->first();
    }

    // Get full IDD code(s)
    public function getFullIddAttribute()
    {
        if (!$this->idd_root) {
            return null;
        }

        if (empty($this->idd_suffixes)) {
            return $this->idd_root;
        }

        return collect($this->idd_suffixes)->map(function ($suffix) {
            return $this->idd_root . $suffix;
        })->implode(', ');
    }
}
