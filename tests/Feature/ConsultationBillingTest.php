<?php

namespace Tests\Feature;

use App\Models\Consultation;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Setting;
use App\Models\User;
use App\Models\Visit;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationBillingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_generate_a_consultation_invoice_once(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SettingsSeeder::class);

        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('Admin');

        Currency::create([
            'code' => 'CDF',
            'name' => 'Franc Congolais',
            'symbol' => 'FC',
            'exchange_rate' => 1,
            'is_default' => true,
            'is_active' => true,
        ]);

        Setting::set('consultation_fee', 25000, 'billing', 'decimal');

        $patient = Patient::create([
            'patient_code' => 'PAT-BILL-0001',
            'first_name' => 'Bill',
            'last_name' => 'Patient',
            'gender' => 'female',
            'status' => 'active',
            'created_by' => $admin->id,
        ]);

        $visit = Visit::create([
            'visit_code' => 'VIS-BILL-0001',
            'patient_id' => $patient->id,
            'status' => 'open',
            'opened_by' => $admin->id,
            'opened_at' => now(),
        ]);

        $consultation = Consultation::create([
            'consultation_code' => 'CONS-BILL-0001',
            'patient_id' => $patient->id,
            'doctor_id' => $admin->id,
            'visit_id' => $visit->id,
            'chief_complaint' => 'Baisse de vision',
            'status' => 'completed',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('consultations.invoice', $consultation));

        $invoice = Invoice::firstOrFail();

        $response->assertRedirect(route('cashier.invoices.show', $invoice));
        $this->assertSame($consultation->id, $invoice->consultation_id);
        $this->assertSame($visit->id, $invoice->visit_id);
        $this->assertSame('consultation', $invoice->invoice_type->value);
        $this->assertSame('unpaid', $invoice->status->value);
        $this->assertSame('25000.00', $invoice->total_amount);
        $this->assertSame('25000.00', $invoice->remaining_amount);

        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
            'label' => 'Consultation ophtalmologique',
            'total' => 25000,
        ]);

        $this->actingAs($admin)
            ->post(route('consultations.invoice', $consultation))
            ->assertRedirect(route('cashier.invoices.show', $invoice));

        $this->assertDatabaseCount('invoices', 1);
        $this->assertDatabaseCount('invoice_items', 1);
    }
}
