<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientInsurance extends Model
{
    protected $table = 'patient_insurance';

    protected $fillable = [
        'patient_id', 'insurance_provider_id', 'insurance_plan_id',
        'policy_number', 'member_id', 'enrollee_code',
        'principal_relationship', 'principal_name', 'principal_dob',
        'principal_phone', 'principal_nin',
        'enrollment_date', 'effective_date', 'expiry_date',
        'remaining_benefit', 'utilized_benefit', 'is_primary',
        'verification_status', 'verified_at', 'verified_by',
        'notes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'enrollment_date' => 'date',
            'effective_date'  => 'date',
            'expiry_date'     => 'date',
            'verified_at'     => 'datetime',
            'remaining_benefit' => 'decimal:2',
            'utilized_benefit'  => 'decimal:2',
            'is_primary'        => 'boolean',
        ];
    }

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function provider(): BelongsTo { return $this->belongsTo(InsuranceProvider::class, 'insurance_provider_id'); }
    public function plan(): BelongsTo { return $this->belongsTo(InsurancePlan::class, 'insurance_plan_id'); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }
    public function claims(): HasMany { return $this->hasMany(InsuranceClaim::class); }
    public function preAuthorizations(): HasMany { return $this->hasMany(PreAuthorization::class); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }

    public function isActive(): bool
    {
        return $this->status === 'Active'
            && $this->verification_status === 'Verified'
            && $this->effective_date?->lte(now())
            && $this->expiry_date?->gte(now());
    }

    public function remainingAfter(float $amount): float
    {
        return max(0, (float) $this->remaining_benefit - $amount);
    }
}
