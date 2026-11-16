<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ReportSchedule extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'hospital_id', 'report_template_id', 'name', 'frequency', 'run_at',
        'day_of_week', 'day_of_month', 'month_of_quarter',
        'parameters', 'recipients', 'department_id', 'user_id',
        'last_run_at', 'next_run_at', 'is_active', 'created_by',
    ];
    protected function casts(): array {
        return [
            'run_at' => 'datetime', 'parameters' => 'array', 'recipients' => 'array',
            'day_of_week' => 'integer', 'day_of_month' => 'integer', 'month_of_quarter' => 'integer',
            'last_run_at' => 'datetime', 'next_run_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
    protected static function booted() {
        static::creating(function ($s) {
            if (empty($s->created_by) && Auth::check()) $s->created_by = Auth::id();
            if (empty($s->hospital_id) && Auth::check()) $s->hospital_id = Auth::user()->hospital_id;
        });
    }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function template(): BelongsTo { return $this->belongsTo(ReportTemplate::class, 'report_template_id'); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}
