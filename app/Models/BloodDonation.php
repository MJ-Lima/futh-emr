<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BloodDonation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'donation_number', 'hospital_id', 'donor_id', 'donation_date', 'donation_time',
        'donation_type', 'volume_collected_ml', 'collected_by',
        'collection_site', 'vein_used', 'adverse_reactions', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'donation_date'       => 'date',
            'volume_collected_ml' => 'integer',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($d) {
            if (empty($d->collected_by) && Auth::check()) $d->collected_by = Auth::id();
            if (empty($d->hospital_id) && Auth::check()) $d->hospital_id = Auth::user()->hospital_id;
            if (empty($d->donation_date)) $d->donation_date = now();
            if (empty($d->donation_number)) {
                $prefix = 'BDN';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->donation_number, -6)) + 1 : 1;
                $d->donation_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });

        static::created(function ($d) {
            // Update donor's last donation date and totals
            $donor = $d->donor;
            if ($donor) {
                $donor->increment('total_donations');
                $donor->increment('total_volume_donated_ml', (int) $d->volume_collected_ml);
                $donor->update(['last_donation_date' => $d->donation_date]);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function donor(): BelongsTo { return $this->belongsTo(BloodDonor::class); }
    public function collector(): BelongsTo { return $this->belongsTo(User::class, 'collected_by'); }
    public function units(): HasMany { return $this->hasMany(BloodUnit::class); }
}
