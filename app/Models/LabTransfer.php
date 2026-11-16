<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabTransfer extends Model
{
    protected $fillable = [
        'lab_order_id',
        'from_laboratory_id',
        'to_laboratory_id',
        'transferred_by',
        'received_by',
        'reason',
        'status',
        'transferred_at',
        'received_at',
        'notes',
    ];

    protected $casts = [
        'transferred_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function labOrder(): BelongsTo
    {
        return $this->belongsTo(LabOrder::class);
    }

    public function fromLab(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class, 'from_laboratory_id');
    }

    public function toLab(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class, 'to_laboratory_id');
    }

    public function transferredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
