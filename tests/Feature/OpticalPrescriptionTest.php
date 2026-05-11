<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpticalPrescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_ophthalmologist_can_create_and_print_optical_prescription(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SettingsSeeder::class);

        $doctor = User::factory()->create(['is_active' => true]);
        $doctor->assignRole('Ophthalmologist');

        $patient = Patient::create([
            'patient_code' => 'PAT-RXO-0001',
            'first_name' => 'Optical',
            'last_name' => 'Patient',
            'gender' => 'female',
            'status' => 'active',
            'created_by' => $doctor->id,
        ]);

        $visit = Visit::create([
            'visit_code' => 'VIS-RXO-0001',
            'patient_id' => $patient->id,
            'status' => 'open',
            'opened_by' => $doctor->id,
            'opened_at' => now(),
        ]);

        $consultation = Consultation::create([
            'consultation_code' => 'CONS-RXO-0001',
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'visit_id' => $visit->id,
            'chief_complaint' => 'Vision floue',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($doctor)->post(
            route('consultations.optical-prescriptions.store', $consultation),
            [
                'right_sphere' => -1.25,
                'right_cylinder' => -0.50,
                'right_axis' => 90,
                'left_sphere' => -1.00,
                'left_cylinder' => -0.25,
                'left_axis' => 85,
                'pd_right' => 31.5,
                'pd_left' => 31.5,
                'lens_type' => 'unifocal',
                'usage' => 'distance',
                'valid_until' => now()->addYear()->format('Y-m-d'),
                'remarks' => 'Port permanent recommandé.',
            ]
        );

        $prescription = $consultation->opticalPrescriptions()->firstOrFail();

        $response->assertRedirect(route('consultations.show', $consultation));
        $this->assertSame('RXO-' . now()->format('Ymd') . '-0001', $prescription->prescription_number);

        $this->actingAs($doctor)
            ->get(route('optical-prescriptions.pdf', $prescription))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
