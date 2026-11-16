<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Referral extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'patient_id', 'encounter_id', 'admission_id',
        'referred_by', 'referred_to_user_id',
        'referral_number', 'referral_type', 'patient_category',
        'destination_facility', 'destination_department', 'destination_doctor',
        'clinical_summary', 'diagnosis', 'treatment_given',
        'reason_for_referral', 'investigations_done', 'investigations_requested',
        'urgency', 'status', 'referred_date', 'appointment_date',
        'receiving_feedback', 'received_by', 'received_at', 'notes',
    ];

    public const TYPES = [
        'Internal'      => 'Internal (within FUTH Lafia)',
        'External'      => 'External (to another hospital)',
        'Inter-Hospital' => 'Inter-Hospital (FUTH main / Annex / Taimako)',
        'Specialist'    => 'Specialist Clinic',
        'Emergency'     => 'Emergency Referral',
    ];

    public const CATEGORIES = [
        'Outpatient' => 'Outpatient',
        'Inpatient'  => 'Inpatient',
        'Emergency'  => 'Emergency',
    ];

    public const URGENCIES = [
        'Routine' => 'Routine',
        'Urgent'  => 'Urgent',
        'STAT'    => 'STAT',
        'ASAP'    => 'ASAP',
    ];

    public const URGENCY_COLORS = [
        'Routine' => 'gray',
        'Urgent'  => 'warning',
        'STAT'    => 'danger',
        'ASAP'    => 'warning',
    ];

    public const STATUSES = [
        'Pending'   => 'Pending',
        'Accepted'  => 'Accepted',
        'Rejected'  => 'Rejected',
        'Completed' => 'Completed',
        'Cancelled' => 'Cancelled',
    ];

    public const STATUS_COLORS = [
        'Pending'   => 'warning',
        'Accepted'  => 'info',
        'Rejected'  => 'danger',
        'Completed' => 'success',
        'Cancelled' => 'danger',
    ];

    protected $casts = [
        'referred_date'    => 'date',
        'appointment_date' => 'date',
        'received_at'      => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($r) {
            if (empty($r->referred_by) && Auth::check()) {
                $r->referred_by = Auth::id();
            }
            if (empty($r->hospital_id) && Auth::check()) {
                $r->hospital_id = Auth::user()->hospital_id;
            }
            if (empty($r->referred_date)) {
                $r->referred_date = now();
            }
            $year = now()->year;
            $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
            $next = $last ? ((int) substr($last->referral_number, -6)) + 1 : 1;
            $r->referral_number = sprintf('REF-%s-%06d', $year, $next);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function admission(): BelongsTo { return $this->belongsTo(Admission::class); }
    public function referrer(): BelongsTo { return $this->belongsTo(User::class, 'referred_by'); }
    public function referredTo(): BelongsTo { return $this->belongsTo(User::class, 'referred_to_user_id'); }
    public function receiver(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }
}
