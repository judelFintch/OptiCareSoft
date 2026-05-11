<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Invoice extends Model
{
    use LogsActivity;

    protected $fillable = [
        'invoice_number', 'patient_id', 'visit_id', 'consultation_id',
        'invoice_type', 'status', 'subtotal', 'discount_amount',
        'tax_amount', 'total_amount', 'paid_amount', 'remaining_amount',
        'currency_id', 'exchange_rate', 'notes', 'cancellation_reason',
        'created_by', 'cancelled_by', 'issued_at', 'due_date', 'paid_at', 'cancelled_at',
    ];

    protected $casts = [
        'status'          => InvoiceStatus::class,
        'invoice_type'    => InvoiceType::class,
        'subtotal'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'total_amount'    => 'decimal:2',
        'paid_amount'     => 'decimal:2',
        'remaining_amount'=> 'decimal:2',
        'issued_at'       => 'datetime',
        'due_date'        => 'date',
        'paid_at'         => 'datetime',
        'cancelled_at'    => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function canceller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function isPaid(): bool
    {
        return $this->status === InvoiceStatus::Paid;
    }

    public function isCancelled(): bool
    {
        return $this->status === InvoiceStatus::Cancelled;
    }

    public function canBePaid(): bool
    {
        return in_array($this->status, [
            InvoiceStatus::Unpaid,
            InvoiceStatus::PartiallyPaid,
        ]);
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', [
            InvoiceStatus::Unpaid->value,
            InvoiceStatus::PartiallyPaid->value,
        ]);
    }
}
