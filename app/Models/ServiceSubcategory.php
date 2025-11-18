<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSubcategory extends Model
{
    protected $fillable = [
        'service_type_id',
        'name',
        'description',
        'price_modifier',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
    ];

    /**
     * Get the service type that owns this subcategory.
     */
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Get the products for this subcategory.
     */
    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('sort_order');
    }
}
