<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PresentingComplaint extends Model
{
    protected $fillable = [
        'name', 'category', 'code', 'description',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const CATEGORIES = [
        'Constitutional' => 'Constitutional',
        'Respiratory' => 'Respiratory',
        'Cardiovascular' => 'Cardiovascular',
        'Gastrointestinal' => 'Gastrointestinal',
        'O&G' => 'Obstetric & Gynaecology',
        'Genitourinary' => 'Genitourinary',
        'Neurological' => 'Neurological',
        'Musculoskeletal' => 'Musculoskeletal',
        'Dermatological' => 'Dermatological',
        'ENT' => 'ENT (Ear, Nose, Throat)',
        'Eye' => 'Eye',
        'Dental' => 'Dental',
        'Paediatric' => 'Paediatric',
        'Psychiatric' => 'Psychiatric',
        'Psychology' => 'Psychology',
    ];

    public function encounters(): BelongsToMany
    {
        return $this->belongsToMany(Encounter::class, 'encounter_presenting_complaints')
            ->withPivot(['duration', 'description'])
            ->withTimestamps();
    }
}
