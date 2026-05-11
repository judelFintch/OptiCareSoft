<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AppointmentService
{
    public function create(array $data, User $creator): Appointment
    {
        $data['created_by'] = $creator->id;
        $data['status']     = AppointmentStatus::Scheduled;

        return Appointment::create($data);
    }

    public function confirm(Appointment $appointment): Appointment
    {
        throw_if(! $appointment->canBeConfirmed(), \Exception::class, 'Ce rendez-vous ne peut pas être confirmé.');
        $appointment->update(['status' => AppointmentStatus::Confirmed]);
        return $appointment->fresh();
    }

    public function cancel(Appointment $appointment, ?string $reason = null): Appointment
    {
        throw_if(! $appointment->canBeCancelled(), \Exception::class, 'Ce rendez-vous ne peut pas être annulé.');
        $appointment->update([
            'status' => AppointmentStatus::Cancelled,
            'notes'  => $appointment->notes . ($reason ? "\nAnnulation: {$reason}" : ''),
        ]);
        return $appointment->fresh();
    }

    public function markMissed(Appointment $appointment): Appointment
    {
        $appointment->update(['status' => AppointmentStatus::Missed]);
        return $appointment->fresh();
    }

    public function getTodayAppointments(?User $doctor = null): Collection
    {
        return Appointment::today()
            ->when($doctor, fn($q) => $q->forDoctor($doctor->id))
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_time')
            ->get();
    }

    public function getCalendarEvents(string $startDate, string $endDate, ?int $doctorId = null): array
    {
        return Appointment::whereBetween('appointment_date', [$startDate, $endDate])
            ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId))
            ->with(['patient', 'doctor'])
            ->get()
            ->map(fn(Appointment $a) => [
                'id'    => $a->id,
                'title' => $a->patient->full_name,
                'start' => $a->appointment_date->format('Y-m-d') . 'T' . $a->appointment_time,
                'color' => $this->statusColor($a->status),
                'extendedProps' => [
                    'patient'  => $a->patient->full_name,
                    'doctor'   => $a->doctor->name,
                    'reason'   => $a->reason,
                    'status'   => $a->status->label(),
                ],
            ])
            ->toArray();
    }

    private function statusColor(AppointmentStatus $status): string
    {
        return match($status) {
            AppointmentStatus::Scheduled      => '#3B82F6',
            AppointmentStatus::Confirmed      => '#6366F1',
            AppointmentStatus::Waiting        => '#F59E0B',
            AppointmentStatus::InConsultation => '#8B5CF6',
            AppointmentStatus::Completed      => '#10B981',
            AppointmentStatus::Cancelled      => '#EF4444',
            AppointmentStatus::Missed         => '#6B7280',
        };
    }
}
