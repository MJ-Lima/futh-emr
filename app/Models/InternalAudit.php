<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class InternalAudit extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'audit_number', 'hospital_id', 'audit_category_id', 'department_id',
        'auditor_id', 'lead_auditor_id', 'audit_type', 'audit_date',
        'period_from', 'period_to', 'audit_scope', 'objectives', 'methodology',
        'documents_reviewed', 'persons_interviewed', 'summary',
        'key_findings', 'recommendations', 'management_response', 'action_plan',
        'financial_impact', 'risk_level', 'status',
        'report_date', 'management_response_date', 'final_report_date', 'closure_date',
        'notes', 'created_by',
    ];
    protected function casts(): array {
        return [
            'audit_date' => 'date', 'period_from' => 'date', 'period_to' => 'date',
            'report_date' => 'date', 'management_response_date' => 'date',
            'final_report_date' => 'date', 'closure_date' => 'date',
            'financial_impact' => 'decimal:2',
        ];
    }
    protected static function booted() {
        static::creating(function ($a) {
            if (empty($a->created_by) && Auth::check()) $a->created_by = Auth::id();
            if (empty($a->auditor_id) && Auth::check()) $a->auditor_id = Auth::id();
            if (empty($a->hospital_id) && Auth::check()) $a->hospital_id = Auth::user()->hospital_id;
            if (empty($a->audit_date)) $a->audit_date = now();
            if (empty($a->audit_number)) {
                $prefix = 'IAU'; $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->audit_number, -6)) + 1 : 1;
                $a->audit_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function category(): BelongsTo { return $this->belongsTo(AuditCategory::class, 'audit_category_id'); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function auditor(): BelongsTo { return $this->belongsTo(User::class, 'auditor_id'); }
    public function leadAuditor(): BelongsTo { return $this->belongsTo(User::class, 'lead_auditor_id'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function findings(): HasMany { return $this->hasMany(InternalAuditFinding::class); }
    public function followUps(): HasMany { return $this->hasMany(AuditFollowUp::class); }
}
