<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientAgreement extends Model
{
    protected $fillable = [
        'client_id',
        'agreement_number',
        'payment_type',
        'proof_of_payment',
        'client_signature_data',
        'client_signed_at',
        'notes',
        'status',
    ];

    protected $casts = [
        'client_signed_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
