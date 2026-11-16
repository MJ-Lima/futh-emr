<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RadiologyModality extends Model
{
    protected $fillable = [
        'hospital_id', 'name', 'code', 'description', 'price',
        'turnaround_hours', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price'            => 'decimal:2',
            'turnaround_hours' => 'integer',
            'status'           => 'boolean',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function orders(): HasMany { return $this->hasMany(RadiologyOrder::class, 'modality_id'); }
}
