<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'sku',
        'barcode',
        'name',
        'description',
        'type',
        'category',
        'subcategory',
        'unit_of_measure',
        'unit_weight',
        'unit_volume',
        'quantity_on_hand',
        'quantity_reserved',
        'reorder_level',
        'reorder_quantity',
        'max_stock_level',
        'costing_method',
        'unit_cost',
        'average_cost',
        'standard_cost',
        'last_purchase_cost',
        'last_purchase_date',
        'track_serial_numbers',
        'track_batches',
        'track_expiry',
        'shelf_life_days',
        'product_id',
        'preferred_vendor_id',
        'is_active',
        'is_stockable',
        'is_purchasable',
        'is_sellable',
        'storage_location',
        'notes',
        'custom_attributes',
    ];

    protected $casts = [
        'unit_weight' => 'decimal:3',
        'unit_volume' => 'decimal:3',
        'quantity_on_hand' => 'decimal:2',
        'quantity_reserved' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'reorder_quantity' => 'decimal:2',
        'max_stock_level' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'average_cost' => 'decimal:2',
        'standard_cost' => 'decimal:2',
        'last_purchase_cost' => 'decimal:2',
        'last_purchase_date' => 'date',
        'track_serial_numbers' => 'boolean',
        'track_batches' => 'boolean',
        'track_expiry' => 'boolean',
        'is_active' => 'boolean',
        'is_stockable' => 'boolean',
        'is_purchasable' => 'boolean',
        'is_sellable' => 'boolean',
        'custom_attributes' => 'array',
    ];

    /**
     * Item types
     */
    const TYPE_PRODUCT = 'product';
    const TYPE_MATERIAL = 'material';
    const TYPE_EQUIPMENT = 'equipment';
    const TYPE_CONSUMABLE = 'consumable';
    const TYPE_SPARE_PART = 'spare_part';
    const TYPE_OTHER = 'other';

    const TYPES = [
        self::TYPE_PRODUCT,
        self::TYPE_MATERIAL,
        self::TYPE_EQUIPMENT,
        self::TYPE_CONSUMABLE,
        self::TYPE_SPARE_PART,
        self::TYPE_OTHER,
    ];

    /**
     * Costing methods
     */
    const COSTING_FIFO = 'FIFO';
    const COSTING_LIFO = 'LIFO';
    const COSTING_AVERAGE = 'Average';
    const COSTING_STANDARD = 'Standard';

    const COSTING_METHODS = [
        self::COSTING_FIFO,
        self::COSTING_LIFO,
        self::COSTING_AVERAGE,
        self::COSTING_STANDARD,
    ];

    /**
     * Product relationship
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Preferred vendor relationship
     */
    public function preferredVendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'preferred_vendor_id');
    }

    /**
     * Location stock levels
     */
    public function locationStock(): HasMany
    {
        return $this->hasMany(InventoryItemLocation::class);
    }

    /**
     * Transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    /**
     * Stock adjustments
     */
    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(StockAdjustment::class);
    }

    /**
     * Scope for active items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for items below reorder level
     */
    public function scopeBelowReorderLevel($query)
    {
        return $query->whereColumn('quantity_on_hand', '<=', 'reorder_level');
    }

    /**
     * Scope for out of stock items
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity_on_hand', '<=', 0);
    }

    /**
     * Scope by item type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get available quantity (on hand minus reserved)
     */
    public function getQuantityAvailableAttribute(): float
    {
        return $this->quantity_on_hand - $this->quantity_reserved;
    }

    /**
     * Get total stock value
     */
    public function getTotalValueAttribute(): float
    {
        return $this->quantity_on_hand * $this->unit_cost;
    }

    /**
     * Check if item needs reordering
     */
    public function needsReorder(): bool
    {
        return $this->quantity_on_hand <= $this->reorder_level;
    }

    /**
     * Check if item is out of stock
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity_on_hand <= 0;
    }

    /**
     * Update average cost
     */
    public function updateAverageCost(float $newCost, float $quantity): void
    {
        if ($this->quantity_on_hand > 0) {
            $totalValue = ($this->quantity_on_hand * $this->average_cost) + ($quantity * $newCost);
            $totalQuantity = $this->quantity_on_hand + $quantity;
            $this->average_cost = $totalValue / $totalQuantity;
        } else {
            $this->average_cost = $newCost;
        }
        $this->save();
    }

    /**
     * Adjust stock quantity
     */
    public function adjustStock(float $quantity, string $locationId = null): void
    {
        $this->quantity_on_hand += $quantity;
        $this->save();

        if ($locationId) {
            $locationStock = $this->locationStock()->where('location_id', $locationId)->first();
            if ($locationStock) {
                $locationStock->quantity_on_hand += $quantity;
                $locationStock->last_movement_date = now();
                $locationStock->save();
            }
        }
    }

    /**
     * Reserve stock
     */
    public function reserveStock(float $quantity): bool
    {
        if ($this->quantity_available >= $quantity) {
            $this->quantity_reserved += $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Release reserved stock
     */
    public function releaseReservedStock(float $quantity): void
    {
        $this->quantity_reserved = max(0, $this->quantity_reserved - $quantity);
        $this->save();
    }
}
