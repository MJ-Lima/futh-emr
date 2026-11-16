<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Allergy extends Model
{
    protected $fillable = [
        'patient_id', 'recorded_by', 'encounter_id', 'substance', 'code',
        'category', 'criticality', 'severity', 'reaction', 'reaction_description',
        'onset_date', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return ['onset_date' => 'date'];
    }

    protected static function booted()
    {
        static::creating(function ($a) {
            if (empty($a->recorded_by) && Auth::check()) $a->recorded_by = Auth::id();
        });
    }

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function recorder(): BelongsTo { return $this->belongsTo(User::class, 'recorded_by'); }
}
