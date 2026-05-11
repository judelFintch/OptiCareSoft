<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Appointment extends Model
{
    use LogsActivity;

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_date', 'appointment_time',
        'duration_minutes', 'reason', 'status', 'notes', 'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'status'           => AppointmentStatus::class,
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function visit(): HasOne
    {
        return $this->hasOne(Visit::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeForDoctor($query, int $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [
            AppointmentStatus::Cancelled->value,
            AppointmentStatus::Missed->value,
        ]);
    }

    public function canBeConfirmed(): bool
    {
        return $this->status === AppointmentStatus::Scheduled;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            AppointmentStatus::Scheduled,
            AppointmentStatus::Confirmed,
        ]);
    }
}
