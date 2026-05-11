<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_and_export_daily_report(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SettingsSeeder::class);

        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('Admin');

        $currency = Currency::create([
            'code' => 'CDF',
            'name' => 'Franc Congolais',
            'symbol' => 'FC',
            'exchange_rate' => 1,
            'is_default' => true,
            'is_active' => true,
        ]);

        $patient = Patient::create([
            'patient_code' => 'PAT-REPORT-0001',
            'first_name' => 'Report',
            'last_name' => 'Patient',
            'gender' => 'male',
            'status' => 'active',
            'created_by' => $admin->id,
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-REPORT-0001',
            'patient_id' => $patient->id,
            'invoice_type' => 'consultation',
            'status' => 'partially_paid',
            'subtotal' => 50000,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 50000,
            'paid_amount' => 30000,
            'remaining_amount' => 20000,
            'currency_id' => $currency->id,
            'exchange_rate' => 1,
            'created_by' => $admin->id,
            'issued_at' => today()->setTime(9, 0),
        ]);

        Payment::create([
            'payment_number' => 'PAY-REPORT-0001',
            'invoice_id' => $invoice->id,
            'patient_id' => $patient->id,
            'amount' => 30000,
            'currency_id' => $currency->id,
            'exchange_rate' => 1,
            'payment_method' => 'cash',
            'received_by' => $admin->id,
            'paid_at' => today()->setTime(9, 30),
        ]);

        $this->actingAs($admin)
            ->get(route('reports.daily', ['date' => today()->format('Y-m-d')]))
            ->assertOk()
            ->assertSee('PAY-REPORT-0001')
            ->assertSee('INV-REPORT-0001')
            ->assertSee('30 000,00');

        $this->actingAs($admin)
            ->get(route('reports.daily.pdf', ['date' => today()->format('Y-m-d')]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
