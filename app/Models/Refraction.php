<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refraction extends Model
{
    protected $fillable = [
        'consultation_id', 'patient_id',
        'right_sphere', 'right_cylinder', 'right_axis', 'right_addition',
        'left_sphere', 'left_cylinder', 'left_axis', 'left_addition',
        'pd_right', 'pd_left', 'lens_type', 'remarks',
    ];

    protected $casts = [
        'right_sphere'   => 'decimal:2',
        'right_cylinder' => 'decimal:2',
        'right_addition' => 'decimal:2',
        'left_sphere'    => 'decimal:2',
        'left_cylinder'  => 'decimal:2',
        'left_addition'  => 'decimal:2',
        'pd_right'       => 'decimal:1',
        'pd_left'        => 'decimal:1',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function formatValue(float $value): string
    {
        return ($value >= 0 ? '+' : '') . number_format($value, 2);
    }
}
