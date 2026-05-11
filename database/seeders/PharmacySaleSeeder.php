<?php

namespace Database\Seeders;

use App\Models\MedicalPrescription;
use App\Models\Patient;
use App\Models\PharmacyProduct;
use App\Models\PharmacySale;
use App\Models\PharmacySaleItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PharmacySaleSeeder extends Seeder
{
    public function run(): void
    {
        $pharmacist  = User::role('Cashier')->first() ?? User::first();
        $patients    = Patient::all();
        $products    = PharmacyProduct::all();
        $prescriptions = MedicalPrescription::all();

        if ($patients->isEmpty() || $products->isEmpty()) return;

        $tobradex  = $products->where('reference', 'PH-TOB-001')->first();
        $chloramph = $products->where('reference', 'PH-CHL-002')->first();
        $systane   = $products->where('reference', 'PH-SYS-004')->first();
        $visine    = $products->where('reference', 'PH-VIS-005')->first();
        $timolol   = $products->where('reference', 'PH-TIM-009')->first();
        $olopat    = $products->where('reference', 'PH-OLO-011')->first();
        $vitA      = $products->where('reference', 'PH-VIT-012')->first();
        $lutemax   = $products->where('reference', 'PH-LUT-013')->first();
        $atropine  = $products->where('reference', 'PH-ATR-014')->first();
        $dexamet   = $products->where('reference', 'PH-DEX-007')->first();

        $sales = [
            [
                'patient'  => $patients->get(0),
                'rx'       => $prescriptions->first(),
                'days_ago' => 2,
                'status'   => 'paid',
                'items'    => [
                    ['product' => $tobradex,  'qty' => 2, 'note' => 'Conjonctivite purulente'],
                    ['product' => $systane,   'qty' => 1, 'note' => 'Hydratation oculaire'],
                ],
            ],
            [
                'patient'  => $patients->get(1),
                'rx'       => null,
                'days_ago' => 5,
                'status'   => 'paid',
                'items'    => [
                    ['product' => $visine,    'qty' => 2],
                    ['product' => $vitA,      'qty' => 1],
                ],
            ],
            [
                'patient'  => $patients->get(2),
                'rx'       => $prescriptions->get(1),
                'days_ago' => 7,
                'status'   => 'paid',
                'items'    => [
                    ['product' => $timolol,   'qty' => 2, 'note' => 'Traitement glaucome mensuel'],
                ],
            ],
            [
                'patient'  => $patients->get(3),
                'rx'       => null,
                'days_ago' => 10,
                'status'   => 'paid',
                'items'    => [
                    ['product' => $systane,   'qty' => 2],
                    ['product' => $lutemax,   'qty' => 1, 'note' => 'Supplémentation dégénérescence maculaire'],
                ],
            ],
            [
                'patient'  => $patients->get(4),
                'rx'       => $prescriptions->get(2),
                'days_ago' => 14,
                'status'   => 'paid',
                'items'    => [
                    ['product' => $chloramph, 'qty' => 3, 'note' => 'Traitement 10 jours'],
                    ['product' => $dexamet,   'qty' => 1],
                ],
            ],
            [
                'patient'  => $patients->get(5),
                'rx'       => null,
                'days_ago' => 16,
                'status'   => 'paid',
                'items'    => [
                    ['product' => $olopat,    'qty' => 1, 'note' => 'Allergie saisonnière'],
                    ['product' => $visine,    'qty' => 1],
                ],
            ],
            [
                'patient'  => $patients->get(6),
                'rx'       => $prescriptions->get(3),
                'days_ago' => 20,
                'status'   => 'paid',
                'items'    => [
                    ['product' => $atropine,  'qty' => 1, 'note' => 'Cycloplégie enfant'],
                ],
            ],
            [
                'patient'  => $patients->get(7),
                'rx'       => null,
                'days_ago' => 22,
                'status'   => 'paid',
                'items'    => [
                    ['product' => $systane,   'qty' => 3],
                    ['product' => $vitA,      'qty' => 2],
                    ['product' => $lutemax,   'qty' => 1],
                ],
            ],
            [
                'patient'  => $patients->get(8),
                'rx'       => null,
                'days_ago' => 25,
                'status'   => 'unpaid',
                'items'    => [
                    ['product' => $timolol,   'qty' => 2],
                ],
            ],
            [
                'patient'  => $patients->get(9),
                'rx'       => null,
                'days_ago' => 1,
                'status'   => 'paid',
                'items'    => [
                    ['product' => $visine,    'qty' => 1],
                    ['product' => $systane,   'qty' => 2],
                    ['product' => $vitA,      'qty' => 1],
                ],
            ],
        ];

        $num = 1;
        foreach ($sales as $s) {
            if (!$s['patient']) continue;

            $total = collect($s['items'])->sum(fn ($item) => ($item['product']?->selling_price ?? 0) * $item['qty']);
            $saleDate = Carbon::now()->subDays($s['days_ago']);

            $sale = PharmacySale::create([
                'sale_number'             => 'VENTE-' . date('Y') . '-' . str_pad($num++, 4, '0', STR_PAD_LEFT),
                'patient_id'              => $s['patient']->id,
                'medical_prescription_id' => $s['rx']?->id,
                'total_amount'            => $total,
                'payment_status'          => $s['status'],
                'served_by'               => $pharmacist->id,
                'created_at'              => $saleDate,
                'updated_at'              => $saleDate,
            ]);

            foreach ($s['items'] as $item) {
                if (!$item['product']) continue;

                PharmacySaleItem::create([
                    'pharmacy_sale_id'    => $sale->id,
                    'pharmacy_product_id' => $item['product']->id,
                    'quantity'            => $item['qty'],
                    'unit_price'          => $item['product']->selling_price,
                    'total'               => $item['product']->selling_price * $item['qty'],
                ]);

                // Décrémenter le stock
                $item['product']->decrement('stock_quantity', $item['qty']);
            }
        }
    }
}
