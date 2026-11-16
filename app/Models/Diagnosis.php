<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Diagnosis extends Model
{
    protected $fillable = [
        'patient_id', 'encounter_id', 'recorded_by', 'icd10_code',
        'description', 'type', 'status', 'onset_date', 'resolved_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'onset_date'   => 'date',
            'resolved_date' => 'date',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($d) {
            if (empty($d->recorded_by) && Auth::check()) $d->recorded_by = Auth::id();
        });
    }

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function recorder(): BelongsTo { return $this->belongsTo(User::class, 'recorded_by'); }
}
