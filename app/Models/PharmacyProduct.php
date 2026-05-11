<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PharmacyProduct extends Model
{
    protected $fillable = [
        'reference', 'name', 'generic_name', 'category', 'form',
        'dosage', 'manufacturer', 'purchase_price', 'selling_price',
        'stock_quantity', 'reorder_level', 'expiry_date',
        'is_prescription_required', 'supplier_id', 'is_active',
    ];

    protected $casts = [
        'purchase_price'          => 'decimal:2',
        'selling_price'           => 'decimal:2',
        'expiry_date'             => 'date',
        'is_prescription_required'=> 'boolean',
        'is_active'               => 'boolean',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(PharmacySaleItem::class);
    }

    public function stockMovements(): MorphMany
    {
        return $this->morphMany(StockMovement::class, 'stockable');
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_level;
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->expiry_date && $this->expiry_date->diffInDays(now()) <= $days;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
