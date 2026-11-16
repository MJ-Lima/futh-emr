<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_no', 'patient_type', 'hospital_id', 'clinic_id', 'firstname', 'middlename', 'lastname', 'dob',
        'gender', 'bloodgroup', 'genotype', 'marital', 'religion', 'occupation',
        'phonemain', 'phonealt', 'email', 'address', 'city', 'state', 'lga', 'country',
        'postalcode', 'nokname', 'nok_firstname', 'nok_middlename', 'relationship', 'nokphone', 'nokaddress',
        'hereabout', 'notes', 'status', 'photo_path', 'thumbprint_path', 'thumbprint_data',
        'biometric_captured_at', 'biometric_captured_by', 'verification_status', 'verified_at', 'verified_by',
        'national_id', 'national_id_type',
    ];

    protected function casts(): array
    {
        return [
            'dob'                   => 'date',
            'status'                => 'boolean',
            'biometric_captured_at' => 'datetime',
            'verified_at'           => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($patient) {
            if (empty($patient->hospital_id) && Auth::check()) {
                $patient->hospital_id = Auth::user()->hospital_id;
            }
            $hospital = Hospital::find($patient->hospital_id);
            $prefix   = $hospital?->code ?? 'FUTH';
            $year     = now()->year;

            $lastPatient = self::withTrashed()
                ->where('hospital_id', $patient->hospital_id)
                ->whereYear('created_at', $year)
                ->latest('id')
                ->first();

            $next = $lastPatient
                ? ((int) substr($lastPatient->hospital_no, -6)) + 1
                : 1;

            $patient->hospital_no = sprintf('%s-%s-%06d', $prefix, $year, $next);
        });
    }

    /* ---- Relations ---- */
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function clinic(): BelongsTo { return $this->belongsTo(Clinic::class); }
    public function encounters(): HasMany { return $this->hasMany(Encounter::class); }
    public function appointments(): HasMany { return $this->hasMany(Appointment::class); }
    public function vitals(): HasMany { return $this->hasMany(Vitals::class); }
    public function clinicalNotes(): HasMany { return $this->hasMany(ClinicalNote::class); }
    public function allergies(): HasMany { return $this->hasMany(Allergy::class); }
    public function diagnoses(): HasMany { return $this->hasMany(Diagnosis::class); }
    public function prescriptions(): HasMany { return $this->hasMany(Prescription::class); }
    public function labOrders(): HasMany { return $this->hasMany(LabOrder::class); }
    public function radiologyOrders(): HasMany { return $this->hasMany(RadiologyOrder::class); }
    public function insurancePolicies(): HasMany { return $this->hasMany(PatientInsurance::class); }
    public function insuranceClaims(): HasMany { return $this->hasMany(InsuranceClaim::class); }
    public function preAuthorizations(): HasMany { return $this->hasMany(PreAuthorization::class); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
    public function dependants(): HasMany { return $this->hasMany(PatientDependant::class, 'principal_patient_id'); }
    public function relationships(): HasMany { return $this->hasMany(PatientRelationship::class); }
    public function biometricCapturer(): BelongsTo { return $this->belongsTo(User::class, 'biometric_captured_by'); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }
    public function isVerified(): bool { return $this->verification_status === 'Verified'; }
    public function verify(?int $verifierId = null): void { $this->update(['verification_status' => 'Verified', 'verified_at' => now(), 'verified_by' => $verifierId ?? Auth::id()]); }

    /* ---- Accessors (for Blade & Filament) ---- */
    public function getFullNameAttribute(): string
    {
        return trim(
            implode(' ', array_filter([
                $this->firstname,
                $this->middlename,
                $this->lastname,
            ]))
        );
    }

    public function getAgeAttribute(): ?string
    {
        if (! $this->dob) {
            return null;
        }
        $age = $this->dob->age;
        return (string) $age . ' ' . ($age === 1 ? 'yr' : 'yrs');
    }

    public function getPatientcodeAttribute(): ?string
    {
        return $this->hospital_no;
    }

    public function getMrnAttribute(): ?string
    {
        return $this->hospital_no;
    }
}