<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ClinicalNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'patient_id', 'encounter_id', 'author_id', 'note_number',
        'note_type', 'title', 'subjective', 'objective', 'assessment', 'plan',
        'narrative', 'tags', 'is_signed', 'signed_at', 'signed_by',
    ];

    protected function casts(): array
    {
        return [
            'is_signed'  => 'boolean',
            'signed_at'  => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($n) {
            if (empty($n->author_id) && Auth::check()) $n->author_id = Auth::id();
            if (empty($n->hospital_id) && Auth::check()) $n->hospital_id = Auth::user()->hospital_id;
            $prefix = 'NOTE';
            $year = now()->year;
            $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
            $next = $last ? ((int) substr($last->note_number, -6)) + 1 : 1;
            $n->note_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function signer(): BelongsTo { return $this->belongsTo(User::class, 'signed_by'); }

    public function sign(): bool
    {
        if ($this->is_signed) return false;
        $this->update([
            'is_signed'  => true,
            'signed_at'  => now(),
            'signed_by'  => Auth::id(),
        ]);
        return true;
    }
}
