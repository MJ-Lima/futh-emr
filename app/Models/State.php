<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
     protected $table = 'states';

    protected $primaryKey = 'state_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'name',
    ];

    public function locals(): HasMany
    {
        return $this->hasMany(Local::class, 'state_id', 'state_id');
    }
}
