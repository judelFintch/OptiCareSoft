<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\MedicalPrescription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MedicalPrescriptionService
{
    public function create(Consultation $consultation, User $doctor, array $data): MedicalPrescription
    {
        return DB::transaction(function () use ($consultation, $doctor, $data) {
            $items = $data['items'];
            unset($data['items']);

            $prescription = MedicalPrescription::create([
                ...$data,
                'prescription_number' => $this->generateNumber(),
                'consultation_id' => $consultation->id,
                'patient_id' => $consultation->patient_id,
                'doctor_id' => $doctor->id,
            ]);

            $this->syncItems($prescription, $items);

            return $prescription->fresh(['items']);
        });
    }

    public function update(MedicalPrescription $prescription, array $data): MedicalPrescription
    {
        return DB::transaction(function () use ($prescription, $data) {
            $items = $data['items'];
            unset($data['items']);

            $prescription->update($data);
            $prescription->items()->delete();
            $this->syncItems($prescription, $items);

            return $prescription->fresh(['items']);
        });
    }

    private function syncItems(MedicalPrescription $prescription, array $items): void
    {
        foreach ($items as $index => $item) {
            if (blank($item['drug_name'] ?? null)) {
                continue;
            }

            $prescription->items()->create([
                ...$item,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function generateNumber(): string
    {
        $prefix = 'RXM-' . now()->format('Ymd') . '-';
        $last = MedicalPrescription::where('prescription_number', 'like', $prefix . '%')
            ->orderByDesc('prescription_number')
            ->value('prescription_number');

        $seq = $last ? ((int) substr($last, -4) + 1) : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
