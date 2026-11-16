<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'patient_id', 'clinic_id', 'provider_id', 'appointment_number',
        'appointment_date', 'appointment_time', 'estimated_duration', 'visit_type',
        'priority', 'reason_for_visit', 'status', 'encounter_id', 'created_by',
        'notes', 'cancellation_reason', 'queue_number',
        'checked_in_at', 'consultation_started_at', 'consultation_ended_at',
        // Card fee (new patients only)
        'card_fee_paid', 'card_fee_amount', 'card_fee_payment_method',
        'card_fee_receipt_no', 'card_fee_received_by', 'card_fee_paid_at',
        // Nurse workflow
        'vitals_status', 'nurse_id', 'vitals_taken_at',
        // Consultation room allocation
        'consultation_room_id', 'room_queue_position', 'room_allocated_at',
    ];

    public const STATUSES = [
        'Scheduled'  => 'Scheduled',
        'Confirmed'  => 'Confirmed',
        'Checked In' => 'Checked In',
        'In Nurse Queue' => 'In Nurse Queue',
        'Vitals Taken' => 'Vitals Taken',
        'Waiting for Doctor' => 'Waiting for Doctor',
        'In Consultation' => 'In Consultation',
        'Completed' => 'Completed',
        'Cancelled' => 'Cancelled',
        'No Show' => 'No Show',
    ];

    public const STATUS_COLORS = [
        'Scheduled'  => 'gray',
        'Confirmed'  => 'info',
        'Checked In' => 'info',
        'In Nurse Queue' => 'warning',
        'Vitals Taken' => 'success',
        'Waiting for Doctor' => 'warning',
        'In Consultation' => 'success',
        'Completed' => 'success',
        'Cancelled' => 'danger',
        'No Show' => 'danger',
    ];

    public const VITALS_STATUSES = [
        'Pending'     => 'Pending',
        'In Progress' => 'In Progress',
        'Completed'   => 'Completed',
        'Skipped'     => 'Skipped',
    ];

    public const VITALS_STATUS_COLORS = [
        'Pending'     => 'warning',
        'In Progress' => 'info',
        'Completed'   => 'success',
        'Skipped'     => 'gray',
    ];

    public const VISIT_TYPES = [
        'New'         => 'New Patient',
        'Follow-up'   => 'Follow-up',
        'Review'      => 'Review',
        'Telemedicine' => 'Telemedicine',
    ];

    public const PAYMENT_METHODS = [
        'Cash'         => 'Cash',
        'Card'         => 'Debit/Credit Card',
        'Bank Transfer' => 'Bank Transfer',
        'Mobile Money' => 'Mobile Money',
        'Insurance'    => 'Insurance',
        'NHIA'         => 'NHIA',
        'Free'         => 'Free / Waived',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'appointment_time' => 'datetime',
            'checked_in_at' => 'datetime',
            'consultation_started_at' => 'datetime',
            'consultation_ended_at' => 'datetime',
            'card_fee_paid' => 'boolean',
            'card_fee_paid_at' => 'datetime',
            'card_fee_amount' => 'decimal:2',
            'vitals_taken_at' => 'datetime',
            'room_allocated_at' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($appt) {
            if (empty($appt->hospital_id) && Auth::check()) {
                $appt->hospital_id = Auth::user()->hospital_id;
            }
            if (empty($appt->created_by) && Auth::check()) {
                $appt->created_by = Auth::id();
            }
            if (empty($appt->created_by)) {
                // CLI / seeder fallback — assign to admin user
                $appt->created_by = 1;
            }
            if (empty($appt->hospital_id)) {
                $appt->hospital_id = 1;
            }
            $hospital = Hospital::find($appt->hospital_id);
            $prefix   = $hospital?->code ?? 'FUTH';
            $year     = now()->year;
            $last     = self::where('hospital_id', $appt->hospital_id)
                ->whereYear('created_at', $year)->latest('id')->first();
            $next = $last ? ((int) substr($last->appointment_number, -6)) + 1 : 1;
            $appt->appointment_number = sprintf('%s-APT-%s-%06d', $prefix, $year, $next);
        });
    }

    /* ---------- Relationships ---------- */

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function clinic(): BelongsTo { return $this->belongsTo(Clinic::class); }
    public function provider(): BelongsTo { return $this->belongsTo(User::class, 'provider_id'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function cardFeeReceiver(): BelongsTo { return $this->belongsTo(User::class, 'card_fee_received_by'); }
    public function nurse(): BelongsTo { return $this->belongsTo(User::class, 'nurse_id'); }
    public function consultationRoom(): BelongsTo { return $this->belongsTo(ConsultationRoom::class); }

    /* ---------- Helpers ---------- */

    /**
     * Does this appointment require a card fee?
     * Only NEW patients pay the card fee; follow-ups, reviews, telemedicine don't.
     */
    public function getRequiresCardFeeAttribute(): bool
    {
        return $this->visit_type === 'New';
    }

    /**
     * Is this appointment ready to enter the nurse queue?
     * Requires: Confirmed or Checked In, and (card_fee_paid OR no card fee required)
     */
    public function getReadyForNurseQueueAttribute(): bool
    {
        if (!in_array($this->status, ['Confirmed', 'Checked In'])) {
            return false;
        }
        if ($this->requires_card_fee && !$this->card_fee_paid) {
            return false;
        }
        return true;
    }

    /**
     * Generate a queue number like "Q-0012" for this clinic for today.
     */
    public static function generateQueueNumber(int $clinicId, ?int $hospitalId = null): string
    {
        $today = now()->toDateString();
        $last = self::where('clinic_id', $clinicId)
            ->where('appointment_date', $today)
            ->whereNotNull('queue_number')
            ->latest('id')->first();
        $next = $last ? ((int) ltrim(str_replace('Q-', '', $last->queue_number), '0')) + 1 : 1;
        return 'Q-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
