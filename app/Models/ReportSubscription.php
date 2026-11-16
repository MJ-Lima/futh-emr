<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportSubscription extends Model
{
    protected $fillable = [
        'user_id', 'report_template_id', 'report_schedule_id',
        'delivery_method', 'filter_preferences', 'is_active',
    ];
    protected function casts(): array {
        return ['filter_preferences' => 'array', 'is_active' => 'boolean'];
    }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function template(): BelongsTo { return $this->belongsTo(ReportTemplate::class, 'report_template_id'); }
    public function schedule(): BelongsTo { return $this->belongsTo(ReportSchedule::class, 'report_schedule_id'); }
}
