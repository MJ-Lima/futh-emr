<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id', 'drug_id', 'drug_name', 'batch_number', 'expiry_date',
        'quantity_ordered', 'quantity_received', 'unit_cost', 'tax_percent',
        'discount_percent', 'line_total',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date'       => 'date',
            'quantity_ordered'  => 'decimal:2',
            'quantity_received' => 'decimal:2',
            'unit_cost'         => 'decimal:2',
            'tax_percent'       => 'decimal:2',
            'discount_percent'  => 'decimal:2',
            'line_total'        => 'decimal:2',
        ];
    }

    public function purchase(): BelongsTo { return $this->belongsTo(Purchase::class); }
    public function drug(): BelongsTo { return $this->belongsTo(Drug::class); }
}
