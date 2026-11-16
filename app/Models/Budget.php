<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Budget extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'department_id', 'chart_of_account_id',
        'name', 'code', 'period', 'fiscal_year', 'quarter', 'month',
        'planned_amount', 'actual_amount', 'variance',
        'status', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'planned_amount' => 'decimal:2',
            'actual_amount'  => 'decimal:2',
            'variance'       => 'decimal:2',
            'fiscal_year'    => 'integer',
            'quarter'        => 'integer',
            'month'          => 'integer',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($b) {
            if (empty($b->created_by) && Auth::check()) $b->created_by = Auth::id();
            if (empty($b->hospital_id) && Auth::check()) $b->hospital_id = Auth::user()->hospital_id;
            if (empty($b->code)) {
                $year = $b->fiscal_year ?? now()->year;
                $last = self::withTrashed()->where('fiscal_year', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->code, -4)) + 1 : 1;
                $b->code = sprintf('BGT-%s-%04d', $year, $next);
            }
        });

        static::saved(function ($b) {
            $b->variance = (float) $b->planned_amount - (float) $b->actual_amount;
            \Illuminate\Support\Facades\DB::table('budgets')->where('id', $b->id)
                ->update(['variance' => $b->variance]);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function chartOfAccount(): BelongsTo { return $this->belongsTo(ChartOfAccount::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function isOverBudget(): bool
    {
        return (float) $this->actual_amount > (float) $this->planned_amount;
    }

    public function utilizationPercent(): float
    {
        $planned = (float) $this->planned_amount;
        return $planned > 0 ? round((float) $this->actual_amount / $planned * 100, 1) : 0;
    }
}
