<?php

namespace App\Livewire\Appointments;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class AppointmentCalendar extends Component
{
    public string $currentDate;
    public ?int $doctorFilter = null;

    public function mount(): void
    {
        $this->currentDate = today()->toDateString();
    }

    public function previousWeek(): void
    {
        $this->currentDate = Carbon::parse($this->currentDate)->subWeek()->toDateString();
    }

    public function nextWeek(): void
    {
        $this->currentDate = Carbon::parse($this->currentDate)->addWeek()->toDateString();
    }

    public function goToday(): void
    {
        $this->currentDate = today()->toDateString();
    }

    public function confirm(int $appointmentId): void
    {
        $this->authorize('appointments.confirm');
        Appointment::findOrFail($appointmentId)
            ->update(['status' => AppointmentStatus::Confirmed]);
        $this->dispatch('appointment-updated');
    }

    public function cancel(int $appointmentId): void
    {
        $this->authorize('appointments.cancel');
        Appointment::findOrFail($appointmentId)
            ->update(['status' => AppointmentStatus::Cancelled]);
        $this->dispatch('appointment-updated');
    }

    public function render()
    {
        $weekStart = Carbon::parse($this->currentDate)->startOfWeek();
        $weekEnd   = $weekStart->copy()->endOfWeek();

        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereBetween('appointment_date', [$weekStart, $weekEnd])
            ->when($this->doctorFilter, fn($q) => $q->where('doctor_id', $this->doctorFilter))
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get()
            ->groupBy(fn($a) => $a->appointment_date->toDateString());

        $days    = collect();
        $current = $weekStart->copy();
        while ($current <= $weekEnd) {
            $days->push($current->copy());
            $current->addDay();
        }

        $doctors = User::role(['Ophthalmologist'])->orderBy('name')->get();

        return view('livewire.appointments.calendar', compact('appointments', 'days', 'weekStart', 'weekEnd', 'doctors'));
    }
}
