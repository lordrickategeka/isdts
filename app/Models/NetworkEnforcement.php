<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NetworkEnforcement extends Model
{
    protected $fillable = [
        'network_session_id',
        'router_id',
        'mac_address',
        'ip_address',
        'username',
        'action',
        'parameters',
        'status',
        'applied_at',
        'expires_at',
        'failure_reason',
        'router_response',
    ];

    protected $casts = [
        'parameters' => 'array',
        'applied_at' => 'datetime',
        'expires_at' => 'datetime',
        'router_response' => 'array',
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
