<?php

namespace Database\Seeders;

use App\Models\Frame;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class FrameSeeder extends Seeder
{
    public function run(): void
    {
        $supplier1 = Supplier::where('name', 'Optique Centrale Kinshasa')->first();
        $supplier2 = Supplier::where('name', 'Vision Pro Congo')->first();

        $frames = [
            // Ray-Ban
            ['brand' => 'Ray-Ban', 'model' => 'RB5154 Clubmaster', 'color' => 'Noir/Or',     'material' => 'Acétate', 'category' => 'adulte', 'size' => '51-21-145', 'purchase_price' => 65000, 'selling_price' => 120000, 'stock_quantity' => 8,  'reorder_level' => 2, 'supplier_id' => $supplier1?->id],
            ['brand' => 'Ray-Ban', 'model' => 'RB2132 New Wayfarer','color' => 'Tortoise',    'material' => 'Acétate', 'category' => 'adulte', 'size' => '52-18-145', 'purchase_price' => 60000, 'selling_price' => 115000,'stock_quantity' => 6,  'reorder_level' => 2, 'supplier_id' => $supplier1?->id],
            ['brand' => 'Ray-Ban', 'model' => 'RB6375 Aviator',     'color' => 'Or/Vert',    'material' => 'Métal',   'category' => 'adulte', 'size' => '58-14-140', 'purchase_price' => 55000, 'selling_price' => 105000,'stock_quantity' => 5,  'reorder_level' => 2, 'supplier_id' => $supplier1?->id],
            // Oakley
            ['brand' => 'Oakley',  'model' => 'OX8046 Crosslink',   'color' => 'Noir Mat',   'material' => 'O-Matter','category' => 'adulte', 'size' => '56-17-138', 'purchase_price' => 70000, 'selling_price' => 135000,'stock_quantity' => 4,  'reorder_level' => 2, 'supplier_id' => $supplier2?->id],
            ['brand' => 'Oakley',  'model' => 'OX3184 Holbrook',    'color' => 'Bleu Marine', 'material' => 'Métal',  'category' => 'adulte', 'size' => '55-19-140', 'purchase_price' => 68000, 'selling_price' => 130000,'stock_quantity' => 3,  'reorder_level' => 2, 'supplier_id' => $supplier2?->id],
            // Silhouette
            ['brand' => 'Silhouette','model'=> 'Titan Minimal Art', 'color' => 'Argent',      'material' => 'Titane',  'category' => 'adulte', 'size' => '54-18-140', 'purchase_price' => 90000, 'selling_price' => 175000,'stock_quantity' => 3,  'reorder_level' => 1, 'supplier_id' => $supplier1?->id],
            // Guess
            ['brand' => 'Guess',   'model' => 'GU2706 Acetate',     'color' => 'Bordeaux',   'material' => 'Acétate', 'category' => 'adulte', 'size' => '53-16-140', 'purchase_price' => 40000, 'selling_price' => 80000, 'stock_quantity' => 7,  'reorder_level' => 2, 'supplier_id' => $supplier2?->id],
            ['brand' => 'Guess',   'model' => 'GU2792 Classic',     'color' => 'Transparent', 'material' => 'Acétate','category' => 'femme',  'size' => '52-17-140', 'purchase_price' => 38000, 'selling_price' => 75000, 'stock_quantity' => 9,  'reorder_level' => 3, 'supplier_id' => $supplier2?->id],
            // Safilo
            ['brand' => 'Safilo',  'model' => 'SA 6033 Elasta',     'color' => 'Gris',       'material' => 'Métal',   'category' => 'adulte', 'size' => '54-17-140', 'purchase_price' => 35000, 'selling_price' => 70000, 'stock_quantity' => 10, 'reorder_level' => 3, 'supplier_id' => $supplier1?->id],
            // Enfant
            ['brand' => 'Nano Vista','model'=> 'Spiderman 3630',    'color' => 'Rouge/Bleu',  'material' => 'Mémoire', 'category' => 'enfant', 'size' => '44-17-120', 'purchase_price' => 28000, 'selling_price' => 55000, 'stock_quantity' => 5,  'reorder_level' => 2, 'supplier_id' => $supplier2?->id],
            ['brand' => 'Nano Vista','model'=> 'Classic 3016',      'color' => 'Rose/Blanc',  'material' => 'Mémoire', 'category' => 'enfant', 'size' => '46-15-125', 'purchase_price' => 25000, 'selling_price' => 50000, 'stock_quantity' => 6,  'reorder_level' => 2, 'supplier_id' => $supplier2?->id],
            // Haut de gamme
            ['brand' => 'Lindberg', 'model'=> 'Acetanium 1265',     'color' => 'Havane',     'material' => 'Acétate', 'category' => 'adulte', 'size' => '52-19-140', 'purchase_price' => 120000,'selling_price' => 230000,'stock_quantity' => 2,  'reorder_level' => 1, 'supplier_id' => $supplier1?->id],
            ['brand' => 'Lindberg', 'model'=> 'Spirit Titanium',    'color' => 'Mat Gris',   'material' => 'Titane',  'category' => 'adulte', 'size' => '53-18-145', 'purchase_price' => 130000,'selling_price' => 250000,'stock_quantity' => 2,  'reorder_level' => 1, 'supplier_id' => $supplier1?->id],
            // Abordable
            ['brand' => 'Luxottica','model'=> 'LX202 Basic',        'color' => 'Noir',       'material' => 'Plastique','category' => 'adulte', 'size' => '54-16-140', 'purchase_price' => 15000, 'selling_price' => 35000, 'stock_quantity' => 15, 'reorder_level' => 5, 'supplier_id' => $supplier2?->id],
            ['brand' => 'Luxottica','model'=> 'LX105 Femme',        'color' => 'Marron',     'material' => 'Plastique','category' => 'femme',  'size' => '51-16-138', 'purchase_price' => 14000, 'selling_price' => 32000, 'stock_quantity' => 12, 'reorder_level' => 4, 'supplier_id' => $supplier2?->id],
        ];

        foreach ($frames as $f) {
            $f['is_active'] = true;
            $f['reference'] = 'FR-' . strtoupper(substr($f['brand'], 0, 3)) . '-' . rand(100, 999);
            Frame::firstOrCreate(['brand' => $f['brand'], 'model' => $f['model']], $f);
        }
    }
}
