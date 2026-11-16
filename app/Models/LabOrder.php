<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class LabOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'patient_id', 'encounter_id', 'requested_by', 'received_by',
        'destination_lab_id',
        'order_number', 'ordered_at', 'collected_at', 'resulted_at', 'priority',
        'status', 'clinical_notes', 'specimen_source', 'fasting_status',
    ];

    public const STATUSES = [
        'Pending' => 'Pending',
        'Sample Collected' => 'Sample Collected',
        'In Progress' => 'In Progress',
        'Completed' => 'Completed',
        'Verified' => 'Verified',
        'Cancelled' => 'Cancelled',
        'Rejected' => 'Rejected',
    ];

    public const STATUS_COLORS = [
        'Pending' => 'warning',
        'Sample Collected' => 'info',
        'In Progress' => 'info',
        'Completed' => 'success',
        'Verified' => 'success',
        'Cancelled' => 'danger',
        'Rejected' => 'danger',
    ];

    public const PRIORITIES = [
        'Routine' => 'Routine',
        'Urgent' => 'Urgent',
        'STAT' => 'STAT',
        'ASAP' => 'ASAP',
    ];

    protected function casts(): array
    {
        return [
            'ordered_at'  => 'datetime',
            'collected_at' => 'datetime',
            'resulted_at' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($o) {
            if (empty($o->requested_by) && Auth::check()) $o->requested_by = Auth::id();
            if (empty($o->hospital_id) && Auth::check()) $o->hospital_id = Auth::user()->hospital_id;
            if (empty($o->ordered_at)) $o->ordered_at = now();
            $prefix = 'LAB';
            $year = now()->year;
            $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
            $next = $last ? ((int) substr($last->order_number, -6)) + 1 : 1;
            $o->order_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function requester(): BelongsTo { return $this->belongsTo(User::class, 'requested_by'); }
    public function receiver(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }
    public function destinationLab(): BelongsTo { return $this->belongsTo(Laboratory::class, 'destination_lab_id'); }
    public function items(): HasMany { return $this->hasMany(LabOrderItem::class); }
    public function transfers(): HasMany { return $this->hasMany(LabTransfer::class); }
}
