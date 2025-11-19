<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProjectBudgetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'item_name',
        'description',
        'category',
        'quantity',
        'unit',
        'unit_cost',
        'total_cost',
        'justification',
        'status',
        'added_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total_cost = $item->quantity * $item->unit_cost;
        });
    }

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function availability(): HasOne
    {
        return $this->hasOne(ProjectItemAvailability::class);
    }
}
