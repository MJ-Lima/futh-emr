<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    use SoftDeletes;

    protected $fillable = ['hospital_id', 'name', 'code', 'status'];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function encounters(): HasMany { return $this->hasMany(Encounter::class); }
    public function appointments(): HasMany { return $this->hasMany(Appointment::class); }
    public function patients(): HasMany { return $this->hasMany(Patient::class); }
    public function staff(): HasMany { return $this->hasMany(Staff::class); }
    public function dutyPostings(): HasMany { return $this->hasMany(DutyPosting::class); }
    public function consultationRooms(): HasMany { return $this->hasMany(ConsultationRoom::class); }

    /**
     * Count of patients currently waiting for the nurse at this clinic (today).
     */
    public function getPatientsWaitingForNurseAttribute(): int
    {
        return $this->appointments()
            ->whereDate('appointment_date', today())
            ->whereIn('status', ['Confirmed', 'Checked In', 'In Nurse Queue'])
            ->where(function ($q) {
                $q->where('vitals_status', 'Pending')->orWhereNull('vitals_status');
            })
            ->count();
    }

    /**
     * Count of patients currently waiting for a doctor (post-vitals) at this clinic (today).
     */
    public function getPatientsWaitingForDoctorAttribute(): int
    {
        return $this->appointments()
            ->whereDate('appointment_date', today())
            ->whereIn('status', ['Vitals Taken', 'Waiting for Doctor'])
            ->count();
    }

    /**
     * Active consultation rooms in this clinic.
     */
    public function getActiveConsultationRoomsCountAttribute(): int
    {
        return $this->consultationRooms()->where('is_active', true)->count();
    }
}
