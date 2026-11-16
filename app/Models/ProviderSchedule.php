<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProviderSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'user_id', 'clinic_id',
        'weekday', 'start_time', 'end_time',
        'break_start', 'break_end',
        'slot_duration_minutes', 'max_patients_per_slot',
        'effective_from', 'effective_until',
        'is_active', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_time'             => 'datetime',
            'end_time'               => 'datetime',
            'break_start'            => 'datetime',
            'break_end'              => 'datetime',
            'effective_from'         => 'date',
            'effective_until'        => 'date',
            'slot_duration_minutes'  => 'integer',
            'max_patients_per_slot'  => 'integer',
            'is_active'              => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($s) {
            if (empty($s->created_by) && Auth::check()) $s->created_by = Auth::id();
            if (empty($s->hospital_id) && Auth::check()) $s->hospital_id = Auth::user()->hospital_id;
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function clinic(): BelongsTo { return $this->belongsTo(Clinic::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function slots(): HasMany { return $this->hasMany(AppointmentSlot::class); }

    public function isEffectiveOn(string $date): bool
    {
        $d = \Carbon\Carbon::parse($date);
        if ($this->effective_from && $d->lt($this->effective_from)) return false;
        if ($this->effective_until && $d->gt($this->effective_until)) return false;
        return $d->format('l') === $this->weekday;
    }
}
