<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouterTruthSource extends Model
{
    protected $fillable = [
        'router_id',
        'source',
        'enabled',
        'poll_interval_seconds',
        'last_polled_at',
        'failures',
        'disabled_at',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'last_polled_at' => 'datetime',
        'disabled_at' => 'datetime',
    ];

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }
}
