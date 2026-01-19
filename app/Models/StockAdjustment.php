<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    use Auditable;

    protected $fillable = [
        'adjustment_number',
        'inventory_item_id',
        'location_id',
        'adjustment_type',
        'quantity_before',
        'quantity_counted',
        'quantity_adjusted',
        'quantity_after',
        'unit_cost',
        'total_cost_impact',
        'reason',
        'notes',
        'document_reference',
        'created_by',
        'approved_by',
        'approved_at',
        'status',
        'batch_number',
        'serial_number',
    ];

    protected $casts = [
        'quantity_before' => 'decimal:2',
        'quantity_counted' => 'decimal:2',
        'quantity_adjusted' => 'decimal:2',
        'quantity_after' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost_impact' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Adjustment types
     */
    const TYPE_PHYSICAL_COUNT = 'physical_count';
    const TYPE_DAMAGE = 'damage';
    const TYPE_LOSS = 'loss';
    const TYPE_FOUND = 'found';
    const TYPE_EXPIRY = 'expiry';
    const TYPE_QUALITY = 'quality';
    const TYPE_CORRECTION = 'correction';
    const TYPE_WRITE_OFF = 'write_off';
    const TYPE_WRITE_ON = 'write_on';

    const TYPES = [
        self::TYPE_PHYSICAL_COUNT,
        self::TYPE_DAMAGE,
        self::TYPE_LOSS,
        self::TYPE_FOUND,
        self::TYPE_EXPIRY,
        self::TYPE_QUALITY,
        self::TYPE_CORRECTION,
        self::TYPE_WRITE_OFF,
        self::TYPE_WRITE_ON,
    ];

    /**
     * Adjustment reasons
     */
    const REASON_PHYSICAL_COUNT = 'physical_count';
    const REASON_DAMAGE = 'damage';
    const REASON_THEFT = 'theft';
    const REASON_OBSOLETE = 'obsolete';
    const REASON_EXPIRED = 'expired';
    const REASON_DATA_ERROR = 'data_error';
    const REASON_QUALITY_ISSUE = 'quality_issue';
    const REASON_OTHER = 'other';

    const REASONS = [
        self::REASON_PHYSICAL_COUNT,
        self::REASON_DAMAGE,
        self::REASON_THEFT,
        self::REASON_OBSOLETE,
        self::REASON_EXPIRED,
        self::REASON_DATA_ERROR,
        self::REASON_QUALITY_ISSUE,
        self::REASON_OTHER,
    ];

    /**
     * Adjustment statuses
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_APPROVAL = 'pending_approval';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING_APPROVAL,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
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
     * Created by user relationship
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Approved by user relationship
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for adjustment type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('adjustment_type', $type);
    }

    /**
     * Scope for pending approval
     */
    public function scopePendingApproval($query)
    {
        return $query->where('status', self::STATUS_PENDING_APPROVAL);
    }

    /**
     * Scope for approved adjustments
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Generate unique adjustment number
     */
    public static function generateAdjustmentNumber(): string
    {
        $date = now()->format('Ymd');
        $lastAdjustment = self::where('adjustment_number', 'like', "ADJ-{$date}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastAdjustment) {
            $lastNumber = (int) substr($lastAdjustment->adjustment_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "ADJ-{$date}-{$newNumber}";
    }

    /**
     * Submit for approval
     */
    public function submitForApproval(): void
    {
        $this->status = self::STATUS_PENDING_APPROVAL;
        $this->save();
    }

    /**
     * Approve adjustment
     */
    public function approve(int $userId): void
    {
        $this->approved_by = $userId;
        $this->approved_at = now();
        $this->status = self::STATUS_APPROVED;
        $this->save();

        // Create inventory transaction
        $this->createInventoryTransaction();
    }

    /**
     * Reject adjustment
     */
    public function reject(): void
    {
        $this->status = self::STATUS_REJECTED;
        $this->save();
    }

    /**
     * Create corresponding inventory transaction
     */
    protected function createInventoryTransaction(): void
    {
        InventoryTransaction::create([
            'transaction_number' => InventoryTransaction::generateTransactionNumber('adjustment'),
            'transaction_type' => InventoryTransaction::TYPE_ADJUSTMENT,
            'inventory_item_id' => $this->inventory_item_id,
            'location_id' => $this->location_id,
            'quantity' => $this->quantity_adjusted,
            'unit_cost' => $this->unit_cost,
            'total_cost' => $this->total_cost_impact,
            'balance_before' => $this->quantity_before,
            'balance_after' => $this->quantity_after,
            'reference_type' => self::class,
            'reference_id' => $this->id,
            'reference_number' => $this->adjustment_number,
            'notes' => $this->notes,
            'batch_number' => $this->batch_number,
            'serial_number' => $this->serial_number,
            'created_by' => $this->created_by,
            'approved_by' => $this->approved_by,
            'approved_at' => $this->approved_at,
            'status' => InventoryTransaction::STATUS_COMPLETED,
        ]);

        // Update inventory item quantity
        $this->inventoryItem->adjustStock($this->quantity_adjusted, $this->location_id);
    }

    /**
     * Check if adjustment increases stock
     */
    public function increasesStock(): bool
    {
        return $this->quantity_adjusted > 0;
    }

    /**
     * Check if adjustment decreases stock
     */
    public function decreasesStock(): bool
    {
        return $this->quantity_adjusted < 0;
    }
}
