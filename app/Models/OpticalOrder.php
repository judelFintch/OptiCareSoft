<?php

namespace App\Models;

use App\Enums\OpticalOrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class OpticalOrder extends Model
{
    use LogsActivity;

    protected $fillable = [
        'order_number', 'patient_id', 'optical_prescription_id',
        'frame_id', 'right_lens_id', 'left_lens_id',
        'right_sphere', 'right_cylinder', 'right_axis', 'right_addition',
        'left_sphere', 'left_cylinder', 'left_axis', 'left_addition',
        'pupillary_distance', 'special_instructions',
        'price_frame', 'price_lenses', 'total_price',
        'deposit_paid', 'remaining_amount',
        'supplier_id', 'assigned_to', 'status',
        'expected_date', 'delivery_date', 'notes', 'created_by',
    ];

    protected $casts = [
        'status'        => OpticalOrderStatus::class,
        'price_frame'   => 'decimal:2',
        'price_lenses'  => 'decimal:2',
        'total_price'   => 'decimal:2',
        'deposit_paid'  => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'expected_date' => 'date',
        'delivery_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(OpticalPrescription::class, 'optical_prescription_id');
    }

    public function frame(): BelongsTo
    {
        return $this->belongsTo(Frame::class);
    }

    public function rightLens(): BelongsTo
    {
        return $this->belongsTo(Lens::class, 'right_lens_id');
    }

    public function leftLens(): BelongsTo
    {
        return $this->belongsTo(Lens::class, 'left_lens_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isReady(): bool
    {
        return $this->status === OpticalOrderStatus::Ready;
    }
}
