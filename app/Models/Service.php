<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Service extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name', 'code', 'description', 'category',
        'default_price', 'currency_id', 'is_active',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'default_price' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'consultation' => 'Consultation',
            'exam'         => 'Examen',
            'optical'      => 'Optique',
            'pharmacy'     => 'Pharmacie',
            default        => 'Général',
        };
    }
}
