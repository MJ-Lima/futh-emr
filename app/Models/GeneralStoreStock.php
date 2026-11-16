<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralStoreStock extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'hospital_id', 'general_store_item_id', 'general_store_location_id',
        'batch_number', 'expiry_date', 'quantity_received', 'quantity_remaining',
        'unit_cost', 'received_date', 'reference_number', 'is_quarantined',
    ];
    protected function casts(): array {
        return [
            'expiry_date' => 'date', 'received_date' => 'date',
            'quantity_received' => 'decimal:2', 'quantity_remaining' => 'decimal:2',
            'unit_cost' => 'decimal:2', 'is_quarantined' => 'boolean',
        ];
    }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function item(): BelongsTo { return $this->belongsTo(GeneralStoreItem::class, 'general_store_item_id'); }
    public function location(): BelongsTo { return $this->belongsTo(GeneralStoreLocation::class, 'general_store_location_id'); }
}
