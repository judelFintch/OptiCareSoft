<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\OpticalPrescription;
use App\Models\User;

class OpticalPrescriptionService
{
    public function create(Consultation $consultation, User $doctor, array $data): OpticalPrescription
    {
        $data['prescription_number'] = $this->generateNumber();
        $data['consultation_id']     = $consultation->id;
        $data['patient_id']          = $consultation->patient_id;
        $data['doctor_id']           = $doctor->id;

        return OpticalPrescription::create($data);
    }

    public function update(OpticalPrescription $prescription, array $data): OpticalPrescription
    {
        $prescription->update($data);
        return $prescription->fresh();
    }

    private function generateNumber(): string
    {
        $prefix = 'RXO-' . now()->format('Ymd') . '-';
        $last   = OpticalPrescription::where('prescription_number', 'like', $prefix . '%')
            ->orderByDesc('prescription_number')
            ->value('prescription_number');

        $seq = $last ? ((int) substr($last, -4) + 1) : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
