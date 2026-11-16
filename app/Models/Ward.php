<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ward extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'department_id', 'name', 'code', 'type',
        'gender_restriction', 'total_beds', 'floor', 'wing',
        'daily_room_rate', 'in_charge_id', 'description', 'status',
    ];

    protected function casts(): array
    {
        return [
            'daily_room_rate' => 'decimal:2',
            'total_beds'      => 'integer',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function inCharge(): BelongsTo { return $this->belongsTo(User::class, 'in_charge_id'); }
    public function beds(): HasMany { return $this->hasMany(Bed::class); }
    public function admissions(): HasMany { return $this->hasMany(Admission::class); }

    public function availableBedsCount(): int
    {
        return $this->beds()->where('status', 'Available')->count();
    }

    public function occupiedBedsCount(): int
    {
        return $this->beds()->where('status', 'Occupied')->count();
    }

    public function occupancyRate(): float
    {
        $total = $this->beds()->count();
        return $total > 0 ? round($this->occupiedBedsCount() / $total * 100, 1) : 0;
    }
}
