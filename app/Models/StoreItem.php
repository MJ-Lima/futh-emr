<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'store_category_id', 'name', 'code', 'barcode',
        'generic_name', 'brand', 'manufacturer', 'supplier', 'unit_of_measure',
        'pack_size', 'units_per_pack', 'cost_price', 'selling_price',
        'reorder_level', 'reorder_quantity', 'is_consumable', 'is_reusable',
        'requires_cold_chain', 'is_controlled', 'tracks_expiry', 'tracks_serial',
        'description', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'cost_price'          => 'decimal:2',
            'selling_price'       => 'decimal:2',
            'reorder_level'       => 'decimal:2',
            'reorder_quantity'    => 'decimal:2',
            'units_per_pack'      => 'integer',
            'is_consumable'       => 'boolean',
            'is_reusable'         => 'boolean',
            'requires_cold_chain' => 'boolean',
            'is_controlled'       => 'boolean',
            'tracks_expiry'       => 'boolean',
            'tracks_serial'       => 'boolean',
            'is_active'           => 'boolean',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function category(): BelongsTo { return $this->belongsTo(StoreCategory::class, 'store_category_id'); }
    public function stocks(): HasMany { return $this->hasMany(StoreStock::class); }
    public function movements(): HasMany { return $this->hasMany(StoreMovement::class); }

    public function totalStock(): float
    {
        return (float) $this->stocks()->where('is_quarantined', false)->sum('quantity_remaining');
    }

    public function isLowStock(): bool
    {
        return $this->totalStock() <= (float) $this->reorder_level;
    }
}
