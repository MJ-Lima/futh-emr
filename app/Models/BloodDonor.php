<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BloodDonor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'donor_number', 'donor_type', 'first_name', 'middle_name', 'last_name',
        'gender', 'date_of_birth', 'phone', 'email', 'address', 'occupation',
        'blood_group', 'rh_factor', 'last_donation_date', 'total_donations',
        'total_volume_donated_ml', 'is_eligible', 'ineligibility_reason',
        'medical_history', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth'           => 'date',
            'last_donation_date'      => 'date',
            'total_donations'         => 'integer',
            'total_volume_donated_ml' => 'integer',
            'is_eligible'             => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($d) {
            if (empty($d->hospital_id) && Auth::check()) $d->hospital_id = Auth::user()->hospital_id;
            if (empty($d->donor_number)) {
                $prefix = 'DON';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->donor_number, -6)) + 1 : 1;
                $d->donor_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function donations(): HasMany { return $this->hasMany(BloodDonation::class); }

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function bloodType(): string
    {
        return $this->blood_group;
    }
}
