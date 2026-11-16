<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PatientRelationship extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'hospital_id', 'patient_id', 'related_patient_id', 'relationship_type_id',
        'related_party_name', 'related_party_phone', 'related_party_email', 'related_party_address',
        'notes', 'effective_date', 'end_date',
        'is_primary_contact', 'is_emergency_contact', 'can_make_decisions',
        'status', 'created_by',
    ];
    protected function casts(): array {
        return [
            'effective_date' => 'date', 'end_date' => 'date',
            'is_primary_contact' => 'boolean', 'is_emergency_contact' => 'boolean',
            'can_make_decisions' => 'boolean',
        ];
    }
    protected static function booted() {
        static::creating(function ($r) {
            if (empty($r->created_by) && Auth::check()) $r->created_by = Auth::id();
            if (empty($r->hospital_id) && Auth::check()) $r->hospital_id = Auth::user()->hospital_id;
        });
    }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function relatedPatient(): BelongsTo { return $this->belongsTo(Patient::class, 'related_patient_id'); }
    public function relationshipType(): BelongsTo { return $this->belongsTo(RelationshipType::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}
