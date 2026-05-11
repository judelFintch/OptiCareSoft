<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PatientService
{
    public function createPatient(array $data, User $creator): Patient
    {
        $data['patient_code'] = $this->generatePatientCode();
        $data['created_by']   = $creator->id;

        return DB::transaction(function () use ($data) {
            return Patient::create($data);
        });
    }

    public function updatePatient(Patient $patient, array $data): Patient
    {
        $patient->update($data);
        return $patient->fresh();
    }

    public function searchPatients(string $term, int $perPage = 15): LengthAwarePaginator
    {
        return Patient::active()
            ->search($term)
            ->with(['creator'])
            ->orderBy('last_name')
            ->paginate($perPage);
    }

    public function getPatientHistory(Patient $patient): array
    {
        return [
            'consultations'         => $patient->consultations()->with('doctor')->latest()->take(10)->get(),
            'optical_prescriptions' => $patient->opticalPrescriptions()->with('doctor')->latest()->take(5)->get(),
            'medical_prescriptions' => $patient->medicalPrescriptions()->with('doctor')->latest()->take(5)->get(),
            'optical_orders'        => $patient->opticalOrders()->latest()->take(5)->get(),
            'invoices'              => $patient->invoices()->latest()->take(10)->get(),
            'exams'                 => $patient->ophthalmicExams()->latest()->take(10)->get(),
        ];
    }

    public function generatePatientCode(): string
    {
        $prefix = 'PAT-' . now()->format('Ymd') . '-';
        $last   = Patient::withTrashed()
            ->where('patient_code', 'like', $prefix . '%')
            ->orderByDesc('patient_code')
            ->value('patient_code');

        $seq = $last ? ((int) substr($last, -4) + 1) : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function checkDuplicate(string $firstName, string $lastName, ?string $birthDate): ?Patient
    {
        return Patient::where('first_name', $firstName)
            ->where('last_name', $lastName)
            ->when($birthDate, fn($q) => $q->where('birth_date', $birthDate))
            ->first();
    }

    public function deactivatePatient(Patient $patient): void
    {
        $patient->update(['status' => 'inactive']);
    }
}
