<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PreAuthorization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'authorization_number', 'hospital_id', 'patient_id', 'patient_insurance_id',
        'insurance_provider_id', 'encounter_id', 'requested_by',
        'service_type', 'clinical_justification',
        'diagnosis_icd10', 'diagnosis_description',
        'estimated_cost', 'approved_amount',
        'requested_date', 'required_by_date', 'authorized_date', 'valid_until',
        'priority', 'status', 'provider_response', 'rejection_reason',
        'processed_by',
    ];

    protected function casts(): array
    {
        return [
            'requested_date'    => 'date',
            'required_by_date'  => 'date',
            'authorized_date'   => 'date',
            'valid_until'       => 'date',
            'estimated_cost'    => 'decimal:2',
            'approved_amount'   => 'decimal:2',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($p) {
            if (empty($p->requested_by) && Auth::check()) $p->requested_by = Auth::id();
            if (empty($p->hospital_id) && Auth::check()) $p->hospital_id = Auth::user()->hospital_id;
            if (empty($p->requested_date)) $p->requested_date = now();
            if (empty($p->authorization_number)) {
                $prefix = 'PA';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->authorization_number, -6)) + 1 : 1;
                $p->authorization_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function policy(): BelongsTo { return $this->belongsTo(PatientInsurance::class, 'patient_insurance_id'); }
    public function provider(): BelongsTo { return $this->belongsTo(InsuranceProvider::class, 'insurance_provider_id'); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function requester(): BelongsTo { return $this->belongsTo(User::class, 'requested_by'); }
    public function processor(): BelongsTo { return $this->belongsTo(User::class, 'processed_by'); }
}
