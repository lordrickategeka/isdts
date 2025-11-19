<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceFeasibilityMaterial extends Model
{
    protected $fillable = [
        'service_feasibility_id',
        'name',
        'quantity',
        'unit_cost',
        'total_cost',
        'description',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically calculate total_cost when saving
        static::saving(function ($material) {
            $material->total_cost = $material->quantity * $material->unit_cost;
        });
    }

    public function serviceFeasibility(): BelongsTo
    {
        return $this->belongsTo(ServiceFeasibility::class);
    }
}
