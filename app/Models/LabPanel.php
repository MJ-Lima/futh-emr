<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabPanel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'lab_unit_id', 'name', 'code', 'loinc_code', 'specimen_type',
        'container', 'description', 'price', 'turnaround_hours', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price'             => 'decimal:2',
            'turnaround_hours'  => 'integer',
            'status'            => 'boolean',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function unit(): BelongsTo { return $this->belongsTo(LabUnit::class, 'lab_unit_id'); }
    public function tests(): HasMany { return $this->hasMany(LabTest::class, 'lab_panel_id'); }
}
