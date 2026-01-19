<?php

namespace App\Models;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectMilestone extends Model
{
    use Auditable;

    protected $fillable = [
        'project_id',
        'milestone_code',
        'name',
        'description',
        'start_date',
        'due_date',
        'completed_date',
        'amount',
        'percentage',
        'is_billable',
        'invoiced_date',
        'status',
        'progress_percentage',
        'depends_on_milestone_id',
        'priority',
        'deliverables',
        'notes',
        'assigned_to',
        'approved_by',
        'approved_date',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_date' => 'date',
        'invoiced_date' => 'date',
        'approved_date' => 'date',
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'is_billable' => 'boolean',
        'progress_percentage' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class, 'milestone_id');
    }

    public function dependsOnMilestone(): BelongsTo
    {
        return $this->belongsTo(ProjectMilestone::class, 'depends_on_milestone_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
