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

class InvoicePdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_invoice_and_receipt_pdfs(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SettingsSeeder::class);

        $admin = User::factory()->create();
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
            'patient_code' => 'PAT-PDF-0001',
            'first_name' => 'Pdf',
            'last_name' => 'Patient',
            'gender' => 'male',
            'status' => 'active',
            'created_by' => $admin->id,
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-PDF-0001',
            'patient_id' => $patient->id,
            'invoice_type' => 'consultation',
            'status' => 'partially_paid',
            'subtotal' => 100,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 100,
            'paid_amount' => 50,
            'remaining_amount' => 50,
            'currency_id' => $currency->id,
            'exchange_rate' => 1,
            'created_by' => $admin->id,
            'issued_at' => now(),
        ]);

        $invoice->items()->create([
            'label' => 'Consultation ophtalmologique',
            'quantity' => 1,
            'unit_price' => 100,
            'discount_percent' => 0,
            'total' => 100,
        ]);

        Payment::create([
            'payment_number' => 'PAY-PDF-0001',
            'invoice_id' => $invoice->id,
            'patient_id' => $patient->id,
            'amount' => 50,
            'currency_id' => $currency->id,
            'exchange_rate' => 1,
            'payment_method' => 'cash',
            'received_by' => $admin->id,
            'paid_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('cashier.invoices.pdf', $invoice))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->actingAs($admin)
            ->get(route('cashier.invoices.receipt', $invoice))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
