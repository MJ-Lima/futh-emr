<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procedure extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'procedure_category_id', 'service_id',
        'name', 'code', 'icd10_pcs_code', 'cpt_code',
        'type', 'site', 'anaesthesia_type',
        'estimated_duration_minutes', 'default_pre_op_days', 'default_post_op_days',
        'description', 'indication', 'contraindication',
        'pre_op_instructions', 'post_op_instructions', 'complications',
        'standard_fee', 'nhia_rate', 'private_rate',
        'surgeon_fee', 'anaesthetist_fee', 'theatre_fee', 'consumables_fee',
        'requires_pre_authorization', 'requires_blood_reservation', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'estimated_duration_minutes'    => 'integer',
            'default_pre_op_days'           => 'integer',
            'default_post_op_days'          => 'integer',
            'standard_fee'                  => 'decimal:2',
            'nhia_rate'                     => 'decimal:2',
            'private_rate'                  => 'decimal:2',
            'surgeon_fee'                   => 'decimal:2',
            'anaesthetist_fee'              => 'decimal:2',
            'theatre_fee'                   => 'decimal:2',
            'consumables_fee'               => 'decimal:2',
            'requires_pre_authorization'    => 'boolean',
            'requires_blood_reservation'    => 'boolean',
            'is_active'                     => 'boolean',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function category(): BelongsTo { return $this->belongsTo(ProcedureCategory::class, 'procedure_category_id'); }
    public function service(): BelongsTo { return $this->belongsTo(Service::class); }
    public function bookings(): HasMany { return $this->hasMany(ProcedureBooking::class); }

    public function totalFee(): float
    {
        return (float) $this->standard_fee
             + (float) $this->surgeon_fee
             + (float) $this->anaesthetist_fee
             + (float) $this->theatre_fee
             + (float) $this->consumables_fee;
    }
}
