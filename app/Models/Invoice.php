<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number', 'hospital_id', 'patient_id', 'encounter_id',
        'patient_insurance_id', 'payment_mode', 'invoice_date', 'due_date',
        'subtotal', 'tax_amount', 'discount_amount', 'insurance_covered',
        'patient_portion', 'total_amount', 'amount_paid', 'balance_due',
        'status', 'created_by', 'billed_by', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date'      => 'date',
            'due_date'          => 'date',
            'subtotal'          => 'decimal:2',
            'tax_amount'        => 'decimal:2',
            'discount_amount'   => 'decimal:2',
            'insurance_covered' => 'decimal:2',
            'patient_portion'   => 'decimal:2',
            'total_amount'      => 'decimal:2',
            'amount_paid'       => 'decimal:2',
            'balance_due'       => 'decimal:2',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($inv) {
            if (empty($inv->created_by) && Auth::check()) $inv->created_by = Auth::id();
            if (empty($inv->hospital_id) && Auth::check()) $inv->hospital_id = Auth::user()->hospital_id;
            if (empty($inv->invoice_date)) $inv->invoice_date = now();
            if (empty($inv->invoice_number)) {
                $prefix = 'INV';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->invoice_number, -6)) + 1 : 1;
                $inv->invoice_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function policy(): BelongsTo { return $this->belongsTo(PatientInsurance::class, 'patient_insurance_id'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function billedBy(): BelongsTo { return $this->belongsTo(User::class, 'billed_by'); }
    public function items(): HasMany { return $this->hasMany(InvoiceItem::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }

    public function recalculate(): void
    {
        $this->subtotal = $this->items()->sum('total_amount');
        $this->tax_amount = $this->items()->sum('tax_amount');
        $this->discount_amount = $this->items()->sum('discount_amount');
        $this->insurance_covered = $this->items()->sum('insurance_covered');
        $this->patient_portion = $this->items()->sum('patient_portion');
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
        $this->balance_due = $this->patient_portion - $this->amount_paid;
        $this->save();
    }
}
