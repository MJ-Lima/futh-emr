<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'lab_panel_id', 'lab_unit_id', 'name', 'code', 'loinc_code',
        'specimen_type', 'container', 'unit', 'reference_range_low',
        'reference_range_high', 'reference_notes', 'description', 'price',
        'turnaround_hours', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price'            => 'decimal:2',
            'turnaround_hours' => 'integer',
            'reference_range_low'  => 'decimal:2',
            'reference_range_high' => 'decimal:2',
            'status'           => 'boolean',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function panel(): BelongsTo { return $this->belongsTo(LabPanel::class, 'lab_panel_id'); }
    public function unit(): BelongsTo { return $this->belongsTo(LabUnit::class, 'lab_unit_id'); }
    public function orderItems(): HasMany { return $this->hasMany(LabOrderItem::class); }

    public function getReferenceRangeAttribute(): string
    {
        if ($this->reference_range_low && $this->reference_range_high) {
            return "{$this->reference_range_low} - {$this->reference_range_high}";
        }
        return (string) ($this->reference_notes ?? '');
    }
}
