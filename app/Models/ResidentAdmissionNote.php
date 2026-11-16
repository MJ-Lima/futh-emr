<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ResidentAdmissionNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'admission_id', 'patient_id', 'encounter_id',
        'resident_doctor_id', 'supervising_consultant_id',
        'note_number',
        'presenting_complaint', 'history_presenting_illness',
        'past_medical_history', 'drug_history', 'family_history', 'social_history',
        'physical_examination', 'systemic_review',
        'provisional_diagnosis', 'differential_diagnosis',
        'investigations_ordered', 'management_plan', 'resident_notes',
        'consultant_feedback',
        'status', 'submitted_at', 'countersigned_at',
    ];

    public const STATUSES = [
        'Draft' => 'Draft',
        'Submitted' => 'Submitted to Consultant',
        'Counter-signed' => 'Counter-signed',
        'Amended' => 'Amended',
    ];

    public const STATUS_COLORS = [
        'Draft' => 'warning',
        'Submitted' => 'info',
        'Counter-signed' => 'success',
        'Amended' => 'gray',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'countersigned_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($note) {
            if (empty($note->resident_doctor_id) && Auth::check()) {
                $note->resident_doctor_id = Auth::id();
            }
            if (empty($note->hospital_id) && Auth::check()) {
                $note->hospital_id = Auth::user()->hospital_id;
            }
            if (empty($note->hospital_id)) {
                $note->hospital_id = 1;
            }
            $year = now()->year;
            $last = self::whereYear('created_at', $year)->latest('id')->first();
            $next = $last ? ((int) substr($last->note_number, -6)) + 1 : 1;
            $note->note_number = sprintf('RAN-%s-%06d', $year, $next);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function admission(): BelongsTo { return $this->belongsTo(Admission::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function residentDoctor(): BelongsTo { return $this->belongsTo(User::class, 'resident_doctor_id'); }
    public function supervisingConsultant(): BelongsTo { return $this->belongsTo(User::class, 'supervising_consultant_id'); }
}
