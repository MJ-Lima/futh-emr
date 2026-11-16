<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class GeneralStoreMovement extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'movement_number', 'hospital_id', 'general_store_item_id', 'general_store_stock_id',
        'from_location_id', 'to_location_id', 'user_id', 'type',
        'reference_number', 'reference_type', 'reference_id',
        'movement_date', 'quantity', 'balance_after', 'reason',
    ];
    protected function casts(): array {
        return ['movement_date' => 'datetime', 'quantity' => 'decimal:2', 'balance_after' => 'decimal:2'];
    }
    protected static function booted() {
        static::creating(function ($m) {
            if (empty($m->user_id) && Auth::check()) $m->user_id = Auth::id();
            if (empty($m->hospital_id) && Auth::check()) $m->hospital_id = Auth::user()->hospital_id;
            if (empty($m->movement_date)) $m->movement_date = now();
            if (empty($m->movement_number)) {
                $prefix = 'GSM'; $year = now()->year;
                $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
                $next = $last ? ((int) substr($last->movement_number, -6)) + 1 : 1;
                $m->movement_number = sprintf('%s-%s-%06d', $prefix, $year, $next);
            }
        });
    }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function item(): BelongsTo { return $this->belongsTo(GeneralStoreItem::class, 'general_store_item_id'); }
    public function stock(): BelongsTo { return $this->belongsTo(GeneralStoreStock::class, 'general_store_stock_id'); }
    public function fromLocation(): BelongsTo { return $this->belongsTo(GeneralStoreLocation::class, 'from_location_id'); }
    public function toLocation(): BelongsTo { return $this->belongsTo(GeneralStoreLocation::class, 'to_location_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
