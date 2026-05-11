<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $fillable = [
        'code', 'name', 'symbol', 'exchange_rate', 'is_default', 'is_active',
    ];

    protected $casts = [
        'is_default'    => 'boolean',
        'is_active'     => 'boolean',
        'exchange_rate' => 'decimal:6',
    ];

    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->first();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
