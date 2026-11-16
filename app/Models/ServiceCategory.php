<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'parent_id', 'name', 'code', 'type',
        'color', 'description', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function parent(): BelongsTo { return $this->belongsTo(ServiceCategory::class, 'parent_id'); }
    public function children(): HasMany { return $this->hasMany(ServiceCategory::class, 'parent_id'); }
    public function services(): HasMany { return $this->hasMany(Service::class); }
}
