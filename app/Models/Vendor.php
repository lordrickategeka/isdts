<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = [
        'vendor_code',
        'name',
        'email',
        'phone',
        'address',
        'status',
        'notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vendor) {
            if (empty($vendor->vendor_code)) {
                $vendor->vendor_code = self::generateVendorCode();
            }
        });
    }

    /**
     * Generate unique vendor code in format: VND-YYYYMMDD-XXXX
     */
    private static function generateVendorCode()
    {
        $date = date('Ymd');
        $lastVendor = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastVendor && preg_match('/VND-\d{8}-(\d{4})/', $lastVendor->vendor_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        } else {
            $sequence = 1;
        }

        return 'VND-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function services(): HasMany
    {
        return $this->hasMany(VendorService::class);
    }
}
