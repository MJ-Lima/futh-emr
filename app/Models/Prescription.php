<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Prescription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'patient_id', 'encounter_id', 'prescriber_id',
        'dispensing_point_id', 'prescription_number', 'prescribed_at',
        'dispensed_at', 'dispensed_by', 'status', 'priority', 'is_inpatient', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'prescribed_at' => 'datetime',
            'dispensed_at'  => 'datetime',
            'is_inpatient'  => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($p) {
            if (empty($p->prescriber_id) && Auth::check()) $p->prescriber_id = Auth::id();
            if (empty($p->hospital_id) && Auth::check()) $p->hospital_id = Auth::user()->hospital_id;
            if (empty($p->prescribed_at)) $p->prescribed_at = now();
            $prefix = 'RX';
            $year = now()->year;
            $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
            $next = $last ? ((int) substr($last->prescription_number, -6)) + 1 : 1;
            $p->prescription_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function prescriber(): BelongsTo { return $this->belongsTo(User::class, 'prescriber_id'); }
    public function dispensingPoint(): BelongsTo { return $this->belongsTo(DispensingPoint::class); }
    public function dispenser(): BelongsTo { return $this->belongsTo(User::class, 'dispensed_by'); }
    public function items(): HasMany { return $this->hasMany(PrescriptionItem::class); }
}
