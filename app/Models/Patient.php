<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\PatientStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Patient extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'patient_code', 'first_name', 'last_name', 'gender', 'birth_date',
        'phone', 'email', 'address', 'city', 'profession', 'nationality',
        'emergency_contact_name', 'emergency_contact_phone',
        'medical_history', 'ophthalmic_history', 'allergies',
        'current_medications', 'photo', 'status', 'created_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'gender'     => Gender::class,
        'status'     => PatientStatus::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    // Relations
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function activeVisit(): HasOne
    {
        return $this->hasOne(Visit::class)->whereIn('status', ['open', 'in_progress']);
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class)->latest();
    }

    public function opticalPrescriptions(): HasMany
    {
        return $this->hasMany(OpticalPrescription::class)->latest();
    }

    public function medicalPrescriptions(): HasMany
    {
        return $this->hasMany(MedicalPrescription::class)->latest();
    }

    public function opticalOrders(): HasMany
    {
        return $this->hasMany(OpticalOrder::class)->latest();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class)->latest();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->latest();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PatientDocument::class)->latest();
    }

    public function ophthalmicExams(): HasMany
    {
        return $this->hasMany(OphthalmicExam::class)->latest();
    }

    public function getTotalDebtAttribute(): float
    {
        return $this->invoices()
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->sum('remaining_amount');
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('status', PatientStatus::Active->value);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('first_name', 'like', "%{$term}%")
              ->orWhere('last_name', 'like', "%{$term}%")
              ->orWhere('patient_code', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }
}
