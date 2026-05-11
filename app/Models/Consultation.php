<?php

namespace App\Models;

use App\Enums\ConsultationStatus;
use App\Enums\DiagnosisType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Consultation extends Model
{
    use LogsActivity;

    protected $fillable = [
        'consultation_code', 'patient_id', 'doctor_id', 'visit_id',
        'chief_complaint', 'history_of_present_illness', 'medical_history',
        'ophthalmic_history', 'current_medications', 'clinical_findings',
        'primary_diagnosis', 'secondary_diagnoses', 'icd_code',
        'treatment_plan', 'recommendations', 'next_appointment_date',
        'status', 'signed_at',
    ];

    protected $casts = [
        'status'                => ConsultationStatus::class,
        'primary_diagnosis'     => DiagnosisType::class,
        'secondary_diagnoses'   => 'array',
        'next_appointment_date' => 'date',
        'signed_at'             => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function visualAcuity(): HasOne
    {
        return $this->hasOne(VisualAcuity::class);
    }

    public function refraction(): HasOne
    {
        return $this->hasOne(Refraction::class);
    }

    public function eyePressure(): HasOne
    {
        return $this->hasOne(EyePressure::class);
    }

    public function medicalPrescriptions(): HasMany
    {
        return $this->hasMany(MedicalPrescription::class);
    }

    public function opticalPrescriptions(): HasMany
    {
        return $this->hasMany(OpticalPrescription::class);
    }

    public function ophthalmicExams(): HasMany
    {
        return $this->hasMany(OphthalmicExam::class);
    }

    public function isDraft(): bool
    {
        return $this->status === ConsultationStatus::Draft;
    }

    public function isSigned(): bool
    {
        return $this->status === ConsultationStatus::Signed;
    }
}
