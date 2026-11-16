<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrugInteraction extends Model
{
    protected $fillable = [
        'drug_a_id', 'drug_b_id', 'severity', 'effect', 'description',
        'clinical_action', 'source', 'status',
    ];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function drugA(): BelongsTo { return $this->belongsTo(Drug::class, 'drug_a_id'); }
    public function drugB(): BelongsTo { return $this->belongsTo(Drug::class, 'drug_b_id'); }
}
