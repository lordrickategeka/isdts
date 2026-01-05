<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        //
    ];

    /**
     * Get the subcategories for this service type.
     */
    public function subcategories()
    {
        return $this->hasMany(ServiceSubcategory::class)->orderBy('sort_order');
    }

    // Note: products() relationship removed - products now belong to vendor_services
    // Use VendorService->products() instead
}
