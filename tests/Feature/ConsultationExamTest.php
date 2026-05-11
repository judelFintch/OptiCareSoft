<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationExamTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_save_consultation_exams(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $doctor = User::factory()->create(['is_active' => true]);
        $doctor->assignRole('Ophthalmologist');

        $patient = Patient::create([
            'patient_code' => 'PAT-EXAM-0001',
            'first_name' => 'Exam',
            'last_name' => 'Patient',
            'gender' => 'male',
            'status' => 'active',
            'created_by' => $doctor->id,
        ]);

        $visit = Visit::create([
            'visit_code' => 'VIS-EXAM-0001',
            'patient_id' => $patient->id,
            'status' => 'open',
            'opened_by' => $doctor->id,
            'opened_at' => now(),
        ]);

        $consultation = Consultation::create([
            'consultation_code' => 'CONS-EXAM-0001',
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'visit_id' => $visit->id,
            'chief_complaint' => 'Baisse de vision',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($doctor)->put(route('consultations.exams.update', $consultation), [
            'visual_acuity' => [
                'right_eye_sc' => '6/10',
                'left_eye_sc' => '7/10',
                'right_eye_cc' => '10/10',
                'left_eye_cc' => '10/10',
            ],
            'refraction' => [
                'right_sphere' => -1.25,
                'right_cylinder' => -0.50,
                'right_axis' => 90,
                'left_sphere' => -1.00,
                'left_cylinder' => -0.25,
                'left_axis' => 85,
                'pd_right' => 31.5,
                'pd_left' => 31.5,
            ],
            'eye_pressure' => [
                'right_eye_pressure' => 15,
                'left_eye_pressure' => 16,
                'measurement_method' => 'non-contact',
            ],
        ]);

        $response->assertRedirect(route('consultations.show', $consultation));

        $consultation->refresh();

        $this->assertSame('6/10', $consultation->visualAcuity->right_eye_sc);
        $this->assertSame('-1.25', $consultation->refraction->right_sphere);
        $this->assertSame('15.0', $consultation->eyePressure->right_eye_pressure);
    }
}
