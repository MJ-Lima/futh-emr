<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BloodCrossmatch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'crossmatch_number', 'hospital_id', 'blood_request_id', 'blood_unit_id',
        'patient_id', 'performed_by', 'method',
        'major_crossmatch', 'minor_crossmatch',
        'results', 'notes', 'performed_at', 'status',
    ];

    protected function casts(): array
    {
        return ['performed_at' => 'datetime'];
    }

    protected static function booted()
    {
        static::creating(function ($c) {
            if (empty($c->performed_by) && Auth::check()) $c->performed_by = Auth::id();
            if (empty($c->hospital_id) && Auth::check()) $c->hospital_id = Auth::user()->hospital_id;
            if (empty($c->performed_at)) $c->performed_at = now();
            if (empty($c->crossmatch_number)) {
                $prefix = 'BCM';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->crossmatch_number, -6)) + 1 : 1;
                $c->crossmatch_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });

        static::created(function ($c) {
            // If crossmatch is compatible, reserve the blood unit
            if ($c->major_crossmatch === 'Compatible') {
                $c->update(['status' => 'Compatible']);
                $c->bloodUnit->update(['status' => 'Reserved']);
                $c->bloodRequest->markCrossmatched();
            } elseif ($c->major_crossmatch === 'Incompatible') {
                $c->update(['status' => 'Incompatible']);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function bloodRequest(): BelongsTo { return $this->belongsTo(BloodRequest::class); }
    public function bloodUnit(): BelongsTo { return $this->belongsTo(BloodUnit::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function performer(): BelongsTo { return $this->belongsTo(User::class, 'performed_by'); }
}
