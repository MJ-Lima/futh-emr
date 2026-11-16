<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceClaimLine extends Model
{
    protected $fillable = [
        'insurance_claim_id', 'service_type', 'service_code', 'service_name',
        'item_reference', 'quantity', 'unit_price', 'amount_claimed',
        'amount_approved', 'amount_paid', 'patient_copay', 'status',
        'rejection_reason', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity'         => 'integer',
            'unit_price'       => 'decimal:2',
            'amount_claimed'   => 'decimal:2',
            'amount_approved'  => 'decimal:2',
            'amount_paid'      => 'decimal:2',
            'patient_copay'    => 'decimal:2',
        ];
    }

    public function claim(): BelongsTo { return $this->belongsTo(InsuranceClaim::class, 'insurance_claim_id'); }
}
