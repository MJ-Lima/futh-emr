<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BloodRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'request_number', 'hospital_id', 'patient_id', 'encounter_id', 'admission_id',
        'requested_by', 'requested_blood_group', 'component_requested', 'units_requested',
        'urgency', 'required_by', 'clinical_indication', 'diagnosis',
        'patient_hb_level', 'patient_blood_group', 'patient_rh_factor', 'crossmatch_notes',
        'status', 'approved_by', 'approved_at',
        'crossmatched_by', 'crossmatched_at', 'issued_by', 'issued_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'required_by'        => 'datetime',
            'approved_at'        => 'datetime',
            'crossmatched_at'    => 'datetime',
            'issued_at'          => 'datetime',
            'units_requested'    => 'integer',
            'patient_hb_level'   => 'decimal:1',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($r) {
            if (empty($r->requested_by) && Auth::check()) $r->requested_by = Auth::id();
            if (empty($r->hospital_id) && Auth::check()) $r->hospital_id = Auth::user()->hospital_id;
            if (empty($r->request_number)) {
                $prefix = 'BTR';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->request_number, -6)) + 1 : 1;
                $r->request_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function admission(): BelongsTo { return $this->belongsTo(Admission::class); }
    public function requester(): BelongsTo { return $this->belongsTo(User::class, 'requested_by'); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
    public function crossmatcher(): BelongsTo { return $this->belongsTo(User::class, 'crossmatched_by'); }
    public function issuer(): BelongsTo { return $this->belongsTo(User::class, 'issued_by'); }
    public function crossmatches(): HasMany { return $this->hasMany(BloodCrossmatch::class); }
    public function transfusions(): HasMany { return $this->hasMany(BloodTransfusion::class); }

    public function isFulfilled(): bool
    {
        return $this->transfusions()->where('status', 'Completed')->count() >= (int) $this->units_requested;
    }

    public function approve(int $approverId = null): void
    {
        $this->update([
            'status'      => 'Approved',
            'approved_by' => $approverId ?? Auth::id(),
            'approved_at' => now(),
        ]);
    }

    public function markCrossmatched(int $crossmatcherId = null): void
    {
        $this->update([
            'status'           => 'Crossmatched',
            'crossmatched_by'  => $crossmatcherId ?? Auth::id(),
            'crossmatched_at'  => now(),
        ]);
    }

    public function issue(int $issuerId = null): void
    {
        $this->update([
            'status'     => 'Issued',
            'issued_by'  => $issuerId ?? Auth::id(),
            'issued_at'  => now(),
        ]);
    }
}
