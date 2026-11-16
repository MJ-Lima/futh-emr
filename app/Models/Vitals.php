<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Vitals extends Model
{
    protected $table = 'vitals';

    protected $fillable = [
        'hospital_id', 'patient_id', 'encounter_id', 'recorded_by', 'recorded_at',
        'height_cm', 'weight_kg', 'bmi', 'muac_cm', 'systolic_bp', 'diastolic_bp',
        'heart_rate', 'bp_position', 'respiratory_rate', 'spo2', 'temperature_c',
        'temperature_route', 'pain_score', 'glasgow_coma_score',
        'blood_glucose_mmol_l', 'glucose_fasting', 'fetal_heart_rate',
        'fundal_height_cm', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at'          => 'datetime',
            'height_cm'            => 'decimal:2',
            'weight_kg'            => 'decimal:2',
            'bmi'                  => 'decimal:2',
            'muac_cm'              => 'decimal:2',
            'temperature_c'        => 'decimal:2',
            'blood_glucose_mmol_l' => 'decimal:2',
            'fundal_height_cm'     => 'decimal:2',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($v) {
            if (empty($v->recorded_by) && Auth::check()) $v->recorded_by = Auth::id();
            if (empty($v->recorded_at)) $v->recorded_at = now();
            if (empty($v->hospital_id) && Auth::check()) $v->hospital_id = Auth::user()->hospital_id;
            if ($v->height_cm && $v->weight_kg && !$v->bmi) {
                $h = $v->height_cm / 100;
                $v->bmi = $h > 0 ? round($v->weight_kg / ($h * $h), 2) : null;
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function recorder(): BelongsTo { return $this->belongsTo(User::class, 'recorded_by'); }

   public function getBpLabelAttribute(): string
{
    return "{$this->systolic}/{$this->diastolic}";
        return "{$this->systolic_bp}/{$this->diastolic_bp} mmHg";
    }

    public function bmiCategory(): string
    {
        if (!$this->bmi) return '—';
        if ($this->bmi < 18.5) return 'Underweight';
        if ($this->bmi < 25)   return 'Normal';
        if ($this->bmi < 30)   return 'Overweight';
        return 'Obese';
    }
}
