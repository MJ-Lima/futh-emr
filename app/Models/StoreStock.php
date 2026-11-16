<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreStock extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'store_item_id', 'store_location_id',
        'batch_number', 'expiry_date', 'quantity_received', 'quantity_remaining',
        'unit_cost', 'received_date', 'reference_number', 'is_quarantined',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date'        => 'date',
            'received_date'      => 'date',
            'quantity_received'  => 'decimal:2',
            'quantity_remaining' => 'decimal:2',
            'unit_cost'          => 'decimal:2',
            'is_quarantined'     => 'boolean',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function item(): BelongsTo { return $this->belongsTo(StoreItem::class, 'store_item_id'); }
    public function location(): BelongsTo { return $this->belongsTo(StoreLocation::class, 'store_location_id'); }

    public function isExpired(): bool
    {
        return $this->expiry_date?->isPast() ?? false;
    }

    public function isExpiringSoon(int $days = 90): bool
    {
        return $this->expiry_date && $this->expiry_date->isFuture()
            && $this->expiry_date->diffInDays(now()) <= $days;
    }
}
