<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultationRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'clinic_id', 'name', 'code',
        'capacity', 'assigned_doctor_id', 'is_active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function assignedDoctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_doctor_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'consultation_room_id');
    }

    /**
     * Count of patients currently allocated to this room and waiting/in-consultation.
     */
    public function getCurrentLoadAttribute(): int
    {
        return $this->appointments()
            ->whereDate('appointment_date', today())
            ->whereIn('status', ['Waiting for Doctor', 'In Consultation'])
            ->count();
    }

    /**
     * Is this room available to take a new patient?
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->is_active && $this->current_load < $this->capacity;
    }

    /**
     * Auto-allocate the patient to the next available consultation room in their clinic.
     *
     * Strategy:
     *  - Pick all active rooms in the patient's clinic
     *  - If at least one has available capacity (< capacity patients today), pick the one with
     *    the smallest current load.
     *  - If no room is currently available (all at capacity), pick the room with the smallest
     *    current load anyway — the patient will wait in the queue (room_queue_position will
     *    assign them a place in line).
     *
     * Returns null when the clinic has no active consultation rooms configured.
     */
    public static function autoAllocateForAppointment(Appointment $appointment): ?self
    {
        $rooms = $appointment->clinic->consultationRooms()
            ->where('is_active', true)
            ->get();

        if ($rooms->isEmpty()) {
            return null;
        }

        // Prefer rooms with available capacity, smallest load first
        $available = $rooms->filter(fn ($r) => $r->is_available);
        if ($available->isNotEmpty()) {
            return $available->sortBy('current_load')->first();
        }

        // Otherwise assign to the room with the smallest load (so patient gets next slot)
        return $rooms->sortBy('current_load')->first();
    }
}
