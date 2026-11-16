<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'service_category_id', 'name', 'code', 'category', 'sub_category',
        'nhia_code', 'standard_price', 'nhia_rate', 'private_rate',
        'cost_price', 'unit_of_measure', 'is_taxable', 'tax_percentage',
        'description', 'requires_pre_authorization', 'status',
    ];

    protected function casts(): array
    {
        return [
            'standard_price'  => 'decimal:2',
            'nhia_rate'       => 'decimal:2',
            'private_rate'    => 'decimal:2',
            'cost_price'      => 'decimal:2',
            'tax_percentage'  => 'decimal:2',
            'is_taxable'      => 'boolean',
            'requires_pre_authorization' => 'boolean',
            'status'          => 'string',
        ];
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function serviceCategory(): BelongsTo { return $this->belongsTo(ServiceCategory::class); }
    public function invoiceItems(): HasMany { return $this->hasMany(InvoiceItem::class); }
    public function procedures(): HasMany { return $this->hasMany(Procedure::class); }
}
