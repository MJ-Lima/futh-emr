<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'name', 'code', 'contact_person', 'phone', 'email',
        'address', 'tax_id', 'registration_number', 'status',
    ];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function purchases(): HasMany { return $this->hasMany(Purchase::class); }
    public function receipts(): HasMany { return $this->hasMany(Receipt::class); }
}
