<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionItem extends Model
{
    protected $fillable = [
        'medical_prescription_id', 'drug_name', 'generic_name',
        'dosage', 'form', 'frequency', 'duration', 'route',
        'instructions', 'sort_order',
    ];

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(MedicalPrescription::class, 'medical_prescription_id');
    }
}
