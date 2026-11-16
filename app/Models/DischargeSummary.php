<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DischargeSummary extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'admission_id', 'patient_id', 'discharging_doctor_id', 'discharge_date',
        'discharge_type', 'final_diagnosis', 'comorbidities', 'procedures_performed',
        'investigations_summary', 'treatment_summary', 'treatment_given',
        'condition_on_discharge', 'medications_on_discharge',
        'follow_up_instructions', 'follow_up_date',
        'death_cause', 'death_time', 'notes',
        'is_signed', 'signed_at', 'signed_by',
    ];

    protected function casts(): array
    {
        return [
            'discharge_date'  => 'datetime',
            'follow_up_date'  => 'date',
            'death_time'      => 'datetime',
            'signed_at'       => 'datetime',
            'is_signed'       => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($d) {
            if (empty($d->discharge_date)) $d->discharge_date = now();
        });
    }

    public function admission(): BelongsTo { return $this->belongsTo(Admission::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function dischargingDoctor(): BelongsTo { return $this->belongsTo(User::class, 'discharging_doctor_id'); }
    public function signer(): BelongsTo { return $this->belongsTo(User::class, 'signed_by'); }

    public function sign(): bool
    {
        if ($this->is_signed) return false;
        $this->update([
            'is_signed' => true,
            'signed_at' => now(),
            'signed_by' => Auth::id(),
        ]);
        return true;
    }
}
