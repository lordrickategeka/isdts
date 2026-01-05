<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSubcategory extends Model
{
    protected $fillable = [
        'service_type_id',
        'name',
        'description',
        'status',
        'sort_order',
    ];

    protected $casts = [
        //
    ];

    /**
     * Get the service type that owns this subcategory.
     */
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    // Note: products() relationship removed - products now belong to vendor_services
    // Use VendorService->products() instead
}
