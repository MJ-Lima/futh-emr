<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Purchase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'supplier_id', 'received_by', 'purchase_number',
        'invoice_number', 'lpo_number', 'order_date', 'received_date',
        'invoice_date', 'subtotal', 'tax_amount', 'discount_amount',
        'total_amount', 'amount_paid', 'status', 'payment_status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'order_date'    => 'date',
            'received_date' => 'date',
            'invoice_date'  => 'date',
            'subtotal'         => 'decimal:2',
            'tax_amount'       => 'decimal:2',
            'discount_amount'  => 'decimal:2',
            'total_amount'     => 'decimal:2',
            'amount_paid'      => 'decimal:2',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($p) {
            if (empty($p->received_by) && Auth::check()) $p->received_by = Auth::id();
            if (empty($p->hospital_id) && Auth::check()) $p->hospital_id = Auth::user()->hospital_id;
            $prefix = 'PO';
            $year = now()->year;
            $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
            $next = $last ? ((int) substr($last->purchase_number, -6)) + 1 : 1;
            $p->purchase_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function receiver(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }
    public function items(): HasMany { return $this->hasMany(PurchaseItem::class); }
}
