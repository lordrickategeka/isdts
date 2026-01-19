<?php

namespace App\Models;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class ProjectTask extends Model
{
    use Auditable;

    protected $fillable = [
        'project_id',
        'milestone_id',
        'task_code',
        'name',
        'description',
        'start_date',
        'due_date',
        'completed_date',
        'estimated_hours',
        'actual_hours',
        'status',
        'progress_percentage',
        'priority',
        'task_type',
        'depends_on_task_id',
        'assigned_to',
        'assigned_date',
        'acceptance_criteria',
        'notes',
        'tags',
        'created_by',
        'completed_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_date' => 'date',
        'assigned_date' => 'date',
        'estimated_hours' => 'integer',
        'actual_hours' => 'integer',
        'progress_percentage' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (empty($task->task_code)) {
                // Generate task code: T-001, T-002, etc.
                $count = static::where('project_id', $task->project_id)->count();
                $task->task_code = 'T-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(ProjectMilestone::class, 'milestone_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function completedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function dependsOnTask(): BelongsTo
    {
        return $this->belongsTo(ProjectTask::class, 'depends_on_task_id');
    }

    public function dependentTasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class, 'depends_on_task_id');
    }

    // Scopes
    public function scopeByProject(Builder $query, int $projectId): Builder
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByMilestone(Builder $query, int $milestoneId): Builder
    {
        return $query->where('milestone_id', $milestoneId);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    public function scopeAssignedTo(Builder $query, int $userId): Builder
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeDueThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopeBlocked(Builder $query): Builder
    {
        return $query->where('status', 'blocked');
    }

    public function scopeHighPriority(Builder $query): Builder
    {
        return $query->whereIn('priority', ['high', 'critical']);
    }

    // Accessors & Mutators
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->due_date || in_array($this->status, ['completed', 'cancelled'])) {
            return false;
        }
        return $this->due_date->isPast();
    }

    public function getIsDueSoonAttribute(): bool
    {
        if (!$this->due_date || in_array($this->status, ['completed', 'cancelled'])) {
            return false;
        }
        return $this->due_date->isBetween(now(), now()->addDays(3));
    }

    public function getDaysUntilDueAttribute(): ?int
    {
        if (!$this->due_date) {
            return null;
        }
        return now()->diffInDays($this->due_date, false);
    }

    public function getDurationDaysAttribute(): ?int
    {
        if (!$this->start_date || !$this->due_date) {
            return null;
        }
        return $this->start_date->diffInDays($this->due_date);
    }

    public function getHoursVarianceAttribute(): ?int
    {
        if (!$this->estimated_hours || !$this->actual_hours) {
            return null;
        }
        return $this->actual_hours - $this->estimated_hours;
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'todo' => 'gray',
            'in_progress' => 'blue',
            'review' => 'yellow',
            'completed' => 'green',
            'blocked' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function getPriorityBadgeColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'gray',
            'medium' => 'blue',
            'high' => 'orange',
            'critical' => 'red',
            default => 'gray',
        };
    }

    // Helper Methods
    public function isOverdue(): bool
    {
        return $this->is_overdue;
    }

    public function isDueSoon(): bool
    {
        return $this->is_due_soon;
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function canStart(): bool
    {
        if (!$this->depends_on_task_id) {
            return true;
        }

        $dependentTask = $this->dependsOnTask;
        return $dependentTask && $dependentTask->isCompleted();
    }

    public function markAsInProgress(): bool
    {
        if (!$this->canStart()) {
            return false;
        }

        $this->status = 'in_progress';
        if (!$this->start_date) {
            $this->start_date = now();
        }
        return $this->save();
    }

    public function markAsCompleted(int $completedBy): bool
    {
        $this->status = 'completed';
        $this->completed_date = now();
        $this->completed_by = $completedBy;
        $this->progress_percentage = 100;
        return $this->save();
    }

    public function markAsBlocked(): bool
    {
        $this->status = 'blocked';
        return $this->save();
    }

    public function assignTo(int $userId): bool
    {
        $this->assigned_to = $userId;
        $this->assigned_date = now();
        return $this->save();
    }

    public function updateProgress(int $percentage): bool
    {
        $this->progress_percentage = max(0, min(100, $percentage));

        // Auto-update status based on progress
        if ($percentage === 0 && $this->status === 'in_progress') {
            $this->status = 'todo';
        } elseif ($percentage > 0 && $percentage < 100 && $this->status === 'todo') {
            $this->status = 'in_progress';
        } elseif ($percentage === 100 && $this->status !== 'completed') {
            $this->status = 'review';
        }

        return $this->save();
    }

    public function logActualHours(int $hours): bool
    {
        $this->actual_hours = ($this->actual_hours ?? 0) + $hours;
        return $this->save();
    }

    public function hasBlockedDependents(): bool
    {
        if (!$this->isCompleted()) {
            return $this->dependentTasks()
                ->whereNotIn('status', ['todo', 'cancelled'])
                ->exists();
        }
        return false;
    }

    public function getCompletionPercentage(): int
    {
        return $this->progress_percentage ?? 0;
    }

    public function getEstimatedCompletionDate(): ?string
    {
        if (!$this->start_date || !$this->estimated_hours || $this->isCompleted()) {
            return null;
        }

        $actualHours = $this->actual_hours ?? 0;
        $progressPercent = $this->progress_percentage ?? 0;

        if ($progressPercent === 0) {
            return null;
        }

        // Calculate estimated total hours based on current progress
        $estimatedTotalHours = ($actualHours / $progressPercent) * 100;
        $remainingHours = $estimatedTotalHours - $actualHours;

        // Assume 8 working hours per day
        $remainingDays = ceil($remainingHours / 8);

        return now()->addDays($remainingDays)->format('Y-m-d');
    }
}
