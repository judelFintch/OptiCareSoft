<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CurrencySeeder::class,
            SettingsSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            SupplierSeeder::class,
            FrameSeeder::class,
            LensSeeder::class,
            PharmacyProductSeeder::class,
            PatientSeeder::class,
            AppointmentSeeder::class,
            VisitConsultationSeeder::class,
            OpticalOrderSeeder::class,
            InvoicePaymentSeeder::class,
            PharmacySaleSeeder::class,
        ]);
    }
}
