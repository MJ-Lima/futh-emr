<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class AuditFollowUp extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'follow_up_number', 'hospital_id', 'audit_type',
        'medical_audit_id', 'internal_audit_id', 'finding_id',
        'followed_up_by', 'follow_up_date', 'progress_notes',
        'evidence_provided', 'attachments', 'progress_status',
        'completion_percentage', 'next_follow_up_date', 'notes',
    ];
    protected function casts(): array {
        return [
            'follow_up_date' => 'date', 'next_follow_up_date' => 'date',
            'completion_percentage' => 'integer', 'attachments' => 'array',
        ];
    }
    protected static function booted() {
        static::creating(function ($f) {
            if (empty($f->followed_up_by) && Auth::check()) $f->followed_up_by = Auth::id();
            if (empty($f->hospital_id) && Auth::check()) $f->hospital_id = Auth::user()->hospital_id;
            if (empty($f->follow_up_date)) $f->follow_up_date = now();
            if (empty($f->follow_up_number)) {
                $prefix = 'AFU'; $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->follow_up_number, -6)) + 1 : 1;
                $f->follow_up_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function medicalAudit(): BelongsTo { return $this->belongsTo(MedicalAudit::class); }
    public function internalAudit(): BelongsTo { return $this->belongsTo(InternalAudit::class); }
    public function follower(): BelongsTo { return $this->belongsTo(User::class, 'followed_up_by'); }
}
