<?php

namespace App\Models;

use App\Enums\LensType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpticalPrescription extends Model
{
    protected $fillable = [
        'prescription_number', 'consultation_id', 'patient_id', 'doctor_id',
        'right_sphere', 'right_cylinder', 'right_axis', 'right_addition',
        'left_sphere', 'left_cylinder', 'left_axis', 'left_addition',
        'pd_right', 'pd_left', 'lens_type', 'usage', 'valid_until', 'remarks',
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
        'valid_until'    => 'date',
        'lens_type'      => LensType::class,
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function opticalOrders(): HasMany
    {
        return $this->hasMany(OpticalOrder::class);
    }

    public function isExpired(): bool
    {
        return $this->valid_until && $this->valid_until->isPast();
    }

    public function formatSphere(string $eye): string
    {
        $value = $this->{$eye . '_sphere'};
        if ($value === null) {
            return 'plan';
        }
        return ($value >= 0 ? '+' : '') . number_format($value, 2);
    }
}
