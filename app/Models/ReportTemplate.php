<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ReportTemplate extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'hospital_id', 'name', 'code', 'category', 'frequency', 'description',
        'parameters', 'columns', 'filters', 'group_by', 'chart_config',
        'data_source', 'sql_query', 'is_scheduled', 'is_active', 'created_by',
    ];
    protected function casts(): array {
        return [
            'parameters' => 'array', 'columns' => 'array', 'filters' => 'array',
            'group_by' => 'array', 'chart_config' => 'array',
            'is_scheduled' => 'boolean', 'is_active' => 'boolean',
        ];
    }
    protected static function booted() {
        static::creating(function ($t) {
            if (empty($t->created_by) && Auth::check()) $t->created_by = Auth::id();
            if (empty($t->hospital_id) && Auth::check()) $t->hospital_id = Auth::user()->hospital_id;
        });
    }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function savedReports(): HasMany { return $this->hasMany(SavedReport::class); }
    public function schedules(): HasMany { return $this->hasMany(ReportSchedule::class); }
}
