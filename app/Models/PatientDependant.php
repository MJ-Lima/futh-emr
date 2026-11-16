<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PatientDependant extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'hospital_id', 'principal_patient_id', 'patient_id', 'patient_insurance_id',
        'first_name', 'middle_name', 'last_name', 'date_of_birth', 'gender',
        'relationship', 'national_id', 'national_id_type',
        'photo_path', 'thumbprint_path', 'thumbprint_data',
        'biometric_captured_at', 'biometric_captured_by',
        'verification_status', 'verified_at', 'verified_by',
        'phone', 'email', 'address', 'notes', 'status',
    ];
    protected function casts(): array {
        return [
            'date_of_birth' => 'date', 'biometric_captured_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }
    protected static function booted() {
        static::creating(function ($d) {
            if (empty($d->hospital_id) && Auth::check()) $d->hospital_id = Auth::user()->hospital_id;
        });
    }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function principalPatient(): BelongsTo { return $this->belongsTo(Patient::class, 'principal_patient_id'); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function insurance(): BelongsTo { return $this->belongsTo(PatientInsurance::class, 'patient_insurance_id'); }
    public function capturer(): BelongsTo { return $this->belongsTo(User::class, 'biometric_captured_by'); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }

    public function fullName(): string { return trim("{$this->first_name} {$this->middle_name} {$this->last_name}"); }

    public function verify(?int $verifierId = null): void {
        $this->update([
            'verification_status' => 'Verified',
            'verified_at' => now(),
            'verified_by' => $verifierId ?? Auth::id(),
        ]);
    }
}
