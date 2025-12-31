<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectService extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_INSTALLED = 'installed';
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_TERMINATED = 'terminated';

    protected $fillable = [
        'project_id',
        'vendor_id',
        'service_type',
        'transmission',
        'vlan',
        'capacity',
        'nrc',
        'mrc',
        'installation_date',
        'installation_engineer_id',
        'status',
    ];

    protected $casts = [
        'installation_date' => 'date',
        'nrc' => 'decimal:2',
        'mrc' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function installationEngineer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'installation_engineer_id');
    }
}
