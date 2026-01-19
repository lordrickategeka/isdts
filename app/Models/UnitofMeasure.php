<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitofMeasure extends Model
{
    use Auditable, SoftDeletes;

    protected $table = 'unit_of_measures';

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'description',
        'category',
        'base_unit_id',
        'conversion_factor',
        'conversion_formula',
        'decimal_places',
        'allow_fractional',
        'is_active',
        'is_base_unit',
        'is_default',
        'sort_order',
        'notes',
    ];

    protected $casts = [
        'conversion_factor' => 'decimal:6',
        'decimal_places' => 'integer',
        'allow_fractional' => 'boolean',
        'is_active' => 'boolean',
        'is_base_unit' => 'boolean',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the base unit for conversion
     */
    public function baseUnit()
    {
        return $this->belongsTo(UnitofMeasure::class, 'base_unit_id');
    }

    /**
     * Get all units that use this as their base unit
     */
    public function derivedUnits()
    {
        return $this->hasMany(UnitofMeasure::class, 'base_unit_id');
    }

    /**
     * Scope: Active units
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Units by category
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: Base units only
     */
    public function scopeBaseUnits($query)
    {
        return $query->where('is_base_unit', true);
    }

    /**
     * Convert value from this unit to another unit
     */
    public function convertTo($value, UnitofMeasure $toUnit)
    {
        // Must be same category
        if ($this->category !== $toUnit->category) {
            throw new \Exception("Cannot convert between different unit categories");
        }

        // If same unit, return as is
        if ($this->id === $toUnit->id) {
            return $value;
        }

        // Convert to base unit first, then to target unit
        $baseValue = $value * $this->conversion_factor;
        $targetValue = $baseValue / $toUnit->conversion_factor;

        return round($targetValue, $toUnit->decimal_places);
    }

    /**
     * Get formatted display name with symbol
     */
    public function getDisplayNameAttribute()
    {
        return $this->symbol
            ? "{$this->name} ({$this->symbol})"
            : $this->name;
    }
}
