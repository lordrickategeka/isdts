<?php

namespace App\Models;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectPerson extends Model
{
    use Auditable;

    protected $fillable = [
        'project_id',
        'role_type',
        'client_id',
        'user_id',
        'responsibility',
        'assigned_date',
        'end_date',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors
    public function getPersonNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }

        if ($this->client) {
            return $this->client->name;
        }

        return 'Unknown';
    }

    public function getPersonTypeAttribute(): string
    {
        if ($this->user_id) {
            return 'User';
        }

        if ($this->client_id) {
            return 'Client';
        }

        return 'Unknown';
    }

    public function getRoleBadgeColorAttribute(): string
    {
        return match($this->role_type) {
            'project_manager' => 'blue',
            'sponsor' => 'purple',
            'client' => 'green',
            'staff' => 'gray',
            default => 'gray',
        };
    }

    // Helper Methods
    public function isActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        return true;
    }

    public function markAsInactive(): bool
    {
        $this->is_active = false;
        if (!$this->end_date) {
            $this->end_date = now();
        }
        return $this->save();
    }

    public function markAsActive(): bool
    {
        $this->is_active = true;
        $this->end_date = null;
        return $this->save();
    }
}
