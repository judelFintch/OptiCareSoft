<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class PharmacySale extends Model
{
    use LogsActivity;

    protected $fillable = [
        'sale_number', 'patient_id', 'medical_prescription_id',
        'total_amount', 'payment_status', 'served_by',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function medicalPrescription(): BelongsTo
    {
        return $this->belongsTo(MedicalPrescription::class);
    }

    public function servedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'served_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PharmacySaleItem::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'paid'    => 'Payé',
            'unpaid'  => 'Impayé',
            default   => ucfirst($this->payment_status),
        };
    }
}
