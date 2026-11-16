<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ClinicSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'clinic_id', 'appointment_type_id',
        'weekday', 'start_time', 'end_time',
        'break_start', 'break_end',
        'slot_duration_minutes', 'max_patients_per_slot',
        'buffer_minutes_before', 'buffer_minutes_after',
        'is_active', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_time'             => 'datetime',
            'end_time'               => 'datetime',
            'break_start'            => 'datetime',
            'break_end'              => 'datetime',
            'slot_duration_minutes'  => 'integer',
            'max_patients_per_slot'  => 'integer',
            'buffer_minutes_before'  => 'integer',
            'buffer_minutes_after'   => 'integer',
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
    public function clinic(): BelongsTo { return $this->belongsTo(Clinic::class); }
    public function appointmentType(): BelongsTo { return $this->belongsTo(AppointmentType::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function slots(): HasMany { return $this->hasMany(AppointmentSlot::class); }

    /**
     * Generate concrete appointment slots for this schedule on a specific date.
     * Skips the break window and respects buffer minutes.
     */
    public function generateSlotsForDate(string $date, int $providerId = null): array
    {
        $slots = [];
        $start = \Carbon\Carbon::parse("{$date} {$this->start_time->format('H:i:s')}");
        $end   = \Carbon\Carbon::parse("{$date} {$this->end_time->format('H:i:s')}");
        $breakStart = $this->break_start ? \Carbon\Carbon::parse("{$date} {$this->break_start->format('H:i:s')}") : null;
        $breakEnd   = $this->break_end   ? \Carbon\Carbon::parse("{$date} {$this->break_end->format('H:i:s')}")   : null;

        $cursor = $start->copy()->addMinutes($this->buffer_minutes_before);
        while ($cursor->lt($end)) {
            $slotEnd = $cursor->copy()->addMinutes($this->slot_duration_minutes);
            if ($slotEnd->gt($end)) break;

            // Skip if overlapping with break
            if ($breakStart && $breakEnd && $cursor->lt($breakEnd) && $slotEnd->gt($breakStart)) {
                $cursor = $breakEnd->copy();
                continue;
            }

            $slots[] = [
                'hospital_id'         => $this->hospital_id,
                'clinic_id'           => $this->clinic_id,
                'provider_id'         => $providerId,
                'appointment_type_id' => $this->appointment_type_id,
                'clinic_schedule_id'  => $this->id,
                'slot_date'           => $date,
                'start_time'          => $cursor->format('H:i:s'),
                'end_time'            => $slotEnd->format('H:i:s'),
                'duration_minutes'    => $this->slot_duration_minutes,
                'capacity'            => $this->max_patients_per_slot,
                'booked_count'        => 0,
                'status'              => 'Available',
                'created_at'          => now(),
                'updated_at'          => now(),
            ];
            $cursor = $slotEnd->copy()->addMinutes($this->buffer_minutes_after);
        }

        return $slots;
    }
}
