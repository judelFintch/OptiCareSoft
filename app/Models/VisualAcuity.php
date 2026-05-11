<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisualAcuity extends Model
{
    protected $fillable = [
        'consultation_id', 'patient_id',
        'right_eye_sc', 'left_eye_sc',
        'right_eye_cc', 'left_eye_cc',
        'near_right_sc', 'near_left_sc',
        'near_right_cc', 'near_left_cc',
        'remarks',
    ];

    protected $table = 'visual_acuities';

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
