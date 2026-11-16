<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsReceivedNoteItem extends Model
{
    protected $table = 'goods_received_note_items';
    protected $fillable = [
        'grn_id', 'drug_id', 'drug_batch_id', 'drug_name',
        'batch_number', 'expiry_date', 'manufactured_date',
        'quantity_ordered', 'quantity_received', 'quantity_rejected',
        'unit_cost', 'line_total', 'remarks',
    ];
    protected function casts(): array {
        return [
            'expiry_date' => 'date', 'manufactured_date' => 'date',
            'quantity_ordered' => 'decimal:2', 'quantity_received' => 'decimal:2',
            'quantity_rejected' => 'decimal:2', 'unit_cost' => 'decimal:2', 'line_total' => 'decimal:2',
        ];
    }
    public function grn(): BelongsTo { return $this->belongsTo(GoodsReceivedNote::class, 'grn_id'); }
    public function drug(): BelongsTo { return $this->belongsTo(Drug::class); }
    public function drugBatch(): BelongsTo { return $this->belongsTo(DrugBatch::class); }
}
