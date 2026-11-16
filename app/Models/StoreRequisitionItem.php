<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreRequisitionItem extends Model
{
    protected $fillable = [
        'store_requisition_id', 'store_item_id',
        'quantity_requested', 'quantity_approved', 'quantity_issued',
        'unit_cost', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity_requested' => 'decimal:2',
            'quantity_approved'  => 'decimal:2',
            'quantity_issued'    => 'decimal:2',
            'unit_cost'          => 'decimal:2',
        ];
    }

    public function requisition(): BelongsTo { return $this->belongsTo(StoreRequisition::class); }
    public function item(): BelongsTo { return $this->belongsTo(StoreItem::class, 'store_item_id'); }
}
