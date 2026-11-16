<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class RadiologyOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'patient_id', 'encounter_id', 'modality_id',
        'requested_by', 'received_by', 'order_number', 'ordered_at',
        'performed_at', 'resulted_at', 'examination', 'body_part',
        'clinical_indication', 'request_notes', 'priority', 'status',
    ];

    protected function casts(): array
    {
        return [
            'ordered_at'   => 'datetime',
            'performed_at' => 'datetime',
            'resulted_at'  => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($o) {
            if (empty($o->requested_by) && Auth::check()) $o->requested_by = Auth::id();
            if (empty($o->hospital_id) && Auth::check()) $o->hospital_id = Auth::user()->hospital_id;
            if (empty($o->ordered_at)) $o->ordered_at = now();
            $prefix = 'RAD';
            $year = now()->year;
            $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
            $next = $last ? ((int) substr($last->order_number, -6)) + 1 : 1;
            $o->order_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function modality(): BelongsTo { return $this->belongsTo(RadiologyModality::class, 'modality_id'); }
    public function requester(): BelongsTo { return $this->belongsTo(User::class, 'requested_by'); }
    public function receiver(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }
    public function result()
    {
        return $this->hasOne(RadiologyResult::class, 'radiology_order_id');
    }
}
