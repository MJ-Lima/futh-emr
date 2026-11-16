<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Drug extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'drug_category_id', 'name', 'generic_name', 'code',
        'atc_code', 'dosage_form', 'strength', 'unit_of_measure',
        'manufacturer', 'supplier', 'cost_price', 'selling_price',
        'reorder_level', 'reorder_quantity', 'is_controlled',
        'is_antenatal_safe', 'is_pediatric_safe', 'status',
    ];

    protected function casts(): array
    {
        return [
            'cost_price'        => 'decimal:2',
            'selling_price'     => 'decimal:2',
            'reorder_level'     => 'decimal:2',
            'reorder_quantity'  => 'decimal:2',
            'is_controlled'     => 'boolean',
            'is_antenatal_safe' => 'boolean',
            'is_pediatric_safe' => 'boolean',
            'status'            => 'boolean',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function category(): BelongsTo { return $this->belongsTo(DrugCategory::class, 'drug_category_id'); }
    public function batches(): HasMany { return $this->hasMany(DrugBatch::class); }
    public function prescriptionItems(): HasMany { return $this->hasMany(PrescriptionItem::class); }

    public function totalStock(): float
    {
        return (float) $this->batches()->where('is_quarantined', false)->sum('quantity_remaining');
    }

    public function isLowStock(): bool
    {
        return $this->totalStock() <= (float) $this->reorder_level;
    }

    // Snake-case aliases used by the dispensing workflow
    public function getStockQtyAttribute(): float
    {
        return $this->totalStock();
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->isLowStock();
    }
}
