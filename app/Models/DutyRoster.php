<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DutyRoster extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'name', 'code', 'start_date', 'end_date', 'cycle',
        'description', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($r) {
            if (empty($r->created_by) && Auth::check()) $r->created_by = Auth::id();
            if (empty($r->hospital_id) && Auth::check()) $r->hospital_id = Auth::user()->hospital_id;
            if (empty($r->code)) {
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->code, -4)) + 1 : 1;
                $r->code = sprintf('DR-%s-%04d', $year, $next);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function postings(): HasMany { return $this->hasMany(DutyPosting::class); }
}
