<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneralStoreRequisitionItem extends Model
{
    protected $fillable = [
        'general_store_requisition_id', 'general_store_item_id',
        'quantity_requested', 'quantity_approved', 'quantity_issued',
        'unit_cost', 'notes',
    ];
    protected function casts(): array {
        return [
            'quantity_requested' => 'decimal:2', 'quantity_approved' => 'decimal:2',
            'quantity_issued' => 'decimal:2', 'unit_cost' => 'decimal:2',
        ];
    }
    public function requisition(): BelongsTo { return $this->belongsTo(GeneralStoreRequisition::class); }
    public function item(): BelongsTo { return $this->belongsTo(GeneralStoreItem::class, 'general_store_item_id'); }
}
