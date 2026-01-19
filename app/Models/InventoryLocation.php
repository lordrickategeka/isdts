<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryLocation extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'description',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'contact_person',
        'contact_phone',
        'contact_email',
        'capacity',
        'current_utilization',
        'is_active',
        'allow_negative_stock',
        'parent_location_id',
        'manager_id',
    ];

    protected $casts = [
        'capacity' => 'decimal:2',
        'current_utilization' => 'decimal:2',
        'is_active' => 'boolean',
        'allow_negative_stock' => 'boolean',
    ];

    /**
     * Types of inventory locations
     */
    const TYPE_WAREHOUSE = 'warehouse';
    const TYPE_STORE = 'store';
    const TYPE_OFFICE = 'office';
    const TYPE_VEHICLE = 'vehicle';
    const TYPE_SITE = 'site';
    const TYPE_OTHER = 'other';

    const TYPES = [
        self::TYPE_WAREHOUSE,
        self::TYPE_STORE,
        self::TYPE_OFFICE,
        self::TYPE_VEHICLE,
        self::TYPE_SITE,
        self::TYPE_OTHER,
    ];

    /**
     * Parent location relationship
     */
    public function parentLocation(): BelongsTo
    {
        return $this->belongsTo(InventoryLocation::class, 'parent_location_id');
    }

    /**
     * Child locations relationship
     */
    public function childLocations(): HasMany
    {
        return $this->hasMany(InventoryLocation::class, 'parent_location_id');
    }

    /**
     * Manager relationship
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Inventory items at this location
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItemLocation::class, 'location_id');
    }

    /**
     * Transactions at this location
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class, 'location_id');
    }

    /**
     * Stock adjustments at this location
     */
    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(StockAdjustment::class, 'location_id');
    }

    /**
     * Scope for active locations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific location type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for root locations (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_location_id');
    }

    /**
     * Get full location path
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $parent = $this->parentLocation;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parentLocation;
        }

        return implode(' > ', $path);
    }

    /**
     * Get total stock value at this location
     */
    public function getTotalStockValueAttribute(): float
    {
        return $this->inventoryItems()
            ->join('inventory_items', 'inventory_items.id', '=', 'inventory_item_locations.inventory_item_id')
            ->sum(\DB::raw('inventory_item_locations.quantity_on_hand * inventory_items.unit_cost'));
    }

    /**
     * Check if location has child locations
     */
    public function hasChildren(): bool
    {
        return $this->childLocations()->count() > 0;
    }

    /**
     * Update utilization percentage
     */
    public function updateUtilization(): void
    {
        if ($this->capacity > 0) {
            $usedCapacity = $this->inventoryItems()->sum('quantity_on_hand');
            $this->current_utilization = ($usedCapacity / $this->capacity) * 100;
            $this->save();
        }
    }
}
