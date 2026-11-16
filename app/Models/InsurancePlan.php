<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsurancePlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'insurance_provider_id', 'name', 'code', 'tier', 'coverage_type',
        'annual_premium', 'annual_benefit_limit', 'per_encounter_limit',
        'outpatient_deduction', 'inpatient_deduction', 'copay_percentage',
        'covered_services', 'excluded_services',
        'covers_pharmacy', 'covers_lab', 'covers_radiology', 'covers_inpatient',
        'covers_maternity', 'covers_dental', 'covers_optical',
        'description', 'status',
    ];

    protected function casts(): array
    {
        return [
            'annual_premium'         => 'decimal:2',
            'annual_benefit_limit'   => 'decimal:2',
            'per_encounter_limit'    => 'decimal:2',
            'outpatient_deduction'   => 'decimal:2',
            'inpatient_deduction'    => 'decimal:2',
            'copay_percentage'       => 'decimal:2',
            'covered_services'       => 'array',
            'excluded_services'      => 'array',
            'covers_pharmacy'        => 'boolean',
            'covers_lab'             => 'boolean',
            'covers_radiology'       => 'boolean',
            'covers_inpatient'       => 'boolean',
            'covers_maternity'       => 'boolean',
            'covers_dental'          => 'boolean',
            'covers_optical'         => 'boolean',
        ];
    }

    public function provider(): BelongsTo { return $this->belongsTo(InsuranceProvider::class, 'insurance_provider_id'); }
    public function policies(): HasMany { return $this->hasMany(PatientInsurance::class); }
    public function claims(): HasMany { return $this->hasMany(InsuranceClaim::class); }
}
