<?php

namespace App\Services;

use App\Enums\ConsultationStatus;
use App\Models\Consultation;
use App\Models\EyePressure;
use App\Models\Refraction;
use App\Models\User;
use App\Models\VisualAcuity;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;

class ConsultationService
{
    public function createConsultation(Visit $visit, User $doctor, array $data): Consultation
    {
        $data['consultation_code'] = $this->generateCode();
        $data['patient_id']        = $visit->patient_id;
        $data['doctor_id']         = $doctor->id;
        $data['visit_id']          = $visit->id;
        $data['status']            = ConsultationStatus::Draft;

        return DB::transaction(fn() => Consultation::create($data));
    }

    public function saveVisualAcuity(Consultation $consultation, array $data): VisualAcuity
    {
        $data['patient_id'] = $consultation->patient_id;

        return VisualAcuity::updateOrCreate(
            ['consultation_id' => $consultation->id],
            $data
        );
    }

    public function saveRefraction(Consultation $consultation, array $data): Refraction
    {
        $data['patient_id'] = $consultation->patient_id;

        return Refraction::updateOrCreate(
            ['consultation_id' => $consultation->id],
            $data
        );
    }

    public function saveEyePressure(Consultation $consultation, array $data): EyePressure
    {
        $data['patient_id'] = $consultation->patient_id;
        $data['measured_at'] = $data['measured_at'] ?? now();

        return EyePressure::updateOrCreate(
            ['consultation_id' => $consultation->id],
            $data
        );
    }

    public function setDiagnosis(Consultation $consultation, array $data): Consultation
    {
        $consultation->update([
            'primary_diagnosis'   => $data['primary_diagnosis'],
            'secondary_diagnoses' => $data['secondary_diagnoses'] ?? [],
            'icd_code'            => $data['icd_code'] ?? null,
            'treatment_plan'      => $data['treatment_plan'] ?? null,
            'recommendations'     => $data['recommendations'] ?? null,
            'next_appointment_date' => $data['next_appointment_date'] ?? null,
        ]);

        return $consultation->fresh();
    }

    public function completeConsultation(Consultation $consultation): Consultation
    {
        $consultation->update(['status' => ConsultationStatus::Completed]);
        return $consultation->fresh();
    }

    public function signConsultation(Consultation $consultation, User $doctor): Consultation
    {
        $consultation->update([
            'status'    => ConsultationStatus::Signed,
            'signed_at' => now(),
        ]);

        return $consultation->fresh();
    }

    public function updateConsultation(Consultation $consultation, array $data): Consultation
    {
        $consultation->update($data);
        return $consultation->fresh();
    }

    private function generateCode(): string
    {
        $prefix = 'CONS-' . now()->format('Ymd') . '-';
        $last   = Consultation::where('consultation_code', 'like', $prefix . '%')
            ->orderByDesc('consultation_code')
            ->value('consultation_code');

        $seq = $last ? ((int) substr($last, -4) + 1) : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
