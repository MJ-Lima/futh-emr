<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProcedureBooking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_number', 'hospital_id', 'patient_id', 'encounter_id', 'admission_id',
        'procedure_id', 'surgeon_id', 'anaesthetist_id', 'assistant_id',
        'scrub_nurse_id', 'circulating_nurse_id', 'theatre_id',
        'scheduled_start', 'scheduled_end', 'priority', 'booking_status',
        'pre_op_assessment_date', 'consent_signed', 'consent_signed_at',
        'consent_witnessed_by', 'fasting_confirmed', 'investigations_done',
        'anaesthesia_review_done', 'blood_reserved', 'pre_op_notes',
        'cancellation_reason', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_start'        => 'datetime',
            'scheduled_end'          => 'datetime',
            'pre_op_assessment_date' => 'date',
            'consent_signed'         => 'boolean',
            'consent_signed_at'      => 'datetime',
            'fasting_confirmed'      => 'boolean',
            'investigations_done'    => 'boolean',
            'anaesthesia_review_done'=> 'boolean',
            'blood_reserved'         => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($b) {
            if (empty($b->created_by) && Auth::check()) $b->created_by = Auth::id();
            if (empty($b->hospital_id) && Auth::check()) $b->hospital_id = Auth::user()->hospital_id;
            if (empty($b->booking_number)) {
                $prefix = 'PBK';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->booking_number, -6)) + 1 : 1;
                $b->booking_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function admission(): BelongsTo { return $this->belongsTo(Admission::class); }
    public function procedure(): BelongsTo { return $this->belongsTo(Procedure::class); }
    public function surgeon(): BelongsTo { return $this->belongsTo(User::class, 'surgeon_id'); }
    public function anaesthetist(): BelongsTo { return $this->belongsTo(User::class, 'anaesthetist_id'); }
    public function assistant(): BelongsTo { return $this->belongsTo(User::class, 'assistant_id'); }
    public function scrubNurse(): BelongsTo { return $this->belongsTo(User::class, 'scrub_nurse_id'); }
    public function circulatingNurse(): BelongsTo { return $this->belongsTo(User::class, 'circulating_nurse_id'); }
    public function theatre(): BelongsTo { return $this->belongsTo(StoreLocation::class, 'theatre_id'); }
    public function consentWitness(): BelongsTo { return $this->belongsTo(User::class, 'consent_witnessed_by'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function sessions(): HasMany { return $this->hasMany(ProcedureSession::class); }

    public function signConsent(int $witnessId = null): void
    {
        $this->update([
            'consent_signed'        => true,
            'consent_signed_at'     => now(),
            'consent_witnessed_by'  => $witnessId ?? Auth::id(),
        ]);
    }

    public function isPreOpComplete(): bool
    {
        return $this->consent_signed
            && $this->fasting_confirmed
            && $this->investigations_done
            && $this->anaesthesia_review_done;
    }
}
