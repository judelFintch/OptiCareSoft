<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Enums\VisitStatus;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class VisitService
{
    public function openVisit(Patient $patient, User $receptionist, ?Appointment $appointment = null): Visit
    {
        // Close any existing open visit first
        $this->closeStaleVisits($patient);

        $visit = DB::transaction(function () use ($patient, $receptionist, $appointment) {
            $v = Visit::create([
                'visit_code'     => $this->generateVisitCode(),
                'patient_id'     => $patient->id,
                'appointment_id' => $appointment?->id,
                'status'         => VisitStatus::Open,
                'opened_by'      => $receptionist->id,
                'opened_at'      => now(),
            ]);

            if ($appointment) {
                $appointment->update(['status' => AppointmentStatus::InConsultation]);
            }

            return $v;
        });

        return $visit;
    }

    public function closeVisit(Visit $visit, User $closer): Visit
    {
        $visit->update([
            'status'    => VisitStatus::Closed,
            'closed_by' => $closer->id,
            'closed_at' => now(),
        ]);

        return $visit->fresh();
    }

    public function getWaitingQueue(): Collection
    {
        return Visit::open()
            ->today()
            ->with(['patient', 'appointment.doctor', 'opener'])
            ->orderBy('opened_at')
            ->get();
    }

    public function getActiveVisit(Patient $patient): ?Visit
    {
        return $patient->activeVisit()->with(['appointment', 'consultations'])->first();
    }

    public function updateStatus(Visit $visit, VisitStatus $status): Visit
    {
        $visit->update(['status' => $status]);
        return $visit->fresh();
    }

    private function generateVisitCode(): string
    {
        $prefix = 'VIS-' . now()->format('Ymd') . '-';
        $last   = Visit::where('visit_code', 'like', $prefix . '%')
            ->orderByDesc('visit_code')
            ->value('visit_code');

        $seq = $last ? ((int) substr($last, -4) + 1) : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    private function closeStaleVisits(Patient $patient): void
    {
        Visit::open()
            ->where('patient_id', $patient->id)
            ->where('opened_at', '<', now()->subDays(1))
            ->update(['status' => VisitStatus::Closed->value, 'closed_at' => now()]);
    }
}
