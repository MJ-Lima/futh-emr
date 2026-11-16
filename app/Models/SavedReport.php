<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SavedReport extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'report_number', 'hospital_id', 'report_template_id', 'title', 'category',
        'period_from', 'period_to', 'department_id', 'clinic_id', 'user_id',
        'parameters', 'data', 'summary', 'record_count', 'total_value',
        'file_path', 'format', 'status', 'error_message', 'generated_by', 'generated_at',
    ];
    protected function casts(): array {
        return [
            'period_from' => 'date', 'period_to' => 'date',
            'parameters' => 'array', 'data' => 'array', 'summary' => 'array',
            'record_count' => 'integer', 'total_value' => 'decimal:2',
            'generated_at' => 'datetime',
        ];
    }
    protected static function booted() {
        static::creating(function ($r) {
            if (empty($r->generated_by) && Auth::check()) $r->generated_by = Auth::id();
            if (empty($r->hospital_id) && Auth::check()) $r->hospital_id = Auth::user()->hospital_id;
            if (empty($r->generated_at)) $r->generated_at = now();
            if (empty($r->report_number)) {
                $prefix = 'RPT'; $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->report_number, -6)) + 1 : 1;
                $r->report_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function template(): BelongsTo { return $this->belongsTo(ReportTemplate::class, 'report_template_id'); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function clinic(): BelongsTo { return $this->belongsTo(Clinic::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function generator(): BelongsTo { return $this->belongsTo(User::class, 'generated_by'); }
}
