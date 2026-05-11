<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MvpPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_main_mvp_pages(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $patient = Patient::create([
            'patient_code' => 'PAT-TEST-0001',
            'first_name' => 'Test',
            'last_name' => 'Patient',
            'gender' => 'male',
            'status' => 'active',
            'created_by' => $admin->id,
        ]);

        $routes = [
            route('dashboard'),
            route('patients.index'),
            route('patients.create'),
            route('patients.show', $patient),
            route('appointments.index'),
            route('appointments.create'),
            route('reception.index'),
            route('consultations.index'),
            route('cashier.index'),
            route('cashier.invoices.index'),
            route('cashier.invoices.create'),
            route('reports.index'),
            route('reports.daily'),
            route('reports.financial'),
            route('reports.patients'),
            route('optical.index'),
            route('optical.orders.index'),
            route('optical.orders.create'),
            route('pharmacy.index'),
            route('pharmacy.products.index'),
            route('pharmacy.products.create'),
            route('admin.users.index'),
            route('admin.users.create'),
            route('admin.settings'),
            route('admin.activity-log'),
        ];

        foreach ($routes as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }
}
