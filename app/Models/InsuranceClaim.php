<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class InsuranceClaim extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'claim_number', 'hospital_id', 'patient_id', 'patient_insurance_id',
        'insurance_provider_id', 'insurance_plan_id', 'encounter_id',
        'service_date_start', 'service_date_end', 'claim_type',
        'diagnosis_icd10', 'diagnosis_description', 'treatment_summary',
        'total_claimed', 'total_approved', 'total_paid', 'patient_copay', 'deduction',
        'pre_authorization_code', 'status',
        'submitted_at', 'acknowledged_at', 'processed_at', 'paid_at',
        'created_by', 'submitted_by', 'rejection_reason', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'service_date_start' => 'date',
            'service_date_end'   => 'date',
            'submitted_at'       => 'datetime',
            'acknowledged_at'    => 'datetime',
            'processed_at'       => 'datetime',
            'paid_at'            => 'datetime',
            'total_claimed'      => 'decimal:2',
            'total_approved'     => 'decimal:2',
            'total_paid'         => 'decimal:2',
            'patient_copay'      => 'decimal:2',
            'deduction'          => 'decimal:2',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($c) {
            if (empty($c->created_by) && Auth::check()) $c->created_by = Auth::id();
            if (empty($c->hospital_id) && Auth::check()) $c->hospital_id = Auth::user()->hospital_id;
            if (empty($c->claim_number)) {
                $prefix = 'CLM';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->claim_number, -6)) + 1 : 1;
                $c->claim_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function policy(): BelongsTo { return $this->belongsTo(PatientInsurance::class, 'patient_insurance_id'); }
    public function provider(): BelongsTo { return $this->belongsTo(InsuranceProvider::class, 'insurance_provider_id'); }
    public function plan(): BelongsTo { return $this->belongsTo(InsurancePlan::class, 'insurance_plan_id'); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function submitter(): BelongsTo { return $this->belongsTo(User::class, 'submitted_by'); }
    public function lines(): HasMany { return $this->hasMany(InsuranceClaimLine::class); }

    public function recalculateTotals(): void
    {
        $this->total_claimed  = $this->lines()->sum('amount_claimed');
        $this->total_approved = $this->lines()->sum('amount_approved');
        $this->total_paid     = $this->lines()->sum('amount_paid');
        $this->patient_copay  = $this->lines()->sum('patient_copay');
        $this->save();
    }
}
