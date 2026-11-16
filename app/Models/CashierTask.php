<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property-read Vitals|Encounter|Appointment|Invoice|LabOrder|RadiologyOrder|Prescription|null $source_record
 */
class CashierTask extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'task_number', 'hospital_id', 'patient_id', 'encounter_id', 'appointment_id',
        'source_type', 'source_id', 'description', 'amount_due', 'amount_paid',
        'status', 'assigned_cashier_id', 'assigned_at', 'paid_at', 'payment_method',
        'receipt_number', 'invoice_id', 'patient_receipt_id', 'notes',
    ];

    protected $casts = [
        'amount_due'   => 'decimal:2',
        'amount_paid'  => 'decimal:2',
        'assigned_at'  => 'datetime',
        'paid_at'       => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (self $task) {
            if (empty($task->task_number)) {
                $year = now()->format('Y');
                do {
                    $task->task_number = 'CST-' . $year . '-' . strtoupper(Str::random(8));
                } while (self::withTrashed()->where('task_number', $task->task_number)->exists());
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function assignedCashier(): BelongsTo { return $this->belongsTo(User::class, 'assigned_cashier_id'); }
    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function patientReceipt(): BelongsTo { return $this->belongsTo(PatientReceipt::class); }

    public function isPending(): bool { return $this->status === 'pending'; }
    public function isPaid(): bool { return in_array($this->status, ['paid', 'short_paid']); }

    /** Polymorphic-ish accessor for the source record. */
    public function getSourceRecordAttribute()
    {
        if (! $this->source_type || ! $this->source_id) {
            return null;
        }
        $classMap = [
            'Invoice'            => Invoice::class,
            'LabOrder'           => LabOrder::class,
            'RadiologyOrder'     => RadiologyOrder::class,
            'Prescription'       => Prescription::class,
            'AppointmentCardFee' => Appointment::class,
            'Consultation'       => Encounter::class,
            'NursingService'     => Vitals::class,
        ];
        $class = $classMap[$this->source_type] ?? null;
        return $class ? $class::find($this->source_id) : null;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
