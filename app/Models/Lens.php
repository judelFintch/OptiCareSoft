<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lens extends Model
{
    protected $fillable = [
        'reference', 'brand', 'type', 'index', 'treatment',
        'purchase_price', 'selling_price',
        'stock_quantity', 'reorder_level', 'supplier_id', 'is_active',
    ];

    protected $casts = [
        'index'          => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'selling_price'  => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_level;
    }

    public function getFullDescriptionAttribute(): string
    {
        return "{$this->brand} {$this->type} {$this->index} {$this->treatment}";
    }
}
