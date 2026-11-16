<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'service_id', 'service_type', 'description',
        'reference_number', 'quantity', 'unit_price',
        'discount_percentage', 'discount_amount',
        'tax_percentage', 'tax_amount',
        'insurance_covered', 'patient_portion', 'total_amount', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity'            => 'integer',
            'unit_price'          => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'discount_amount'     => 'decimal:2',
            'tax_percentage'      => 'decimal:2',
            'tax_amount'          => 'decimal:2',
            'insurance_covered'   => 'decimal:2',
            'patient_portion'     => 'decimal:2',
            'total_amount'        => 'decimal:2',
        ];
    }

    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function service(): BelongsTo { return $this->belongsTo(Service::class); }
}
