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

class MedicalPrescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_ophthalmologist_can_create_and_print_medical_prescription(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SettingsSeeder::class);

        $doctor = User::factory()->create(['is_active' => true]);
        $doctor->assignRole('Ophthalmologist');

        $patient = Patient::create([
            'patient_code' => 'PAT-RXM-0001',
            'first_name' => 'Medical',
            'last_name' => 'Patient',
            'gender' => 'female',
            'status' => 'active',
            'created_by' => $doctor->id,
        ]);

        $visit = Visit::create([
            'visit_code' => 'VIS-RXM-0001',
            'patient_id' => $patient->id,
            'status' => 'open',
            'opened_by' => $doctor->id,
            'opened_at' => now(),
        ]);

        $consultation = Consultation::create([
            'consultation_code' => 'CONS-RXM-0001',
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'visit_id' => $visit->id,
            'chief_complaint' => 'Irritation oculaire',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($doctor)->post(
            route('consultations.medical-prescriptions.store', $consultation),
            [
                'valid_until' => now()->addMonth()->format('Y-m-d'),
                'instructions' => 'Respecter la durée du traitement.',
                'notes' => 'Contrôle si symptômes persistants.',
                'items' => [
                    [
                        'drug_name' => 'Collyre lubrifiant',
                        'generic_name' => 'Larmes artificielles',
                        'dosage' => '1 goutte',
                        'form' => 'collyre',
                        'frequency' => '4x/jour',
                        'duration' => '7 jours',
                        'route' => 'topique',
                        'instructions' => 'Instiller dans chaque œil.',
                    ],
                ],
            ]
        );

        $prescription = $consultation->medicalPrescriptions()->with('items')->firstOrFail();

        $response->assertRedirect(route('consultations.show', $consultation));
        $this->assertSame('RXM-' . now()->format('Ymd') . '-0001', $prescription->prescription_number);
        $this->assertCount(1, $prescription->items);

        $this->actingAs($doctor)
            ->get(route('medical-prescriptions.pdf', $prescription))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
