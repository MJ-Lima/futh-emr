<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Encounter extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'patient_id', 'clinic_id', 'user_id', 'encounter_number',
        'visit_type', 'encounter_date', 'encounter_time', 'status',
        'chief_complaint', 'referred_by', 'appointment_id', 'created_by', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'encounter_date' => 'date',
            'encounter_time' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($enc) {
            if (empty($enc->hospital_id) && Auth::check()) {
                $enc->hospital_id = Auth::user()->hospital_id;
            }
            if (empty($enc->created_by) && Auth::check()) {
                $enc->created_by = Auth::id();
            }
            $hospital = Hospital::find($enc->hospital_id);
            $prefix   = $hospital?->code ?? 'FUTH';
            $year     = now()->year;
            $last     = self::withTrashed()
                ->where('hospital_id', $enc->hospital_id)
                ->whereYear('created_at', $year)
                ->latest('id')->first();
            $next = $last ? ((int) substr($last->encounter_number, -6)) + 1 : 1;
            $enc->encounter_number = sprintf('%s-ENC-%s-%06d', $prefix, $year, $next);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function clinic(): BelongsTo { return $this->belongsTo(Clinic::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function vitals(): HasMany { return $this->hasMany(Vitals::class); }
    public function clinicalNotes(): HasMany { return $this->hasMany(ClinicalNote::class); }
    public function allergies(): HasMany { return $this->hasMany(Allergy::class); }
    public function diagnoses(): HasMany { return $this->hasMany(Diagnosis::class); }
    public function prescriptions(): HasMany { return $this->hasMany(Prescription::class); }
    public function labOrders(): HasMany { return $this->hasMany(LabOrder::class); }
    public function radiologyOrders(): HasMany { return $this->hasMany(RadiologyOrder::class); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
    public function insuranceClaims(): HasMany { return $this->hasMany(InsuranceClaim::class); }

    // — Clinic-specific consultation workflow —
    public function presentingComplaints(): BelongsToMany
    {
        return $this->belongsToMany(PresentingComplaint::class, 'encounter_presenting_complaints')
            ->withPivot(['duration', 'description'])
            ->withTimestamps();
    }

    public function consultationNotes(): HasMany
    {
        return $this->hasMany(ConsultationNote::class);
    }

    public function residentAdmissionNotes(): HasMany
    {
        return $this->hasMany(ResidentAdmissionNote::class);
    }
}
