<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceFeasibilityVendor extends Model
{
    protected $fillable = [
        'service_feasibility_id',
        'vendor_id',
        'vendor_service_id',
        'nrc_cost',
        'mrc_cost',
        'notes',
    ];

    protected $casts = [
        'nrc_cost' => 'decimal:2',
        'mrc_cost' => 'decimal:2',
    ];

    public function serviceFeasibility(): BelongsTo
    {
        return $this->belongsTo(ServiceFeasibility::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function vendorService(): BelongsTo
    {
        return $this->belongsTo(VendorService::class);
    }
}
