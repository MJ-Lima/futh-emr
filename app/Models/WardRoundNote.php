<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class WardRoundNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'admission_id', 'patient_id', 'encounter_id', 'ward_id', 'bed_id',
        'doctor_id', 'note_number', 'round_date', 'round_time', 'round_type',
        'subjective', 'objective', 'assessment', 'plan',
        'temperature', 'systolic_bp', 'diastolic_bp', 'heart_rate',
        'respiratory_rate', 'spo2', 'blood_glucose', 'weight_kg',
        'investigations_ordered', 'medications_changes', 'plan_for_discharge',
        'doctor_notes', 'nurse_action_required',
    ];

    public const ROUND_TYPES = [
        'Morning' => 'Morning Round',
        'Evening' => 'Evening Round',
        'Night' => 'Night Round',
        'Consultant Ward Round' => 'Consultant Ward Round',
        'Emergency Review' => 'Emergency Review',
        'Handover' => 'Handover / Sign-out',
    ];

    protected $casts = [
        'round_date' => 'date',
        'temperature' => 'decimal:2',
        'blood_glucose' => 'decimal:2',
        'weight_kg' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($note) {
            if (empty($note->doctor_id) && Auth::check()) {
                $note->doctor_id = Auth::id();
            }
            if (empty($note->hospital_id) && Auth::check()) {
                $note->hospital_id = Auth::user()->hospital_id;
            }
            if (empty($note->hospital_id)) {
                $note->hospital_id = 1;
            }
            if (empty($note->round_date)) {
                $note->round_date = today();
            }
            if (empty($note->round_time)) {
                $note->round_time = now()->format('H:i:s');
            }
            $year = now()->year;
            $last = self::whereYear('created_at', $year)->latest('id')->first();
            $next = $last ? ((int) substr($last->note_number, -6)) + 1 : 1;
            $note->note_number = sprintf('WRN-%s-%06d', $year, $next);
        });
    }

    /* ---------- Relationships ---------- */

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function admission(): BelongsTo { return $this->belongsTo(Admission::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function ward(): BelongsTo { return $this->belongsTo(Ward::class); }
    public function bed(): BelongsTo { return $this->belongsTo(Bed::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(User::class, 'doctor_id'); }
}
