<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabUnit extends Model
{
    protected $fillable = [
        'laboratory_id',
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function tests(): HasMany
    {
        return $this->hasMany(LabTest::class, 'lab_unit_id');
    }

    public function panels(): HasMany
    {
        return $this->hasMany(LabPanel::class, 'lab_unit_id');
    }
}
