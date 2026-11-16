<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProcedureSession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'session_number', 'procedure_booking_id', 'patient_id', 'procedure_id',
        'surgeon_id', 'anaesthetist_id', 'theatre_id',
        'actual_start', 'actual_end', 'duration_minutes',
        'anaesthesia_used', 'anaesthesia_notes',
        'findings_intra_op', 'procedure_performed', 'steps_taken',
        'complications_intra_op', 'blood_loss_ml',
        'specimens_sent', 'implants_used', 'drains_placed', 'dressing_applied',
        'post_op_instructions', 'outcome', 'notes',
        'is_signed', 'signed_at', 'signed_by', 'status',
    ];

    protected function casts(): array
    {
        return [
            'actual_start' => 'datetime',
            'actual_end'   => 'datetime',
            'duration_minutes' => 'integer',
            'is_signed'    => 'boolean',
            'signed_at'    => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($s) {
            if (empty($s->session_number)) {
                $prefix = 'PSN';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->session_number, -6)) + 1 : 1;
                $s->session_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });

        static::saved(function ($s) {
            if ($s->actual_start && $s->actual_end) {
                $duration = $s->actual_start->diffInMinutes($s->actual_end);
                \Illuminate\Support\Facades\DB::table('procedure_sessions')->where('id', $s->id)
                    ->update(['duration_minutes' => $duration]);
            }
        });
    }

    public function booking(): BelongsTo { return $this->belongsTo(ProcedureBooking::class, 'procedure_booking_id'); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function procedure(): BelongsTo { return $this->belongsTo(Procedure::class); }
    public function surgeon(): BelongsTo { return $this->belongsTo(User::class, 'surgeon_id'); }
    public function anaesthetist(): BelongsTo { return $this->belongsTo(User::class, 'anaesthetist_id'); }
    public function theatre(): BelongsTo { return $this->belongsTo(StoreLocation::class, 'theatre_id'); }
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
