<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'service_type_id',
        'service_subcategory_id',
        'name',
        'description',
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
     * Get the service type that owns this product.
     */
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Get the subcategory that owns this product.
     */
    public function subcategory()
    {
        return $this->belongsTo(ServiceSubcategory::class, 'service_subcategory_id');
    }
}
