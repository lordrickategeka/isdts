<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectItemAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_budget_item_id',
        'project_id',
        'available_quantity',
        'required_quantity',
        'availability_status',
        'notes',
        'expected_availability_date',
        'checked_by',
        'checked_at',
    ];

    protected $casts = [
        'available_quantity' => 'integer',
        'required_quantity' => 'integer',
        'expected_availability_date' => 'date',
        'checked_at' => 'datetime',
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function budgetItem(): BelongsTo
    {
        return $this->belongsTo(ProjectBudgetItem::class, 'project_budget_item_id');
    }

    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
