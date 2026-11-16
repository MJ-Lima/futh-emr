<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class RadiologyResult extends Model
{
    protected $fillable = [
        'radiology_order_id', 'reported_by', 'verified_by', 'findings',
        'impression', 'recommendations', 'images', 'reported_at', 'verified_at',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'reported_at' => 'datetime',
            'verified_at' => 'datetime',
            'is_verified' => 'boolean',
            'images'      => 'array',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($r) {
            if (empty($r->reported_by) && Auth::check()) $r->reported_by = Auth::id();
            if (empty($r->reported_at)) $r->reported_at = now();
        });
    }

    public function order(): BelongsTo { return $this->belongsTo(RadiologyOrder::class, 'radiology_order_id'); }
    public function reporter(): BelongsTo { return $this->belongsTo(User::class, 'reported_by'); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }
}
