<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@opticare.local'],
            [
                'name'      => 'Administrateur',
                'password'  => Hash::make('Admin@2026!'),
                'phone'     => '+243000000000',
                'is_active' => true,
            ]
        );
        $admin->assignRole('Admin');

        $doctor = User::firstOrCreate(
            ['email' => 'docteur@opticare.local'],
            [
                'name'      => 'Dr. Jean Médecin',
                'password'  => Hash::make('Doctor@2026!'),
                'phone'     => '+243000000001',
                'specialty' => 'Ophtalmologie',
                'is_active' => true,
            ]
        );
        $doctor->assignRole('Ophthalmologist');

        $receptionist = User::firstOrCreate(
            ['email' => 'reception@opticare.local'],
            [
                'name'      => 'Marie Accueil',
                'password'  => Hash::make('Recept@2026!'),
                'is_active' => true,
            ]
        );
        $receptionist->assignRole('Receptionist');

        $cashier = User::firstOrCreate(
            ['email' => 'caisse@opticare.local'],
            [
                'name'      => 'Paul Caissier',
                'password'  => Hash::make('Cashier@2026!'),
                'is_active' => true,
            ]
        );
        $cashier->assignRole('Cashier');
    }
}
