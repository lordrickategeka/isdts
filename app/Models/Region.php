<?php

namespace App\Models;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use Auditable;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Hardcoded Ugandan regions
    public const CENTRAL = 'Central';
    public const WESTERN = 'Western';
    public const NORTHERN = 'Northern';
    public const EASTERN = 'Eastern';

    public static function getAllRegions()
    {
        return [
            self::CENTRAL,
            self::WESTERN,
            self::NORTHERN,
            self::EASTERN,
        ];
    }

    public static function seedRegions()
    {
        foreach (self::getAllRegions() as $regionName) {
            self::firstOrCreate(
                ['name' => $regionName],
                ['is_active' => true]
            );
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
