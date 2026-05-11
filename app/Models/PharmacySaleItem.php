<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PharmacySaleItem extends Model
{
    protected $fillable = [
        'pharmacy_sale_id', 'pharmacy_product_id',
        'quantity', 'unit_price', 'total',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total'      => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(PharmacySale::class, 'pharmacy_sale_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(PharmacyProduct::class);
    }
}
