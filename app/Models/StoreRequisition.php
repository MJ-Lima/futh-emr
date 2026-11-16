<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class StoreRequisition extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'requisition_number', 'hospital_id', 'requesting_department_id',
        'requesting_location_id', 'source_location_id',
        'requested_by', 'approved_by', 'issued_by', 'received_by',
        'required_date', 'approved_date', 'issued_date', 'received_date',
        'priority', 'purpose', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'required_date'  => 'date',
            'approved_date'  => 'date',
            'issued_date'    => 'date',
            'received_date'  => 'date',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($r) {
            if (empty($r->requested_by) && Auth::check()) $r->requested_by = Auth::id();
            if (empty($r->hospital_id) && Auth::check()) $r->hospital_id = Auth::user()->hospital_id;
            if (empty($r->requisition_number)) {
                $prefix = 'REQ';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->requisition_number, -6)) + 1 : 1;
                $r->requisition_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function requestingDepartment(): BelongsTo { return $this->belongsTo(Department::class, 'requesting_department_id'); }
    public function requestingLocation(): BelongsTo { return $this->belongsTo(StoreLocation::class, 'requesting_location_id'); }
    public function sourceLocation(): BelongsTo { return $this->belongsTo(StoreLocation::class, 'source_location_id'); }
    public function requester(): BelongsTo { return $this->belongsTo(User::class, 'requested_by'); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
    public function issuer(): BelongsTo { return $this->belongsTo(User::class, 'issued_by'); }
    public function receiver(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }
    public function items(): HasMany { return $this->hasMany(StoreRequisitionItem::class); }
}
