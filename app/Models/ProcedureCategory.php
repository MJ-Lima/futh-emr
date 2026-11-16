<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcedureCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'name', 'code', 'specialty', 'description',
        'requires_consent', 'requires_anaesthesia', 'requires_pre_op_assessment', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requires_consent'             => 'boolean',
            'requires_anaesthesia'         => 'boolean',
            'requires_pre_op_assessment'   => 'boolean',
            'is_active'                    => 'boolean',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function procedures(): HasMany { return $this->hasMany(Procedure::class); }
}
