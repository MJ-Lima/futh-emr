<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentSlot extends Model
{
    protected $fillable = [
        'hospital_id', 'clinic_id', 'provider_id', 'appointment_type_id',
        'clinic_schedule_id', 'provider_schedule_id',
        'slot_date', 'start_time', 'end_time',
        'duration_minutes', 'capacity', 'booked_count',
        'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'slot_date'         => 'date',
            'start_time'        => 'datetime',
            'end_time'          => 'datetime',
            'duration_minutes'  => 'integer',
            'capacity'          => 'integer',
            'booked_count'      => 'integer',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function clinic(): BelongsTo { return $this->belongsTo(Clinic::class); }
    public function provider(): BelongsTo { return $this->belongsTo(User::class, 'provider_id'); }
    public function appointmentType(): BelongsTo { return $this->belongsTo(AppointmentType::class); }
    public function clinicSchedule(): BelongsTo { return $this->belongsTo(ClinicSchedule::class); }
    public function providerSchedule(): BelongsTo { return $this->belongsTo(ProviderSchedule::class); }

    public function isAvailable(): bool
    {
        return $this->status === 'Available' && $this->booked_count < $this->capacity;
    }

    public function book(): bool
    {
        if (! $this->isAvailable()) return false;
        $this->increment('booked_count');
        if ($this->booked_count >= $this->capacity) {
            $this->update(['status' => 'Booked']);
        }
        return true;
    }

    public function cancelBooking(): void
    {
        $this->decrement('booked_count');
        if ($this->booked_count < $this->capacity) {
            $this->update(['status' => 'Available']);
        }
    }
}
