<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BedTransfer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transfer_number', 'admission_id', 'patient_id',
        'from_ward_id', 'from_bed_id', 'to_ward_id', 'to_bed_id',
        'transfer_date', 'reason', 'authorized_by',
    ];

    protected function casts(): array
    {
        return ['transfer_date' => 'datetime'];
    }

    protected static function booted()
    {
        static::creating(function ($t) {
            if (empty($t->authorized_by) && Auth::check()) $t->authorized_by = Auth::id();
            if (empty($t->transfer_date)) $t->transfer_date = now();
            if (empty($t->transfer_number)) {
                $prefix = 'TRF';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->transfer_number, -6)) + 1 : 1;
                $t->transfer_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });

        static::created(function ($t) {
            // Release the old bed
            if ($t->from_bed_id) {
                Bed::find($t->from_bed_id)?->release('Cleaning');
            }
            // Assign the new bed to the admission
            $admission = $t->admission;
            if ($admission && $t->to_bed_id) {
                $admission->update([
                    'ward_id' => $t->to_ward_id,
                    'bed_id'  => $t->to_bed_id,
                ]);
                // The Admission::updated event will handle the bed assignment
                // but we need to manually trigger it because update() doesn't
                // fire updated if values are unchanged.
                Bed::find($t->to_bed_id)?->assign($admission);
            }
        });
    }

    public function admission(): BelongsTo { return $this->belongsTo(Admission::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function fromWard(): BelongsTo { return $this->belongsTo(Ward::class, 'from_ward_id'); }
    public function fromBed(): BelongsTo { return $this->belongsTo(Bed::class, 'from_bed_id'); }
    public function toWard(): BelongsTo { return $this->belongsTo(Ward::class, 'to_ward_id'); }
    public function toBed(): BelongsTo { return $this->belongsTo(Bed::class, 'to_bed_id'); }
    public function authorizer(): BelongsTo { return $this->belongsTo(User::class, 'authorized_by'); }
}
