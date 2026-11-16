<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ExpenseClaim extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'claim_number', 'hospital_id', 'claimant_id', 'department_id',
        'title', 'type', 'expense_date',
        'total_amount', 'reimbursable_amount', 'approved_amount',
        'currency', 'description', 'receipts',
        'status', 'reviewed_by', 'reviewed_at',
        'approved_by', 'approved_at',
        'review_notes', 'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'expense_date'        => 'date',
            'total_amount'        => 'decimal:2',
            'reimbursable_amount' => 'decimal:2',
            'approved_amount'     => 'decimal:2',
            'reviewed_at'         => 'datetime',
            'approved_at'         => 'datetime',
            'receipts'            => 'array',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($c) {
            if (empty($c->claimant_id) && Auth::check()) $c->claimant_id = Auth::id();
            if (empty($c->hospital_id) && Auth::check()) $c->hospital_id = Auth::user()->hospital_id;
            if (empty($c->claim_number)) {
                $prefix = 'EXP';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->claim_number, -6)) + 1 : 1;
                $c->claim_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function claimant(): BelongsTo { return $this->belongsTo(User::class, 'claimant_id'); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
}
