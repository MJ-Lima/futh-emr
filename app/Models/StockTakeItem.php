<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTakeItem extends Model
{
    protected $fillable = [
        'stock_take_id', 'store_item_id', 'drug_id', 'item_name',
        'batch_number', 'system_quantity', 'counted_quantity', 'variance',
        'unit_cost', 'variance_value', 'remarks',
    ];

    protected function casts(): array
    {
        return [
            'system_quantity'   => 'decimal:2',
            'counted_quantity'  => 'decimal:2',
            'variance'          => 'decimal:2',
            'unit_cost'         => 'decimal:2',
            'variance_value'    => 'decimal:2',
        ];
    }

    public function stockTake(): BelongsTo { return $this->belongsTo(StockTake::class); }
    public function storeItem(): BelongsTo { return $this->belongsTo(StoreItem::class); }
    public function drug(): BelongsTo { return $this->belongsTo(Drug::class); }
}
