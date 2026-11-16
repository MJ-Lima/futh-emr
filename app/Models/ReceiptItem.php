<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptItem extends Model
{
    protected $fillable = [
        'receipt_id', 'drug_id', 'drug_batch_id', 'batch_number', 'expiry_date',
        'quantity_received', 'quantity_rejected', 'unit_cost', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date'       => 'date',
            'quantity_received' => 'decimal:2',
            'quantity_rejected' => 'decimal:2',
            'unit_cost'         => 'decimal:2',
        ];
    }

    public function receipt(): BelongsTo { return $this->belongsTo(Receipt::class); }
    public function drug(): BelongsTo { return $this->belongsTo(Drug::class); }
    public function drugBatch(): BelongsTo { return $this->belongsTo(DrugBatch::class); }
}
