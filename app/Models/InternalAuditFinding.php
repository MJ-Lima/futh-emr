<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalAuditFinding extends Model
{
    protected $fillable = [
        'internal_audit_id', 'finding_number', 'category', 'title', 'description',
        'criteria', 'condition_observed', 'cause', 'effect', 'evidence',
        'financial_impact', 'severity', 'risk_level',
        'recommendation', 'management_response', 'corrective_action',
        'responsible_user_id', 'department_id', 'target_completion_date',
        'status', 'date_closed', 'closure_notes',
    ];
    protected function casts(): array {
        return ['target_completion_date' => 'date', 'date_closed' => 'date', 'financial_impact' => 'decimal:2'];
    }
    public function audit(): BelongsTo { return $this->belongsTo(InternalAudit::class, 'internal_audit_id'); }
    public function responsibleUser(): BelongsTo { return $this->belongsTo(User::class, 'responsible_user_id'); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
}
