<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceProvider extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'name', 'code', 'short_name', 'type', 'nhia_code',
        'contact_person', 'phone', 'email', 'address',
        'bank_account_number', 'bank_name',
        'capitation_rate', 'discount_percentage', 'claim_settlement_days',
        'logo_path', 'status',
    ];

    protected function casts(): array
    {
        return [
            'capitation_rate'         => 'decimal:2',
            'discount_percentage'     => 'decimal:2',
            'claim_settlement_days'   => 'integer',
            'status'                  => 'string',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function plans(): HasMany { return $this->hasMany(InsurancePlan::class); }
    public function policies(): HasMany { return $this->hasMany(PatientInsurance::class); }
    public function claims(): HasMany { return $this->hasMany(InsuranceClaim::class); }
    public function preAuthorizations(): HasMany { return $this->hasMany(PreAuthorization::class); }
}
