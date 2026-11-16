<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicTemplate extends Model
{
    protected $fillable = [
        'clinic_id', 'clinic_type', 'name', 'section_type',
        'fields_json', 'description', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const SECTION_TYPES = [
        'physical_history' => 'Physical History',
        'general_history' => 'General History',
        'consultation' => 'General Consultation',
        'specific_template' => 'Clinic-Specific Template',
    ];

    public const CLINIC_TYPES = [
        'General' => 'General',
        'ANC' => 'Antenatal Care (ANC)',
        'SOPD' => 'Surgical Outpatient (SOPD)',
        'Dental' => 'Dental',
        'ENT' => 'Ear, Nose, Throat (ENT)',
        'Paediatric' => 'Paediatric',
        'Eye' => 'Eye / Ophthalmology',
        'Psychiatry' => 'Psychiatry',
        'Psychology' => 'Psychology',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get fields as a decoded array.
     */
    public function getFieldsAttribute(): array
    {
        $raw = $this->fields_json;
        if (!$raw) return [];

        // Try json_decode first (proper JSON)
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) return $decoded;

        // Fallback: it's a PHP array literal like ["Field 1","Field 2"]
        $cleaned = trim($raw, "[] \t\n\r");
        if (empty($cleaned)) return [];

        // Split on comma-with-quote, strip surrounding quotes
        $parts = preg_split('/","/', $cleaned);
        return array_map(fn ($p) => trim($p, '"\' '), $parts);
    }
}
