<?php

namespace App\Models;

use App\Enums\ExamType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OphthalmicExam extends Model
{
    protected $fillable = [
        'exam_code', 'patient_id', 'consultation_id', 'doctor_id',
        'exam_type', 'exam_date', 'result', 'interpretation',
        'file_path', 'status', 'notes',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'exam_type' => ExamType::class,
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
