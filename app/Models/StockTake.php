<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class StockTake extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'stock_take_number', 'hospital_id', 'location_id', 'scope',
        'stock_take_date', 'initiated_by', 'counted_by', 'verified_by',
        'status', 'notes',
    ];

    protected function casts(): array
    {
        return ['stock_take_date' => 'date'];
    }

    protected static function booted()
    {
        static::creating(function ($s) {
            if (empty($s->initiated_by) && Auth::check()) $s->initiated_by = Auth::id();
            if (empty($s->hospital_id) && Auth::check()) $s->hospital_id = Auth::user()->hospital_id;
            if (empty($s->stock_take_date)) $s->stock_take_date = now();
            if (empty($s->stock_take_number)) {
                $prefix = 'STK';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->stock_take_number, -6)) + 1 : 1;
                $s->stock_take_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function location(): BelongsTo { return $this->belongsTo(StoreLocation::class); }
    public function initiator(): BelongsTo { return $this->belongsTo(User::class, 'initiated_by'); }
    public function counter(): BelongsTo { return $this->belongsTo(User::class, 'counted_by'); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }
    public function items(): HasMany { return $this->hasMany(StockTakeItem::class); }
}
