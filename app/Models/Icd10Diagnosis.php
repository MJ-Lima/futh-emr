<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icd10Diagnosis extends Model
{
    protected $fillable = [
        'code', 'description', 'category',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Search by code or description, returning a list.
     */
    public function scopeSearch($query, ?string $term)
    {
        return $query->when($term, fn ($q) => $q->where(function ($inner) use ($term) {
            $inner->where('code', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
        }));
    }
}
