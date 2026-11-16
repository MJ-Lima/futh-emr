<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrugStockLevel extends Model
{
    protected $table = 'drug_stock_levels';
    protected $fillable = [
        'hospital_id', 'drug_id', 'dispensing_point_id',
        'quantity_on_hand', 'quantity_reserved', 'quantity_available',
        'quantity_in_transit', 'reorder_level', 'reorder_quantity',
        'last_received_date', 'last_dispensed_date', 'last_counted_date',
        'stock_status',
    ];
    protected function casts(): array {
        return [
            'quantity_on_hand' => 'decimal:2', 'quantity_reserved' => 'decimal:2',
            'quantity_available' => 'decimal:2', 'quantity_in_transit' => 'decimal:2',
            'reorder_level' => 'decimal:2', 'reorder_quantity' => 'decimal:2',
            'last_received_date' => 'date', 'last_dispensed_date' => 'date', 'last_counted_date' => 'date',
        ];
    }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function drug(): BelongsTo { return $this->belongsTo(Drug::class); }
    public function dispensingPoint(): BelongsTo { return $this->belongsTo(DispensingPoint::class); }
    public function recomputeStatus(): void {
        $onHand = (float) $this->quantity_on_hand;
        $reorder = (float) $this->reorder_level;
        $status = match(true) {
            $onHand <= 0 => 'Out of Stock',
            $onHand <= $reorder => 'Low Stock',
            $onHand > $reorder * 3 => 'Over Stock',
            default => 'In Stock',
        };
        $this->update([
            'quantity_available' => max(0, $onHand - (float) $this->quantity_reserved),
            'stock_status' => $status,
        ]);
    }
}
