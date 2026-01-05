<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceFeasibility extends Model
{
    protected $fillable = [
        'is_feasible',
        'notes',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'is_feasible' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function vendors(): HasMany
    {
        return $this->hasMany(ServiceFeasibilityVendor::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ServiceFeasibilityMaterial::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get total NRC cost from all vendors
     */
    public function getTotalNrcAttribute()
    {
        return $this->vendors()->sum('nrc_cost');
    }

    /**
     * Get total MRC cost from all vendors
     */
    public function getTotalMrcAttribute()
    {
        return $this->vendors()->sum('mrc_cost');
    }

    /**
     * Get total material cost
     */
    public function getTotalMaterialCostAttribute()
    {
        return $this->materials()->sum('total_cost');
    }

    /**
     * Get grand total cost (NRC + Materials)
     */
    public function getGrandTotalCostAttribute()
    {
        return $this->getTotalNrcAttribute() + $this->getTotalMaterialCostAttribute();
    }
}
