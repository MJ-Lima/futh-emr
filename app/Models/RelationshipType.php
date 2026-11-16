<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RelationshipType extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'code', 'category', 'description', 'is_symmetric', 'is_active'];
    protected function casts(): array { return ['is_symmetric' => 'boolean', 'is_active' => 'boolean']; }
    public function relationships(): HasMany { return $this->hasMany(PatientRelationship::class); }
}
