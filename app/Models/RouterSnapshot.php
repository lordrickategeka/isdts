<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouterSnapshot extends Model
{
    protected $fillable = [
        'router_id',
        'source',
        'payload',
        'captured_at',
        'record_count',
        'duration_ms',
        'success',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
        'captured_at' => 'datetime',
        'success' => 'boolean',
    ];

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }
}
