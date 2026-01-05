<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'vendor_id',
        'vendor_service_id',
        'name',
        'description',
        
        // Optional pricing fields (for products that have pricing)
        'price',
        'capacity',
        'installation_charge',
        'monthly_charge',
        'billing_cycle',
        'specifications',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'installation_charge' => 'decimal:2',
        'monthly_charge' => 'decimal:2',
        'specifications' => 'array',
    ];

    /**
     * Get the vendor that owns this product.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the vendor service that owns this product.
     */
    public function vendorService()
    {
        return $this->belongsTo(VendorService::class);
    }
}