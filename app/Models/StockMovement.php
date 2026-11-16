<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    protected $fillable = [
        'hospital_id', 'drug_id', 'drug_batch_id', 'dispensing_point_id',
        'user_id', 'type', 'reference_number', 'movement_date',
        'quantity', 'balance_after', 'reason',
    ];

    protected function casts(): array
    {
        return [
            'movement_date'  => 'datetime',
            'quantity'       => 'decimal:2',
            'balance_after'  => 'decimal:2',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function drug(): BelongsTo { return $this->belongsTo(Drug::class); }
    public function drugBatch(): BelongsTo { return $this->belongsTo(DrugBatch::class); }
    public function dispensingPoint(): BelongsTo { return $this->belongsTo(DispensingPoint::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
