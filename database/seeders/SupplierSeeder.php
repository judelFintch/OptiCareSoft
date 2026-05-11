<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name'           => 'Optique Centrale Kinshasa',
                'contact_person' => 'Paulin Mukendi',
                'phone'          => '+243 081 234 5678',
                'email'          => 'contact@optique-centrale.cd',
                'address'        => 'Avenue du Commerce, Gombe, Kinshasa',
                'category'       => 'optical',
                'is_active'      => true,
            ],
            [
                'name'           => 'Vision Pro Congo',
                'contact_person' => 'Clarisse Ngandu',
                'phone'          => '+243 099 876 5432',
                'email'          => 'visionpro@gmail.com',
                'address'        => 'Boulevard du 30 Juin, Kinshasa',
                'category'       => 'optical',
                'is_active'      => true,
            ],
            [
                'name'           => 'Pharma Espoir',
                'contact_person' => 'Dr. Honoré Kasumba',
                'phone'          => '+243 082 111 2233',
                'email'          => 'pharmaespoir@cd.net',
                'address'        => 'Avenue Kabambare, Kalamu, Kinshasa',
                'category'       => 'pharmacy',
                'is_active'      => true,
            ],
            [
                'name'           => 'MedDist Congo SARL',
                'contact_person' => 'Samuel Tshimanga',
                'phone'          => '+243 097 555 4411',
                'email'          => 'meddist@congosarl.com',
                'address'        => 'Zone Industrielle de Limete, Kinshasa',
                'category'       => 'pharmacy',
                'is_active'      => true,
            ],
            [
                'name'           => 'EuroLens Import',
                'contact_person' => 'André Bolamba',
                'phone'          => '+243 084 900 1122',
                'email'          => 'eurolens@import.cd',
                'address'        => 'Quartier Matonge, Kalamu, Kinshasa',
                'category'       => 'optical',
                'is_active'      => true,
            ],
        ];

        foreach ($suppliers as $s) {
            Supplier::firstOrCreate(['name' => $s['name']], $s);
        }
    }
}
