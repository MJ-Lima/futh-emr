<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PettyCashTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'voucher_number', 'hospital_id', 'dispensing_point_id', 'user_id',
        'type', 'transaction_date', 'amount', 'balance_after',
        'payee', 'category', 'description', 'receipt_path',
        'status', 'approved_by', 'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'datetime',
            'amount'           => 'decimal:2',
            'balance_after'    => 'decimal:2',
            'approved_at'      => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($p) {
            if (empty($p->user_id) && Auth::check()) $p->user_id = Auth::id();
            if (empty($p->hospital_id) && Auth::check()) $p->hospital_id = Auth::user()->hospital_id;
            if (empty($p->transaction_date)) $p->transaction_date = now();
            if (empty($p->voucher_number)) {
                $prefix = 'PCV';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->voucher_number, -6)) + 1 : 1;
                $p->voucher_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function dispensingPoint(): BelongsTo { return $this->belongsTo(DispensingPoint::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
}
