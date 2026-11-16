<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Local extends Model
{
    protected $table = 'locals';

    protected $primaryKey = 'local_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'state_id',
        'local_name',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }
}
