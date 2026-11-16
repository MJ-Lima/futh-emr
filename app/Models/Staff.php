<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'hospital_id', 'department_id', 'clinic_id', 'staff_number',
        'title', 'first_name', 'middle_name', 'last_name', 'gender', 'date_of_birth',
        'phone', 'address', 'designation', 'staff_type', 'passport', 'signature',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'status'        => 'boolean',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function clinic(): BelongsTo { return $this->belongsTo(Clinic::class); }

    public function dutyPostings()
    {
        return $this->hasMany(DutyPosting::class);
    }

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }
}
