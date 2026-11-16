<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DispensingPoint extends Model
{
    protected $fillable = ['hospital_id', 'name', 'code', 'location', 'in_charge_id', 'status'];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function inCharge(): BelongsTo { return $this->belongsTo(User::class, 'in_charge_id'); }
    public function prescriptions(): HasMany { return $this->hasMany(Prescription::class); }
    public function stockMovements(): HasMany { return $this->hasMany(StockMovement::class); }
}
