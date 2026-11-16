<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BloodUnit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'unit_number', 'hospital_id', 'donation_id', 'donor_id',
        'blood_group', 'component_type', 'volume_ml',
        'collection_date', 'expiry_date', 'storage_location', 'storage_temperature_c',
        'hiv_status', 'hepatitis_b_status', 'hepatitis_c_status', 'syphilis_status', 'malaria_status',
        'screening_notes', 'screened_by', 'screened_at',
        'is_screened', 'is_quarantined', 'status',
    ];

    protected function casts(): array
    {
        return [
            'collection_date'           => 'date',
            'expiry_date'               => 'date',
            'volume_ml'                 => 'integer',
            'storage_temperature_c'     => 'decimal:1',
            'screened_at'               => 'datetime',
            'is_screened'               => 'boolean',
            'is_quarantined'            => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($u) {
            if (empty($u->hospital_id) && Auth::check()) $u->hospital_id = Auth::user()->hospital_id;
            if (empty($u->collection_date)) $u->collection_date = now();
            if (empty($u->expiry_date)) {
                // Default expiry: 35 days for whole blood, 5 days for platelets, 1 year for plasma
                $days = match($u->component_type) {
                    'Platelets'         => 5,
                    'Whole Blood'       => 35,
                    'Packed Red Cells'  => 42,
                    'Plasma', 'Fresh Frozen Plasma', 'Cryoprecipitate' => 365,
                    default => 35,
                };
                $u->expiry_date = now()->addDays($days);
            }
            if (empty($u->unit_number)) {
                $prefix = 'BHU';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->unit_number, -6)) + 1 : 1;
                $u->unit_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function donation(): BelongsTo { return $this->belongsTo(BloodDonation::class); }
    public function donor(): BelongsTo { return $this->belongsTo(BloodDonor::class); }
    public function screener(): BelongsTo { return $this->belongsTo(User::class, 'screened_by'); }
    public function crossmatches(): HasMany { return $this->hasMany(BloodCrossmatch::class); }
    public function transfusions(): HasMany { return $this->hasMany(BloodTransfusion::class); }

    public function isExpired(): bool
    {
        return $this->expiry_date?->isPast() ?? false;
    }

    public function isAvailable(): bool
    {
        return $this->status === 'Available'
            && $this->is_screened
            && ! $this->is_quarantined
            && ! $this->isExpired();
    }

    public function isSafeForTransfusion(): bool
    {
        return $this->isAvailable()
            && $this->hiv_status === 'Negative'
            && $this->hepatitis_b_status === 'Negative'
            && $this->hepatitis_c_status === 'Negative'
            && $this->syphilis_status === 'Negative'
            && $this->malaria_status === 'Negative';
    }

    /**
     * Mark this unit as screened with the given infectious disease results.
     */
    public function markScreened(array $results, int $screenedById = null): void
    {
        $this->update(array_merge($results, [
            'is_screened'  => true,
            'screened_at'  => now(),
            'screened_by'  => $screenedById ?? Auth::id(),
            'status'       => $this->isSafeForTransfusionAfterScreening($results) ? 'Available' : 'Discarded',
        ]));
    }

    private function isSafeForTransfusionAfterScreening(array $results): bool
    {
        $safe = true;
        foreach (['hiv_status', 'hepatitis_b_status', 'hepatitis_c_status', 'syphilis_status', 'malaria_status'] as $key) {
            if (isset($results[$key]) && $results[$key] === 'Positive') {
                $safe = false;
                break;
            }
        }
        return $safe;
    }
}
