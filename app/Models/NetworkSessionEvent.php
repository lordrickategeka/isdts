<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NetworkSessionEvent extends Model
{
    protected $fillable = [
        'network_session_id',
        'router_id',
        'event_type',
        'source',
        'before',
        'after',
        'reason',
        'metadata',
        'occurred_at',
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
        'metadata' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function networkSession(): BelongsTo
    {
        return $this->belongsTo(NetworkSession::class);
    }

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }
}
