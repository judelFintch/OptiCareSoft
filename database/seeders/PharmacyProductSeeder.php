<?php

namespace Database\Seeders;

use App\Models\PharmacyProduct;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class PharmacyProductSeeder extends Seeder
{
    public function run(): void
    {
        $supplier = Supplier::where('category', 'pharmacy')->first();

        $products = [
            // Collyres antibiotiques
            ['reference' => 'PH-TOB-001', 'name' => 'Tobradex Collyre', 'generic_name' => 'Tobramycine/Dexaméthasone', 'category' => 'collyre',    'form' => 'collyre', 'dosage' => '5 ml', 'manufacturer' => 'Alcon',         'purchase_price' => 8500,  'selling_price' => 14000, 'stock_quantity' => 20, 'reorder_level' => 5,  'is_prescription_required' => true],
            ['reference' => 'PH-CHL-002', 'name' => 'Chloramphénicol Collyre', 'generic_name' => 'Chloramphénicol',   'category' => 'collyre',    'form' => 'collyre', 'dosage' => '10 ml','manufacturer' => 'MSD',          'purchase_price' => 2500,  'selling_price' => 5000,  'stock_quantity' => 35, 'reorder_level' => 10, 'is_prescription_required' => true],
            ['reference' => 'PH-CIP-003', 'name' => 'Ciprofloxacine Collyre', 'generic_name' => 'Ciprofloxacine',    'category' => 'collyre',    'form' => 'collyre', 'dosage' => '5 ml', 'manufacturer' => 'Bausch & Lomb', 'purchase_price' => 5500,  'selling_price' => 10000, 'stock_quantity' => 18, 'reorder_level' => 5,  'is_prescription_required' => true],
            // Larmes artificielles
            ['reference' => 'PH-SYS-004', 'name' => 'Systane Ultra Larmes', 'generic_name' => 'Polyéthylène glycol', 'category' => 'larme_artificielle','form' => 'collyre','dosage' => '10 ml','manufacturer' => 'Alcon',         'purchase_price' => 9000,  'selling_price' => 15000, 'stock_quantity' => 25, 'reorder_level' => 8,  'is_prescription_required' => false],
            ['reference' => 'PH-VIS-005', 'name' => 'Visine Intense', 'generic_name' => 'Tétryzoline',              'category' => 'larme_artificielle','form' => 'collyre','dosage' => '15 ml','manufacturer' => 'J&J',           'purchase_price' => 4500,  'selling_price' => 8000,  'stock_quantity' => 30, 'reorder_level' => 10, 'is_prescription_required' => false],
            ['reference' => 'PH-OPT-006', 'name' => 'Optive Gel Lubrifiant', 'generic_name' => 'Carmellose sodique','category' => 'larme_artificielle','form' => 'gel',    'dosage' => '10 g', 'manufacturer' => 'Allergan',      'purchase_price' => 7500,  'selling_price' => 13000, 'stock_quantity' => 15, 'reorder_level' => 5,  'is_prescription_required' => false],
            // Anti-inflammatoires
            ['reference' => 'PH-DEX-007', 'name' => 'Dexaméthasone Collyre', 'generic_name' => 'Dexaméthasone',     'category' => 'anti_inflammatoire','form' => 'collyre','dosage' => '5 ml', 'manufacturer' => 'Generics',      'purchase_price' => 3000,  'selling_price' => 6000,  'stock_quantity' => 22, 'reorder_level' => 8,  'is_prescription_required' => true],
            ['reference' => 'PH-PRE-008', 'name' => 'Prednisolone Collyre', 'generic_name' => 'Prednisolone',       'category' => 'anti_inflammatoire','form' => 'collyre','dosage' => '10 ml','manufacturer' => 'Allergan',      'purchase_price' => 6500,  'selling_price' => 12000, 'stock_quantity' => 12, 'reorder_level' => 4,  'is_prescription_required' => true],
            // Antiglaucome
            ['reference' => 'PH-TIM-009', 'name' => 'Timolol 0.5% Collyre', 'generic_name' => 'Timolol maléate',   'category' => 'antiglaucome', 'form' => 'collyre', 'dosage' => '5 ml', 'manufacturer' => 'MSD',          'purchase_price' => 5000,  'selling_price' => 9500,  'stock_quantity' => 10, 'reorder_level' => 3,  'is_prescription_required' => true],
            ['reference' => 'PH-LAT-010', 'name' => 'Latanoprost 0.005%', 'generic_name' => 'Latanoprost',         'category' => 'antiglaucome', 'form' => 'collyre', 'dosage' => '2.5 ml','manufacturer' => 'Pfizer',        'purchase_price' => 12000, 'selling_price' => 22000, 'stock_quantity' => 8,  'reorder_level' => 3,  'is_prescription_required' => true],
            // Antiallergiques
            ['reference' => 'PH-OLO-011', 'name' => 'Olopatadine Collyre', 'generic_name' => 'Olopatadine HCl',    'category' => 'antiallergique','form' => 'collyre', 'dosage' => '5 ml', 'manufacturer' => 'Alcon',         'purchase_price' => 8000,  'selling_price' => 15000, 'stock_quantity' => 14, 'reorder_level' => 4,  'is_prescription_required' => true],
            // Vitamines
            ['reference' => 'PH-VIT-012', 'name' => 'Vitamine A Collyre', 'generic_name' => 'Rétinol palmitate',   'category' => 'vitamine',    'form' => 'collyre', 'dosage' => '10 ml','manufacturer' => 'Generics',      'purchase_price' => 3500,  'selling_price' => 7000,  'stock_quantity' => 20, 'reorder_level' => 6,  'is_prescription_required' => false],
            ['reference' => 'PH-LUT-013', 'name' => 'Lutemax Capsules', 'generic_name' => 'Lutéine/Zéaxanthine',  'category' => 'vitamine',    'form' => 'comprimé','dosage' => '30 cp', 'manufacturer' => 'Bausch & Lomb', 'purchase_price' => 15000, 'selling_price' => 28000, 'stock_quantity' => 10, 'reorder_level' => 3,  'is_prescription_required' => false],
            // Dilatateurs / Anesthésiques
            ['reference' => 'PH-ATR-014', 'name' => 'Atropine 1% Collyre', 'generic_name' => 'Atropine sulfate',  'category' => 'mydriatique',  'form' => 'collyre', 'dosage' => '5 ml', 'manufacturer' => 'Generics',      'purchase_price' => 2800,  'selling_price' => 5500,  'stock_quantity' => 10, 'reorder_level' => 3,  'is_prescription_required' => true],
            ['reference' => 'PH-OXY-015', 'name' => 'Oxybuprocaïne Collyre', 'generic_name' => 'Oxybuprocaïne',  'category' => 'anesthesique', 'form' => 'collyre', 'dosage' => '10 ml','manufacturer' => 'Alcon',         'purchase_price' => 4000,  'selling_price' => 8000,  'stock_quantity' => 8,  'reorder_level' => 2,  'is_prescription_required' => true],
        ];

        foreach ($products as $p) {
            $p['supplier_id'] = $supplier?->id;
            $p['is_active']   = true;
            $p['expiry_date'] = now()->addMonths(rand(12, 30))->format('Y-m-d');
            PharmacyProduct::firstOrCreate(['reference' => $p['reference']], $p);
        }
    }
}
