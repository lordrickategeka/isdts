<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectSite extends Model
{
    protected $fillable = [
        'project_id',
        'region',
        'district',
        'address',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
