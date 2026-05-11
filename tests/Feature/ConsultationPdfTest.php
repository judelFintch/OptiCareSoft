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

class ConsultationPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_complete_sign_and_print_consultation_pdf(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SettingsSeeder::class);

        $doctor = User::factory()->create(['is_active' => true]);
        $doctor->assignRole('Ophthalmologist');

        $patient = Patient::create([
            'patient_code' => 'PAT-CONS-PDF',
            'first_name' => 'Consult',
            'last_name' => 'Patient',
            'gender' => 'male',
            'status' => 'active',
            'created_by' => $doctor->id,
        ]);

        $visit = Visit::create([
            'visit_code' => 'VIS-CONS-PDF',
            'patient_id' => $patient->id,
            'status' => 'open',
            'opened_by' => $doctor->id,
            'opened_at' => now(),
        ]);

        $consultation = Consultation::create([
            'consultation_code' => 'CONS-PDF-0001',
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'visit_id' => $visit->id,
            'chief_complaint' => 'Douleur oculaire',
            'clinical_findings' => 'Conjonctive inflammatoire',
            'primary_diagnosis' => 'conjunctivitis',
            'treatment_plan' => 'Traitement local',
            'status' => 'draft',
        ]);

        $this->actingAs($doctor)
            ->patch(route('consultations.complete', $consultation))
            ->assertRedirect();

        $this->assertSame('completed', $consultation->fresh()->status->value);

        $this->actingAs($doctor)
            ->patch(route('consultations.sign', $consultation))
            ->assertRedirect();

        $this->assertSame('signed', $consultation->fresh()->status->value);
        $this->assertNotNull($consultation->fresh()->signed_at);

        $this->actingAs($doctor)
            ->get(route('consultations.pdf', $consultation))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
