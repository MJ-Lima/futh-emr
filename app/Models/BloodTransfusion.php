<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BloodTransfusion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transfusion_number', 'hospital_id', 'patient_id', 'encounter_id', 'admission_id',
        'blood_request_id', 'blood_unit_id', 'crossmatch_id',
        'transfused_by', 'verified_by',
        'transfusion_started_at', 'transfusion_ended_at',
        'volume_transfused_ml', 'site_of_transfusion', 'cannula_size',
        'pre_transfusion_bp', 'post_transfusion_bp',
        'pre_transfusion_temp_c', 'post_transfusion_temp_c',
        'pre_transfusion_pulse', 'post_transfusion_pulse',
        'adverse_reactions', 'reaction_severity', 'action_taken',
        'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'transfusion_started_at' => 'datetime',
            'transfusion_ended_at'   => 'datetime',
            'volume_transfused_ml'   => 'integer',
            'pre_transfusion_bp'     => 'decimal:0',
            'post_transfusion_bp'    => 'decimal:0',
            'pre_transfusion_temp_c' => 'decimal:1',
            'post_transfusion_temp_c'=> 'decimal:1',
            'pre_transfusion_pulse'  => 'decimal:0',
            'post_transfusion_pulse' => 'decimal:0',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($t) {
            if (empty($t->transfused_by) && Auth::check()) $t->transfused_by = Auth::id();
            if (empty($t->hospital_id) && Auth::check()) $t->hospital_id = Auth::user()->hospital_id;
            if (empty($t->transfusion_started_at)) $t->transfusion_started_at = now();
            if (empty($t->transfusion_number)) {
                $prefix = 'BTF';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->transfusion_number, -6)) + 1 : 1;
                $t->transfusion_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });

        static::created(function ($t) {
            // Mark the blood unit as transfused
            $t->bloodUnit->update(['status' => 'Transfused']);
        });

        static::updated(function ($t) {
            if ($t->wasChanged('status') && $t->status === 'Completed') {
                $t->bloodRequest->update(['status' => 'Transfused']);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function admission(): BelongsTo { return $this->belongsTo(Admission::class); }
    public function bloodRequest(): BelongsTo { return $this->belongsTo(BloodRequest::class); }
    public function bloodUnit(): BelongsTo { return $this->belongsTo(BloodUnit::class); }
    public function crossmatch(): BelongsTo { return $this->belongsTo(BloodCrossmatch::class); }
    public function transfuser(): BelongsTo { return $this->belongsTo(User::class, 'transfused_by'); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }
}
