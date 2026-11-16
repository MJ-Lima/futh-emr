<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Theatre extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'name', 'code', 'type', 'capacity', 'equipment', 'is_active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    public const TYPES = [
        'General' => 'General Surgery',
        'Orthopaedic' => 'Orthopaedic',
        'ENT' => 'ENT',
        'Ophthalmic' => 'Ophthalmic',
        'Cardiothoracic' => 'Cardiothoracic',
        'Obstetric' => 'Obstetric / Emergency',
        'Day Case' => 'Day Case',
    ];

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }

    public function procedureBookings(): HasMany
    {
        return $this->hasMany(ProcedureBooking::class, 'theatre_id');
    }

    /**
     * Get today's scheduled procedures in this theatre.
     */
    public function getTodayBookingsCountAttribute(): int
    {
        return $this->procedureBookings()
            ->whereDate('scheduled_start', today())
            ->whereNotIn('booking_status', ['Cancelled', 'No Show'])
            ->count();
    }

    /**
     * Check if the theatre is currently in use (a procedure is In Progress).
     */
    public function getIsInUseAttribute(): bool
    {
        return $this->procedureBookings()
            ->where('booking_status', 'In Progress')
            ->exists();
    }
}
