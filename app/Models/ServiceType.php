<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'base_price',
        'billing_cycle',
        'status',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];

    /**
     * Get the subcategories for this service type.
     */
    public function subcategories()
    {
        return $this->hasMany(ServiceSubcategory::class)->orderBy('sort_order');
    }

    /**
     * Get the products for this service type.
     */
    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('sort_order');
    }
}
