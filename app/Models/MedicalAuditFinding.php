<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalAuditFinding extends Model
{
    protected $fillable = [
        'medical_audit_id', 'finding_number', 'category', 'title', 'description',
        'criteria', 'condition_observed', 'cause', 'effect',
        'severity', 'risk_level', 'recommendation', 'corrective_action',
        'responsible_user_id', 'target_completion_date', 'status',
        'date_closed', 'closure_notes',
    ];
    protected function casts(): array {
        return ['target_completion_date' => 'date', 'date_closed' => 'date'];
    }
    public function audit(): BelongsTo { return $this->belongsTo(MedicalAudit::class, 'medical_audit_id'); }
    public function responsibleUser(): BelongsTo { return $this->belongsTo(User::class, 'responsible_user_id'); }
}
