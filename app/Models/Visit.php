<?php

namespace App\Models;

use App\Enums\VisitStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Visit extends Model
{
    use LogsActivity;

    protected $fillable = [
        'visit_code', 'patient_id', 'appointment_id', 'status',
        'notes', 'opened_by', 'closed_by', 'opened_at', 'closed_at',
    ];

    protected $casts = [
        'status'    => VisitStatus::class,
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function opener(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function isOpen(): bool
    {
        return in_array($this->status->value, ['open', 'in_progress']);
    }

    public function getDurationAttribute(): ?string
    {
        if (! $this->closed_at) {
            return null;
        }
        $minutes = $this->opened_at->diffInMinutes($this->closed_at);
        return floor($minutes / 60) . 'h ' . ($minutes % 60) . 'min';
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', [VisitStatus::Open->value, VisitStatus::InProgress->value]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('opened_at', today());
    }
}
