<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LabOrderItem extends Model
{
    protected $fillable = [
        'lab_order_id', 'lab_test_id', 'lab_unit_id',
        'test_name', 'loinc_code', 'price', 'status',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:2'];
    }

    public function order(): BelongsTo { return $this->belongsTo(LabOrder::class, 'lab_order_id'); }
    public function test(): BelongsTo { return $this->belongsTo(LabTest::class, 'lab_test_id'); }
    public function unit(): BelongsTo { return $this->belongsTo(LabUnit::class, 'lab_unit_id'); }

    public function result(): HasOne
    {
        return $this->hasOne(LabResult::class, 'lab_order_item_id');
    }
}
