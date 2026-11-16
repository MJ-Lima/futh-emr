<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Admission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'admission_number', 'hospital_id', 'patient_id', 'encounter_id',
        'ward_id', 'bed_id', 'admitting_doctor_id', 'attending_doctor_id',
        'resident_doctor_id', 'discharged_by',
        'admission_date', 'discharge_date',
        'admission_type', 'admission_source',
        'admission_diagnosis', 'admission_reason', 'clinical_notes',
        'discharge_reason', 'discharge_diagnosis', 'discharge_summary',
        'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'admission_date' => 'datetime',
            'discharge_date' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($a) {
            if (empty($a->created_by) && Auth::check()) $a->created_by = Auth::id();
            if (empty($a->hospital_id) && Auth::check()) $a->hospital_id = Auth::user()->hospital_id;
            if (empty($a->admission_date)) $a->admission_date = now();
            if (empty($a->admission_number)) {
                $prefix = 'ADM';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->admission_number, -6)) + 1 : 1;
                $a->admission_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });

        static::created(function ($a) {
            // Assign the bed to this admission
            if ($a->bed_id) {
                $bed = Bed::find($a->bed_id);
                $bed?->assign($a);
            }
        });

        static::updated(function ($a) {
            // If bed changed, release the old bed and assign the new one
            if ($a->wasChanged('bed_id')) {
                $originalBedId = $a->getOriginal('bed_id');
                if ($originalBedId) {
                    Bed::find($originalBedId)?->release('Cleaning');
                }
                if ($a->bed_id) {
                    Bed::find($a->bed_id)?->assign($a);
                }
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function ward(): BelongsTo { return $this->belongsTo(Ward::class); }
    public function bed(): BelongsTo { return $this->belongsTo(Bed::class); }
    public function admittingDoctor(): BelongsTo { return $this->belongsTo(User::class, 'admitting_doctor_id'); }
    public function attendingDoctor(): BelongsTo { return $this->belongsTo(User::class, 'attending_doctor_id'); }
    public function residentDoctor(): BelongsTo { return $this->belongsTo(User::class, 'resident_doctor_id'); }
    public function dischargedBy(): BelongsTo { return $this->belongsTo(User::class, 'discharged_by'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function transfers(): HasMany { return $this->hasMany(BedTransfer::class); }
    public function residentAdmissionNotes(): HasMany { return $this->hasMany(ResidentAdmissionNote::class); }
    public function dischargeSummary()
    {
        return $this->hasOne(DischargeSummary::class);
    }

    public function lengthOfStayDays(): int
    {
        $end = $this->discharge_date ?? now();
        return (int) $this->admission_date->diffInDays($end);
    }

    public function isActive(): bool
    {
        return $this->status === 'Admitted';
    }

    public function discharge(array $data): ?DischargeSummary
    {
        if ($this->status === 'Discharged') {
            return $this->dischargeSummary;
        }
        $this->update([
            'status' => $data['discharge_type'] === 'Death' ? 'Death' : 'Discharged',
            'discharge_date' => $data['discharge_date'] ?? now(),
        ]);
        if ($this->bed_id) {
            Bed::find($this->bed_id)?->release('Cleaning');
        }
        return DischargeSummary::create(array_merge($data, [
            'admission_id' => $this->id,
            'patient_id'   => $this->patient_id,
        ]));
    }
}
