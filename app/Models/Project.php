<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_code',
        'name',
        'description',

        'start_date',
        'end_date',
        'estimated_budget',
        'status',
        'priority',
        'created_by',
        'client_id',
        'objectives',
        'deliverables',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'estimated_budget' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->project_code)) {
                $project->project_code = 'PRJ-' . strtoupper(uniqid());
            }
        });
    }

    // Relationships
    public function form()
    {
        return $this->morphOne(\App\Models\Form::class, 'formable');
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function budgetItems(): HasMany
    {
        return $this->hasMany(ProjectBudgetItem::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(ProjectApproval::class);
    }

    public function itemAvailability(): HasMany
    {
        return $this->hasMany(ProjectItemAvailability::class);
    }

    /**
     * Get all client services for this project
     */
    public function clientServices(): HasMany
    {
        return $this->hasMany(ClientService::class);
    }

    /**
     * Get all milestones for this project
     */
    public function milestones(): HasMany
    {
        return $this->hasMany(ProjectMilestone::class);
    }

    /**
     * Get all clients associated with this project through client services
     */
    public function clients()
    {
        return $this->hasManyThrough(
            Client::class,
            ClientService::class,
            'project_id', // Foreign key on client_services table
            'id',         // Foreign key on clients table
            'id',         // Local key on projects table
            'client_id'   // Local key on client_services table
        )->distinct();
    }

    // Helper methods
    public function getTotalBudgetAttribute(): float
    {
        return $this->budgetItems()->sum('total_cost');
    }

    public function isFullyApproved(): bool
    {
        return $this->approvals()
            ->where('status', 'approved')
            ->whereIn('approver_role', ['cto', 'director'])
            ->count() === 2;
    }

    public function hasRejection(): bool
    {
        return $this->approvals()
            ->where('status', 'rejected')
            ->exists();
    }

    public function getApprovalStatus(): string
    {
        if ($this->hasRejection()) {
            return 'rejected';
        }

        if ($this->isFullyApproved()) {
            return 'approved';
        }

        $approvedCount = $this->approvals()
            ->where('status', 'approved')
            ->count();

        if ($approvedCount > 0) {
            return 'partially_approved';
        }

        return 'pending';
    }
}
