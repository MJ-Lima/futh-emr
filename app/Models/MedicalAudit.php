<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class MedicalAudit extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'audit_number', 'hospital_id', 'audit_category_id', 'department_id', 'clinic_id',
        'patient_id', 'encounter_id', 'admission_id', 'auditor_id', 'reviewed_by',
        'audit_type', 'audit_date', 'period_from', 'period_to',
        'audit_scope', 'objectives', 'methodology', 'summary',
        'key_findings', 'recommendations', 'action_plan', 'risk_level',
        'status', 'review_date', 'review_notes', 'notes', 'created_by',
    ];
    protected function casts(): array {
        return ['audit_date' => 'date', 'period_from' => 'date', 'period_to' => 'date', 'review_date' => 'date'];
    }
    protected static function booted() {
        static::creating(function ($a) {
            if (empty($a->created_by) && Auth::check()) $a->created_by = Auth::id();
            if (empty($a->auditor_id) && Auth::check()) $a->auditor_id = Auth::id();
            if (empty($a->hospital_id) && Auth::check()) $a->hospital_id = Auth::user()->hospital_id;
            if (empty($a->audit_date)) $a->audit_date = now();
            if (empty($a->audit_number)) {
                $prefix = 'MAU'; $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->audit_number, -6)) + 1 : 1;
                $a->audit_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function category(): BelongsTo { return $this->belongsTo(AuditCategory::class, 'audit_category_id'); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function clinic(): BelongsTo { return $this->belongsTo(Clinic::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function admission(): BelongsTo { return $this->belongsTo(Admission::class); }
    public function auditor(): BelongsTo { return $this->belongsTo(User::class, 'auditor_id'); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function findings(): HasMany { return $this->hasMany(MedicalAuditFinding::class); }
    public function followUps(): HasMany { return $this->hasMany(AuditFollowUp::class); }
}
