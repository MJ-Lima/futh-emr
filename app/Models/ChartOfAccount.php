<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChartOfAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'account_code', 'name', 'type', 'sub_type',
        'currency', 'parent_account_id', 'opening_balance', 'current_balance',
        'is_active', 'is_bank_account', 'bank_name', 'bank_account_number',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'opening_balance' => 'decimal:2',
            'current_balance' => 'decimal:2',
            'is_active'       => 'boolean',
            'is_bank_account' => 'boolean',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function parent(): BelongsTo { return $this->belongsTo(ChartOfAccount::class, 'parent_account_id'); }
    public function children(): HasMany { return $this->hasMany(ChartOfAccount::class, 'parent_account_id'); }
    public function journalLines(): HasMany { return $this->hasMany(JournalLine::class); }
    public function budgets(): HasMany { return $this->hasMany(Budget::class); }

    public function applyDebit(float $amount): void
    {
        $this->increment('current_balance', $amount);
    }

    public function applyCredit(float $amount): void
    {
        $this->decrement('current_balance', $amount);
    }
}
