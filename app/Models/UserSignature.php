<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSignature extends Model
{
    protected $fillable = [
        'signature_code',
        'user_id',
        'client_id',
        'agreement_number',
        'position',
        'signature_data',
        'signed_at',
        'status',
        'remarks',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    /**
     * Boot the model and set up event listeners
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($signature) {
            if (empty($signature->signature_code)) {
                $signature->signature_code = self::generateSignatureCode();
            }
        });
    }

    /**
     * Generate unique signature code in format: BCC_HD8U9X
     * Where the last 6 characters are a random mix of uppercase letters and numbers
     */
    private static function generateSignatureCode(): string
    {
        do {
            // Generate 6 random characters (uppercase letters and numbers)
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $randomPart = '';

            for ($i = 0; $i < 6; $i++) {
                $randomPart .= $characters[rand(0, strlen($characters) - 1)];
            }

            $code = 'BCC_' . $randomPart;
        } while (self::where('signature_code', $code)->exists());

        return $code;
    }

    /**
     * Get the user that owns the signature
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the client associated with the signature
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Check if signature is signed
     */
    public function isSigned(): bool
    {
        return $this->status === 'signed' && !empty($this->signed_at);
    }

    /**
     * Check if signature is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if signature is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
