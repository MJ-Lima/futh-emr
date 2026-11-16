<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DutyPosting extends Model
{
    use SoftDeletes;

   protected $fillable = [
    'duty_roster_id', 'hospital_id', 'user_id', 'staff_id',
    'department_id', 'clinic_id',
    'start_date', 'end_date', 'shift', 'shift_start', 'shift_end',
    'role_in_unit', 'notes', 'status', 'posted_by',
    // Cashier shift enforcement fields:
    'shift_start_time', 'shift_end_time',
    'is_cashier_shift', 'is_active_now',
    'last_login_at', 'force_logout_at',
];

protected function casts(): array
{
    return [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'shift_start' => 'datetime',
        'shift_end'   => 'datetime',
        'shift_start_time' => 'datetime:H:i:s',
        'shift_end_time'   => 'datetime:H:i:s',
        'is_cashier_shift' => 'boolean',
        'is_active_now'     => 'boolean',
        'last_login_at'     => 'datetime',
        'force_logout_at'   => 'datetime',
    ];
}

    protected static function booted()
    {
        static::creating(function ($p) {
            if (empty($p->posted_by) && Auth::check()) $p->posted_by = Auth::id();
            if (empty($p->hospital_id) && Auth::check()) $p->hospital_id = Auth::user()->hospital_id;
            if (empty($p->user_id) && $p->staff_id) {
                $p->user_id = Staff::find($p->staff_id)?->user_id;
            }
        });

        static::created(function ($p) {
            // When a posting is created with status Active, mark the user's
            // previous active posting as Completed (one active per user/clinic/day).
            if ($p->status === 'Active') {
                self::where('user_id', $p->user_id)
                    ->where('id', '!=', $p->id)
                    ->where('status', 'Active')
                    ->update(['status' => 'Completed']);
            }
        });
    }

    public function roster(): BelongsTo { return $this->belongsTo(DutyRoster::class, 'duty_roster_id'); }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function staff(): BelongsTo { return $this->belongsTo(Staff::class); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function clinic(): BelongsTo { return $this->belongsTo(Clinic::class); }
    public function poster(): BelongsTo { return $this->belongsTo(User::class, 'posted_by'); }

    public function isActive(): bool
    {
        return $this->status === 'Active'
            && $this->start_date?->lte(now())
            && $this->end_date?->gte(now());
    }
}
