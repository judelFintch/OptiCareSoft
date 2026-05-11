<?php

namespace App\Models;

use App\Enums\StockMovementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    protected $fillable = [
        'stockable_type', 'stockable_id', 'movement_type',
        'quantity', 'stock_before', 'stock_after',
        'unit_cost', 'reference', 'notes', 'created_by',
    ];

    protected $casts = [
        'movement_type' => StockMovementType::class,
        'unit_cost'     => 'decimal:2',
    ];

    public function stockable(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
