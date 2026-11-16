<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class LeaveRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'leave_number', 'hospital_id', 'user_id', 'staff_id',
        'leave_type', 'start_date', 'end_date',
        'days_requested', 'days_approved',
        'reason', 'handover_to', 'handover_notes', 'attachment_paths',
        'status',
        'recommended_by', 'recommended_at',
        'approved_by', 'approved_at',
        'review_notes', 'rejection_reason',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date'        => 'date',
            'end_date'          => 'date',
            'recommended_at'    => 'datetime',
            'approved_at'       => 'datetime',
            'days_requested'    => 'integer',
            'days_approved'     => 'integer',
            'attachment_paths'  => 'array',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($l) {
            if (empty($l->created_by) && Auth::check()) $l->created_by = Auth::id();
            if (empty($l->user_id) && Auth::check()) $l->user_id = Auth::id();
            if (empty($l->hospital_id) && Auth::check()) $l->hospital_id = Auth::user()->hospital_id;
            if (empty($l->leave_number)) {
                $prefix = 'LVR';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->leave_number, -6)) + 1 : 1;
                $l->leave_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
            if (empty($l->days_requested) && $l->start_date && $l->end_date) {
                $l->days_requested = $l->start_date->diffInDays($l->end_date) + 1;
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function staff(): BelongsTo { return $this->belongsTo(Staff::class); }
    public function recommender(): BelongsTo { return $this->belongsTo(User::class, 'recommended_by'); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function isOnLeaveOn(string $date): bool
    {
        $d = \Carbon\Carbon::parse($date);
        return $this->status === 'On Leave'
            && $d->between($this->start_date, $this->end_date);
    }
}
