<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItemLocation extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'location_id',
        'quantity_on_hand',
        'quantity_reserved',
        'reorder_level',
        'max_stock_level',
        'bin_location',
        'aisle',
        'shelf',
        'last_stock_take_date',
        'last_movement_date',
    ];

    protected $casts = [
        'quantity_on_hand' => 'decimal:2',
        'quantity_reserved' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'max_stock_level' => 'decimal:2',
        'last_stock_take_date' => 'datetime',
        'last_movement_date' => 'datetime',
    ];

    /**
     * Inventory item relationship
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Location relationship
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(InventoryLocation::class);
    }

    /**
     * Get available quantity
     */
    public function getQuantityAvailableAttribute(): float
    {
        return $this->quantity_on_hand - $this->quantity_reserved;
    }

    /**
     * Check if needs reorder at this location
     */
    public function needsReorder(): bool
    {
        return $this->reorder_level && $this->quantity_on_hand <= $this->reorder_level;
    }

    /**
     * Check if over max stock
     */
    public function isOverMax(): bool
    {
        return $this->max_stock_level && $this->quantity_on_hand > $this->max_stock_level;
    }
}
