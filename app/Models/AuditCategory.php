<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditCategory extends Model
{
    use SoftDeletes;
    protected $fillable = ['hospital_id', 'name', 'code', 'type', 'description', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function medicalAudits(): HasMany { return $this->hasMany(MedicalAudit::class); }
    public function internalAudits(): HasMany { return $this->hasMany(InternalAudit::class); }
}
