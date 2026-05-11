<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EyePressure extends Model
{
    protected $fillable = [
        'consultation_id', 'patient_id',
        'right_eye_pressure', 'left_eye_pressure',
        'measurement_method', 'measured_at', 'remarks',
    ];

    protected $casts = [
        'right_eye_pressure' => 'decimal:1',
        'left_eye_pressure'  => 'decimal:1',
        'measured_at'        => 'datetime',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    // Normal IOP range: 10-21 mmHg
    public function isRightEyeNormal(): bool
    {
        return $this->right_eye_pressure >= 10 && $this->right_eye_pressure <= 21;
    }

    public function isLeftEyeNormal(): bool
    {
        return $this->left_eye_pressure >= 10 && $this->left_eye_pressure <= 21;
    }
}
