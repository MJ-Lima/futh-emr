<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientReceipt extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'patient_id', 'appointment_id', 'encounter_id',
        'invoice_id', 'receipt_number', 'receipt_date', 'amount',
        'payment_method', 'payment_status', 'status',
        'service_description', 'notes', 'received_by',
    ];

    protected function casts(): array
    {
        return [
            'receipt_date' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function receiver(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
}