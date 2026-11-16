<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'name', 'code', 'category', 'color',
        'default_duration_minutes', 'default_fee',
        'description', 'requires_pre_auth', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'default_duration_minutes' => 'integer',
            'default_fee'              => 'decimal:2',
            'requires_pre_auth'        => 'boolean',
            'is_active'                => 'boolean',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function clinicSchedules(): HasMany { return $this->hasMany(ClinicSchedule::class); }
    public function appointmentSlots(): HasMany { return $this->hasMany(AppointmentSlot::class); }
}
