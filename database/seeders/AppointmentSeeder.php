<?php

namespace Database\Seeders;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $doctor       = User::role('Ophthalmologist')->first() ?? User::first();
        $receptionist = User::role('Receptionist')->first() ?? User::first();
        $patients = Patient::all();

        if ($patients->isEmpty()) return;

        $reasons = [
            'Consultation de routine',
            'Bilan visuel annuel',
            'Gêne visuelle et céphalées',
            'Contrôle glaucome',
            'Suivi post-opératoire cataracte',
            'Renouvellement ordonnance lunettes',
            'Rougeur et douleur oculaire',
            'Diplopie (vision double)',
            'Corps étranger oculaire',
            'Bilan rétinopathie diabétique',
            'Contrôle pression oculaire',
            'Vision floue de près',
            'Vision floue de loin',
            'Larmoiement excessif',
            'Photophobie et vision réduite',
        ];

        // Passés (30 derniers jours) — variés
        $pastSlots = [
            ['days_ago' => 28, 'time' => '08:00', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 27, 'time' => '09:30', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 26, 'time' => '10:00', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 25, 'time' => '08:30', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 24, 'time' => '11:00', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 22, 'time' => '09:00', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 21, 'time' => '10:30', 'status' => AppointmentStatus::Missed],
            ['days_ago' => 20, 'time' => '08:00', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 18, 'time' => '14:00', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 16, 'time' => '09:00', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 14, 'time' => '11:30', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 12, 'time' => '08:30', 'status' => AppointmentStatus::Missed],
            ['days_ago' => 10, 'time' => '10:00', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 8,  'time' => '09:30', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 7,  'time' => '14:30', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 5,  'time' => '08:00', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 4,  'time' => '10:30', 'status' => AppointmentStatus::Cancelled],
            ['days_ago' => 3,  'time' => '09:00', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 2,  'time' => '11:00', 'status' => AppointmentStatus::Completed],
            ['days_ago' => 1,  'time' => '08:30', 'status' => AppointmentStatus::Completed],
        ];

        // Aujourd'hui + futurs
        $futureSlots = [
            ['days' => 0,  'time' => '08:00', 'status' => AppointmentStatus::Confirmed],
            ['days' => 0,  'time' => '09:00', 'status' => AppointmentStatus::Confirmed],
            ['days' => 0,  'time' => '10:00', 'status' => AppointmentStatus::Scheduled],
            ['days' => 0,  'time' => '11:00', 'status' => AppointmentStatus::Scheduled],
            ['days' => 1,  'time' => '09:30', 'status' => AppointmentStatus::Scheduled],
            ['days' => 2,  'time' => '08:00', 'status' => AppointmentStatus::Scheduled],
            ['days' => 3,  'time' => '10:00', 'status' => AppointmentStatus::Scheduled],
            ['days' => 5,  'time' => '09:00', 'status' => AppointmentStatus::Scheduled],
            ['days' => 7,  'time' => '14:00', 'status' => AppointmentStatus::Scheduled],
            ['days' => 10, 'time' => '08:30', 'status' => AppointmentStatus::Scheduled],
        ];

        $patientList = $patients->shuffle();
        $idx = 0;

        foreach ($pastSlots as $slot) {
            $patient = $patientList[$idx % $patientList->count()];
            Appointment::firstOrCreate([
                'patient_id'       => $patient->id,
                'appointment_date' => Carbon::now()->subDays($slot['days_ago'])->toDateString(),
                'appointment_time' => $slot['time'],
            ], [
                'doctor_id'  => $doctor->id,
                'reason'     => $reasons[$idx % count($reasons)],
                'status'     => $slot['status'],
                'notes'      => null,
                'created_by' => $receptionist->id,
            ]);
            $idx++;
        }

        foreach ($futureSlots as $slot) {
            $patient = $patientList[$idx % $patientList->count()];
            Appointment::firstOrCreate([
                'patient_id'       => $patient->id,
                'appointment_date' => Carbon::now()->addDays($slot['days'])->toDateString(),
                'appointment_time' => $slot['time'],
            ], [
                'doctor_id'  => $doctor->id,
                'reason'     => $reasons[$idx % count($reasons)],
                'status'     => $slot['status'],
                'notes'      => null,
                'created_by' => $receptionist->id,
            ]);
            $idx++;
        }
    }
}
