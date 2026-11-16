<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class GoodsReceivedNote extends Model
{
    protected $table = 'goods_received_notes';
    protected $fillable = [
        'grn_number', 'hospital_id', 'purchase_id', 'supplier_id', 'receipt_id',
        'dispensing_point_id', 'received_by', 'verified_by',
        'received_at', 'invoice_number', 'delivery_note_number', 'lpo_number',
        'invoice_date', 'total_value', 'total_rejected', 'status', 'notes',
    ];
    protected function casts(): array {
        return [
            'received_at' => 'datetime', 'invoice_date' => 'date',
            'total_value' => 'decimal:2', 'total_rejected' => 'decimal:2',
        ];
    }
    protected static function booted() {
        static::creating(function ($g) {
            if (empty($g->received_by) && Auth::check()) $g->received_by = Auth::id();
            if (empty($g->hospital_id) && Auth::check()) $g->hospital_id = Auth::user()->hospital_id;
            if (empty($g->received_at)) $g->received_at = now();
            if (empty($g->grn_number)) {
                $prefix = 'GRN'; $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->grn_number, -6)) + 1 : 1;
                $g->grn_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function purchase(): BelongsTo { return $this->belongsTo(Purchase::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function receipt(): BelongsTo { return $this->belongsTo(Receipt::class); }
    public function dispensingPoint(): BelongsTo { return $this->belongsTo(DispensingPoint::class); }
    public function receiver(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }
    public function items(): HasMany { return $this->hasMany(GoodsReceivedNoteItem::class, 'grn_id'); }
    public function recalculateTotals(): void {
        $this->total_value = $this->items()->sum('line_total');
        $this->total_rejected = $this->items()->sum('quantity_rejected');
        $this->save();
    }
    public function verify(int $verifierId = null): void {
        $this->update(['status' => 'Verified', 'verified_by' => $verifierId ?? Auth::id()]);
    }
}
