<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class LabResult extends Model
{
    protected $fillable = [
        'lab_order_item_id', 'lab_test_id', 'performed_by', 'verified_by',
        'value', 'value_numeric', 'unit', 'reference_range', 'flag',
        'interpretation', 'comments', 'performed_at', 'verified_at', 'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
            'verified_at'  => 'datetime',
            'is_verified'  => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($r) {
            if (empty($r->performed_by) && Auth::check()) $r->performed_by = Auth::id();
            if (empty($r->performed_at)) $r->performed_at = now();
        });
    }

    public function orderItem(): BelongsTo { return $this->belongsTo(LabOrderItem::class, 'lab_order_item_id'); }
    public function test(): BelongsTo { return $this->belongsTo(LabTest::class, 'lab_test_id'); }
    public function performer(): BelongsTo { return $this->belongsTo(User::class, 'performed_by'); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }
}
