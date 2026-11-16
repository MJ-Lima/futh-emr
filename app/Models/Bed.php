<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bed extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ward_id', 'bed_number', 'code', 'type', 'class',
        'daily_rate', 'status', 'notes', 'current_admission_id',
    ];

    protected function casts(): array
    {
        return ['daily_rate' => 'decimal:2'];
    }

    public function ward(): BelongsTo { return $this->belongsTo(Ward::class); }
    public function currentAdmission(): BelongsTo { return $this->belongsTo(Admission::class, 'current_admission_id'); }

    public function isAvailable(): bool
    {
        return $this->status === 'Available';
    }

    public function isOccupied(): bool
    {
        return $this->status === 'Occupied';
    }

    /**
     * Mark this bed as occupied by the given admission.
     */
    public function assign(Admission $admission): void
    {
        $this->update([
            'status' => 'Occupied',
            'current_admission_id' => $admission->id,
        ]);
    }

    /**
     * Free the bed (mark as Available / Cleaning).
     */
    public function release(string $nextStatus = 'Cleaning'): void
    {
        $this->update([
            'status' => $nextStatus,
            'current_admission_id' => null,
        ]);
    }
}
