<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NetworkSession extends Model
{
    protected $fillable = [
        'router_id',
        'mac_address',
        'username',
        'ip_address',
        'access_type',
        'authenticated',
        'active',
        'bytes_in',
        'bytes_out',
        'started_at',
        'last_seen_at',
        'ended_at',
        'confidence_score',
        'sources',
        'attributes',
    ];

    protected $casts = [
        'authenticated' => 'boolean',
        'active' => 'boolean',
        'started_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'ended_at' => 'datetime',
        'sources' => 'array',
        'attributes' => 'array',
    ];

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(NetworkSessionEvent::class);
    }

    public function enforcements(): HasMany
    {
        return $this->hasMany(NetworkEnforcement::class);
    }
}
