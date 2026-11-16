<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Holiday extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'name', 'holiday_date', 'end_date',
        'type', 'is_working_day', 'affects_clinics',
        'description', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'holiday_date'    => 'date',
            'end_date'        => 'date',
            'is_working_day'  => 'boolean',
            'affects_clinics' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($h) {
            if (empty($h->created_by) && Auth::check()) $h->created_by = Auth::id();
            if (empty($h->hospital_id) && Auth::check()) $h->hospital_id = Auth::user()->hospital_id;
            if (empty($h->end_date)) $h->end_date = $h->holiday_date;
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    /**
     * Check if a given date falls within this holiday's range.
     */
    public function includesDate(string $date): bool
    {
        $d = \Carbon\Carbon::parse($date);
        $start = $this->holiday_date;
        $end = $this->end_date ?? $this->holiday_date;
        return $d->between($start, $end);
    }

    /**
     * Find all holidays active for a hospital on a given date.
     */
    public static function activeOn(int $hospitalId, string $date)
    {
        return self::where('hospital_id', $hospitalId)
            ->where('holiday_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $date);
            })
            ->where('affects_clinics', true)
            ->get();
    }
}
