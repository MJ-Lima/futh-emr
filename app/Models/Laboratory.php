<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratory extends Model
{
    protected $fillable = [
        'hospital_id',
        'name',
        'code',
        'lab_type',
        'address',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(LabUnit::class);
    }

    public function labOrders(): HasMany
    {
        return $this->hasMany(LabOrder::class, 'destination_lab_id');
    }
}
