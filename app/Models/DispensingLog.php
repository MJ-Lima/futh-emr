<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DispensingLog extends Model
{
    protected $fillable = [
        'prescription_id', 'prescription_item_id', 'drug_id', 'drug_batch_id',
        'dispensing_point_id', 'patient_id', 'user_id',
        'quantity_dispensed', 'balance_after',
        'dosage_instructions', 'dispensed_at',
    ];

    protected $casts = [
        'quantity_dispensed' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'dispensed_at' => 'datetime',
    ];

    public function prescription(): BelongsTo { return $this->belongsTo(Prescription::class); }
    public function drug(): BelongsTo { return $this->belongsTo(Drug::class); }
    public function drugBatch(): BelongsTo { return $this->belongsTo(DrugBatch::class); }
    public function dispensingPoint(): BelongsTo { return $this->belongsTo(DispensingPoint::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
