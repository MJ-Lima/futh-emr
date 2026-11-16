<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'entry_number', 'hospital_id', 'entry_date', 'type',
        'reference_number', 'reference_type', 'reference_id',
        'description', 'total_debit', 'total_credit',
        'is_posted', 'is_reversed', 'posted_at',
        'created_by', 'posted_by',
    ];

    protected function casts(): array
    {
        return [
            'entry_date'   => 'date',
            'posted_at'    => 'datetime',
            'total_debit'  => 'decimal:2',
            'total_credit' => 'decimal:2',
            'is_posted'    => 'boolean',
            'is_reversed'  => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($e) {
            if (empty($e->created_by) && Auth::check()) $e->created_by = Auth::id();
            if (empty($e->hospital_id) && Auth::check()) $e->hospital_id = Auth::user()->hospital_id;
            if (empty($e->entry_date)) $e->entry_date = now();
            if (empty($e->entry_number)) {
                $prefix = 'JE';
                $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->entry_number, -6)) + 1 : 1;
                $e->entry_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });

        static::saved(function ($e) {
            // Recalculate totals from lines
            $e->total_debit  = $e->lines()->sum('debit');
            $e->total_credit = $e->lines()->sum('credit');
            // Use query to avoid firing saved event recursively
            DB::table('journal_entries')->where('id', $e->id)->update([
                'total_debit'  => $e->total_debit,
                'total_credit' => $e->total_credit,
            ]);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function poster(): BelongsTo { return $this->belongsTo(User::class, 'posted_by'); }
    public function lines(): HasMany { return $this->hasMany(JournalLine::class); }

    public function isBalanced(): bool
    {
        return bccomp((string) $this->total_debit, (string) $this->total_credit, 2) === 0;
    }

    public function post(): bool
    {
        if ($this->is_posted || ! $this->isBalanced()) return false;
        DB::transaction(function () {
            foreach ($this->lines as $line) {
                if ($line->debit > 0)  $line->chartOfAccount->applyDebit((float) $line->debit);
                if ($line->credit > 0) $line->chartOfAccount->applyCredit((float) $line->credit);
            }
            $this->update([
                'is_posted' => true,
                'posted_at' => now(),
                'posted_by' => Auth::id(),
            ]);
        });
        return true;
    }

    public function reverse(string $reason = null): bool
    {
        if ($this->is_reversed || ! $this->is_posted) return false;
        DB::transaction(function () use ($reason) {
            foreach ($this->lines as $line) {
                if ($line->debit > 0)  $line->chartOfAccount->applyCredit((float) $line->debit);
                if ($line->credit > 0) $line->chartOfAccount->applyDebit((float) $line->credit);
            }
            $this->update(['is_reversed' => true]);
        });
        return true;
    }
}
