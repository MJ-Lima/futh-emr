<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralStoreLocation extends Model
{
    use SoftDeletes;
    protected $fillable = ['hospital_id', 'department_id', 'name', 'code', 'type', 'building', 'floor', 'room', 'in_charge_id', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function inCharge(): BelongsTo { return $this->belongsTo(User::class, 'in_charge_id'); }
    public function stocks(): HasMany { return $this->hasMany(GeneralStoreStock::class); }
}
