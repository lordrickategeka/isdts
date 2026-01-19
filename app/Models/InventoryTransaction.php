<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    use Auditable;

    protected $fillable = [
        'transaction_number',
        'transaction_type',
        'inventory_item_id',
        'location_id',
        'to_location_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'balance_before',
        'balance_after',
        'reference_type',
        'reference_id',
        'reference_number',
        'notes',
        'batch_number',
        'serial_number',
        'expiry_date',
        'created_by',
        'approved_by',
        'approved_at',
        'status',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'expiry_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Transaction types
     */
    const TYPE_RECEIPT = 'receipt';
    const TYPE_ISSUE = 'issue';
    const TYPE_TRANSFER = 'transfer';
    const TYPE_ADJUSTMENT = 'adjustment';
    const TYPE_RETURN = 'return';
    const TYPE_DAMAGE = 'damage';
    const TYPE_PRODUCTION = 'production';
    const TYPE_COUNT = 'count';

    const TYPES = [
        self::TYPE_RECEIPT,
        self::TYPE_ISSUE,
        self::TYPE_TRANSFER,
        self::TYPE_ADJUSTMENT,
        self::TYPE_RETURN,
        self::TYPE_DAMAGE,
        self::TYPE_PRODUCTION,
        self::TYPE_COUNT,
    ];

    /**
     * Transaction statuses
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';

    const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
        self::STATUS_COMPLETED,
    ];

    /**
     * Inventory item relationship
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * From location relationship
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(InventoryLocation::class, 'location_id');
    }

    /**
     * To location relationship (for transfers)
     */
    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(InventoryLocation::class, 'to_location_id');
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
     * Polymorphic relationship to reference (project, client, etc.)
     */
    public function reference()
    {
        return $this->morphTo();
    }

    /**
     * Scope for transaction type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    /**
     * Scope for specific location
     */
    public function scopeAtLocation($query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Generate unique transaction number
     */
    public static function generateTransactionNumber(string $type): string
    {
        $prefix = strtoupper(substr($type, 0, 3));
        $date = now()->format('Ymd');
        $lastTransaction = self::where('transaction_number', 'like', "{$prefix}-{$date}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->transaction_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$date}-{$newNumber}";
    }

    /**
     * Check if transaction increases stock
     */
    public function increasesStock(): bool
    {
        return in_array($this->transaction_type, [
            self::TYPE_RECEIPT,
            self::TYPE_ADJUSTMENT,
            self::TYPE_PRODUCTION,
        ]) && $this->quantity > 0;
    }

    /**
     * Check if transaction decreases stock
     */
    public function decreasesStock(): bool
    {
        return in_array($this->transaction_type, [
            self::TYPE_ISSUE,
            self::TYPE_RETURN,
            self::TYPE_DAMAGE,
        ]) || ($this->transaction_type === self::TYPE_ADJUSTMENT && $this->quantity < 0);
    }

    /**
     * Approve transaction
     */
    public function approve(int $userId): void
    {
        $this->approved_by = $userId;
        $this->approved_at = now();
        $this->status = self::STATUS_APPROVED;
        $this->save();
    }

    /**
     * Reject transaction
     */
    public function reject(): void
    {
        $this->status = self::STATUS_REJECTED;
        $this->save();
    }
}
