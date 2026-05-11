<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Frame extends Model
{
    protected $fillable = [
        'reference', 'brand', 'model', 'color', 'material',
        'category', 'size', 'purchase_price', 'selling_price',
        'stock_quantity', 'reorder_level', 'supplier_id', 'photo', 'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price'  => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function opticalOrders(): HasMany
    {
        return $this->hasMany(OpticalOrder::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_level;
    }
}
