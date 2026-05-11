<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Patients
            'patients.view', 'patients.create', 'patients.edit', 'patients.delete',
            // Appointments
            'appointments.view', 'appointments.create', 'appointments.edit',
            'appointments.cancel', 'appointments.confirm',
            // Visits
            'visits.view', 'visits.create', 'visits.manage',
            // Consultations
            'consultations.view', 'consultations.create', 'consultations.edit',
            'consultations.delete', 'consultations.sign',
            // Prescriptions
            'medical_prescriptions.create', 'medical_prescriptions.view',
            'optical_prescriptions.create', 'optical_prescriptions.view',
            // Optical
            'optical_orders.view', 'optical_orders.manage',
            'stock.manage', 'stock.view',
            // Pharmacy
            'pharmacy.view', 'pharmacy.manage',
            // Billing
            'invoices.view', 'invoices.create', 'invoices.cancel',
            'payments.receive', 'cashier.report',
            // Admin
            'users.manage', 'roles.manage', 'settings.manage', 'logs.view',
            // Reports
            'reports.view', 'reports.export',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // --- Admin ---
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions(Permission::all());

        // --- Manager ---
        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->syncPermissions([
            'patients.view', 'appointments.view', 'visits.view',
            'consultations.view', 'invoices.view', 'cashier.report',
            'reports.view', 'reports.export', 'logs.view',
            'stock.view', 'pharmacy.view',
            'optical_prescriptions.view', 'medical_prescriptions.view',
            'optical_orders.view',
        ]);

        // --- Receptionist ---
        $receptionist = Role::firstOrCreate(['name' => 'Receptionist']);
        $receptionist->syncPermissions([
            'patients.view', 'patients.create', 'patients.edit',
            'appointments.view', 'appointments.create', 'appointments.edit',
            'appointments.cancel', 'appointments.confirm',
            'visits.view', 'visits.create', 'visits.manage',
        ]);

        // --- Ophthalmologist ---
        $ophthalmologist = Role::firstOrCreate(['name' => 'Ophthalmologist']);
        $ophthalmologist->syncPermissions([
            'patients.view', 'patients.edit',
            'appointments.view', 'appointments.create',
            'consultations.view', 'consultations.create', 'consultations.edit',
            'consultations.sign', 'consultations.delete',
            'medical_prescriptions.create', 'medical_prescriptions.view',
            'optical_prescriptions.create', 'optical_prescriptions.view',
            'optical_orders.view',
            'reports.view',
        ]);

        // --- Optician ---
        $optician = Role::firstOrCreate(['name' => 'Optician']);
        $optician->syncPermissions([
            'patients.view',
            'optical_prescriptions.view',
            'optical_orders.view', 'optical_orders.manage',
            'stock.view', 'stock.manage',
            'invoices.view',
        ]);

        // --- Cashier ---
        $cashier = Role::firstOrCreate(['name' => 'Cashier']);
        $cashier->syncPermissions([
            'patients.view',
            'visits.view',
            'invoices.view', 'invoices.create', 'invoices.cancel',
            'payments.receive', 'cashier.report',
        ]);

        // --- Pharmacist ---
        $pharmacist = Role::firstOrCreate(['name' => 'Pharmacist']);
        $pharmacist->syncPermissions([
            'patients.view',
            'medical_prescriptions.view',
            'pharmacy.view', 'pharmacy.manage',
            'stock.view', 'stock.manage',
        ]);
    }
}
