<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payment_number', 'hospital_id', 'patient_id', 'invoice_id',
        'insurance_claim_id', 'payer_type', 'payer_name', 'payment_method',
        'amount', 'currency', 'reference_number', 'bank_name', 'cheque_number',
        'card_last4', 'payment_date', 'received_by', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'payment_date' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($p) {
            if (empty($p->received_by) && Auth::check()) $p->received_by = Auth::id();
            if (empty($p->hospital_id) && Auth::check()) $p->hospital_id = Auth::user()->hospital_id;
            if (empty($p->payment_date)) $p->payment_date = now();
            if (empty($p->payment_number)) {
                $prefix = 'PMT';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->payment_number, -6)) + 1 : 1;
                $p->payment_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });

        static::created(function ($p) {
            // Update parent invoice amount_paid / balance_due
            if ($p->invoice_id && $p->status === 'Completed') {
                $inv = Invoice::find($p->invoice_id);
                if ($inv) {
                    $inv->amount_paid = $inv->payments()
                        ->where('status', 'Completed')
                        ->sum('amount');
                    $inv->balance_due = max(0, $inv->patient_portion - $inv->amount_paid);
                    $inv->status = $inv->balance_due <= 0.001 ? 'Paid' :
                        ($inv->amount_paid > 0 ? 'Partially Paid' : 'Pending');
                    $inv->save();
                }
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function claim(): BelongsTo { return $this->belongsTo(InsuranceClaim::class, 'insurance_claim_id'); }
    public function receiver(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }
}
