<?php

namespace Database\Seeders;

use App\Models\Lens;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class LensSeeder extends Seeder
{
    public function run(): void
    {
        $supplier = Supplier::where('name', 'EuroLens Import')->first()
            ?? Supplier::where('category', 'optical')->first();

        $lenses = [
            // Monofocaux
            ['brand' => 'Essilor', 'type' => 'monofocal', 'index' => '1.50', 'treatment' => 'antireflet',     'purchase_price' => 18000, 'selling_price' => 40000, 'stock_quantity' => 30],
            ['brand' => 'Essilor', 'type' => 'monofocal', 'index' => '1.60', 'treatment' => 'antireflet',     'purchase_price' => 25000, 'selling_price' => 55000, 'stock_quantity' => 25],
            ['brand' => 'Essilor', 'type' => 'monofocal', 'index' => '1.67', 'treatment' => 'antireflet',     'purchase_price' => 35000, 'selling_price' => 75000, 'stock_quantity' => 15],
            ['brand' => 'Essilor', 'type' => 'monofocal', 'index' => '1.74', 'treatment' => 'antireflet',     'purchase_price' => 55000, 'selling_price' => 110000,'stock_quantity' => 8],
            // Progressifs
            ['brand' => 'Essilor', 'type' => 'progressif','index' => '1.50', 'treatment' => 'antireflet',     'purchase_price' => 55000, 'selling_price' => 110000,'stock_quantity' => 12],
            ['brand' => 'Essilor', 'type' => 'progressif','index' => '1.60', 'treatment' => 'antireflet',     'purchase_price' => 70000, 'selling_price' => 140000,'stock_quantity' => 10],
            ['brand' => 'Zeiss',   'type' => 'progressif','index' => '1.60', 'treatment' => 'antireflet UV',  'purchase_price' => 80000, 'selling_price' => 160000,'stock_quantity' => 8],
            // Photochromiques
            ['brand' => 'Essilor', 'type' => 'monofocal', 'index' => '1.50', 'treatment' => 'photochromique', 'purchase_price' => 40000, 'selling_price' => 85000, 'stock_quantity' => 10],
            ['brand' => 'Transitions','type'=> 'monofocal','index'=> '1.60', 'treatment' => 'photochromique', 'purchase_price' => 50000, 'selling_price' => 100000,'stock_quantity' => 8],
            // Solaires correctrices
            ['brand' => 'Zeiss',   'type' => 'solaire',   'index' => '1.50', 'treatment' => 'polarisé',       'purchase_price' => 45000, 'selling_price' => 90000, 'stock_quantity' => 6],
            // Enfants
            ['brand' => 'Essilor', 'type' => 'monofocal', 'index' => '1.50', 'treatment' => 'antireflet UV enfant','purchase_price' => 22000, 'selling_price' => 48000,'stock_quantity' => 15],
        ];

        foreach ($lenses as $l) {
            $l['is_active']      = true;
            $l['reorder_level']  = 3;
            $l['supplier_id']    = $supplier?->id;
            $l['reference']      = 'LN-' . strtoupper(substr($l['brand'], 0, 3)) . '-' . strtoupper(str_replace('.', '', $l['index'])) . '-' . rand(10, 99);
            Lens::firstOrCreate(['brand' => $l['brand'], 'type' => $l['type'], 'index' => $l['index'], 'treatment' => $l['treatment']], $l);
        }
    }
}
