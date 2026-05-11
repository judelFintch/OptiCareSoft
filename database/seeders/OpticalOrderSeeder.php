<?php

namespace Database\Seeders;

use App\Enums\OpticalOrderStatus;
use App\Models\Frame;
use App\Models\Lens;
use App\Models\OpticalOrder;
use App\Models\OpticalPrescription;
use App\Models\Patient;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OpticalOrderSeeder extends Seeder
{
    public function run(): void
    {
        $doctor   = User::role('Ophthalmologist')->first() ?? User::first();
        $admin    = User::role('Admin')->first() ?? User::first();
        $supplier = Supplier::where('category', 'optical')->first();
        $patients = Patient::all();
        $frames   = Frame::all();
        $lenses   = Lens::where('type', 'monofocal')->get();
        $progLenses = Lens::where('type', 'progressif')->get();

        if ($patients->isEmpty() || $frames->isEmpty() || $lenses->isEmpty()) return;

        $prescriptions = OpticalPrescription::all();

        $orders = [
            [
                'patient'  => $patients->get(0),
                'frame'    => $frames->where('brand', 'Ray-Ban')->first(),
                'r_lens'   => $lenses->where('index', '1.60')->first(),
                'l_lens'   => $lenses->where('index', '1.60')->first(),
                'r_sph'    => -2.25, 'r_cyl' => -0.50, 'r_ax' => 15,
                'l_sph'    => -1.75, 'l_cyl' => -0.25, 'l_ax' => 170,
                'pd'       => 62.5,
                'deposit'  => 50000,
                'status'   => OpticalOrderStatus::Delivered,
                'days_ago' => 25,
                'notes'    => 'Patient pressé pour la rentrée scolaire',
            ],
            [
                'patient'  => $patients->get(1),
                'frame'    => $frames->where('brand', 'Guess')->first(),
                'r_lens'   => $progLenses->first(),
                'l_lens'   => $progLenses->first(),
                'r_sph'    => 1.00, 'l_sph' => 1.25,
                'r_add'    => 2.00, 'l_add' => 2.00,
                'pd'       => 65.0,
                'deposit'  => 80000,
                'status'   => OpticalOrderStatus::Ready,
                'days_ago' => 12,
                'notes'    => 'Première paire de progressifs, bien expliquer au patient',
            ],
            [
                'patient'  => $patients->get(2),
                'frame'    => $frames->where('brand', 'Safilo')->first(),
                'r_lens'   => $lenses->where('index', '1.50')->first(),
                'l_lens'   => $lenses->where('index', '1.50')->first(),
                'r_sph'    => -0.50, 'r_cyl' => -1.25, 'r_ax' => 85,
                'l_sph'    => -0.25, 'l_cyl' => -0.75, 'l_ax' => 95,
                'pd'       => 64.0,
                'deposit'  => 30000,
                'status'   => OpticalOrderStatus::InProduction,
                'days_ago' => 5,
                'notes'    => 'Verres antireflet traitement renforcé demandé',
            ],
            [
                'patient'  => $patients->get(3),
                'frame'    => $frames->where('brand', 'Nano Vista')->first(),
                'r_lens'   => $lenses->where('treatment', 'antireflet UV enfant')->first() ?? $lenses->first(),
                'l_lens'   => $lenses->where('treatment', 'antireflet UV enfant')->first() ?? $lenses->first(),
                'r_sph'    => 3.75, 'l_sph' => 4.00,
                'pd'       => 55.0,
                'deposit'  => 40000,
                'status'   => OpticalOrderStatus::Ordered,
                'days_ago' => 3,
                'notes'    => 'Enfant 8 ans, montures légères et solides recommandées',
            ],
            [
                'patient'  => $patients->get(4),
                'frame'    => $frames->where('brand', 'Oakley')->first(),
                'r_lens'   => $lenses->where('index', '1.67')->first(),
                'l_lens'   => $lenses->where('index', '1.67')->first(),
                'r_sph'    => -4.50, 'r_cyl' => -0.75, 'r_ax' => 10,
                'l_sph'    => -5.00, 'l_cyl' => -1.00, 'l_ax' => 175,
                'pd'       => 66.0,
                'deposit'  => 70000,
                'status'   => OpticalOrderStatus::Pending,
                'days_ago' => 1,
                'notes'    => null,
            ],
            [
                'patient'  => $patients->get(5),
                'frame'    => $frames->where('brand', 'Silhouette')->first(),
                'r_lens'   => $progLenses->where('index', '1.60')->first() ?? $progLenses->first(),
                'l_lens'   => $progLenses->where('index', '1.60')->first() ?? $progLenses->first(),
                'r_sph'    => 0.75, 'r_add' => 2.25,
                'l_sph'    => 1.00, 'l_add' => 2.25,
                'pd'       => 63.5,
                'deposit'  => 0,
                'status'   => OpticalOrderStatus::Pending,
                'days_ago' => 0,
                'notes'    => 'Devis à valider avant fabrication',
            ],
            [
                'patient'  => $patients->get(6),
                'frame'    => $frames->where('brand', 'Ray-Ban')->where('model', 'RB5154 Clubmaster')->first() ?? $frames->first(),
                'r_lens'   => $lenses->where('treatment', 'photochromique')->first() ?? $lenses->first(),
                'l_lens'   => $lenses->where('treatment', 'photochromique')->first() ?? $lenses->first(),
                'r_sph'    => -1.00, 'r_cyl' => -0.50, 'r_ax' => 90,
                'l_sph'    => -1.25, 'l_cyl' => -0.50, 'l_ax' => 85,
                'pd'       => 61.0,
                'deposit'  => 60000,
                'status'   => OpticalOrderStatus::Delivered,
                'days_ago' => 20,
                'notes'    => 'Verres photochromiques — patient conduit beaucoup',
            ],
            [
                'patient'  => $patients->get(7),
                'frame'    => $frames->where('category', 'femme')->first() ?? $frames->first(),
                'r_lens'   => $lenses->where('index', '1.50')->first(),
                'l_lens'   => $lenses->where('index', '1.50')->first(),
                'r_sph'    => 2.00, 'l_sph' => 2.25,
                'pd'       => 60.0,
                'deposit'  => 25000,
                'status'   => OpticalOrderStatus::Cancelled,
                'days_ago' => 15,
                'notes'    => 'Annulé : patiente a choisi une autre clinique',
            ],
        ];

        $num = 1;
        foreach ($orders as $o) {
            $patient   = $o['patient'];
            $frame     = $o['frame'];
            $rLens     = $o['r_lens'];
            $lLens     = $o['l_lens'];
            $daysAgo   = $o['days_ago'];

            if (!$patient) continue;

            $framePrice  = $frame ? (float) $frame->selling_price : 0;
            $lensPrice   = ($rLens ? (float) $rLens->selling_price : 0) + ($lLens ? (float) $lLens->selling_price : 0);
            $total       = $framePrice + $lensPrice;
            $deposit     = $o['deposit'];
            $remaining   = max(0, $total - $deposit);
            $createdAt   = Carbon::now()->subDays($daysAgo);

            $prescription = $prescriptions->where('patient_id', $patient->id)->first();

            $order = OpticalOrder::create([
                'order_number'            => 'ORD-' . date('Y') . '-' . str_pad($num++, 4, '0', STR_PAD_LEFT),
                'patient_id'              => $patient->id,
                'optical_prescription_id' => $prescription?->id,
                'frame_id'                => $frame?->id,
                'right_lens_id'           => $rLens?->id,
                'left_lens_id'            => $lLens?->id,
                'right_sphere'            => $o['r_sph'] ?? null,
                'right_cylinder'          => $o['r_cyl'] ?? null,
                'right_axis'              => $o['r_ax']  ?? null,
                'right_addition'          => $o['r_add'] ?? null,
                'left_sphere'             => $o['l_sph'] ?? null,
                'left_cylinder'           => $o['l_cyl'] ?? null,
                'left_axis'               => $o['l_ax']  ?? null,
                'left_addition'           => $o['l_add'] ?? null,
                'pupillary_distance'      => $o['pd'] ?? null,
                'price_frame'             => $framePrice,
                'price_lenses'            => $lensPrice,
                'total_price'             => $total,
                'deposit_paid'            => $deposit,
                'remaining_amount'        => $remaining,
                'supplier_id'             => $supplier?->id,
                'assigned_to'             => $doctor->id,
                'status'                  => $o['status'],
                'expected_date'           => $createdAt->copy()->addDays(7)->toDateString(),
                'delivery_date'           => $o['status'] === OpticalOrderStatus::Delivered ? $createdAt->copy()->addDays(6)->toDateString() : null,
                'notes'                   => $o['notes'],
                'created_by'              => $admin->id,
                'created_at'              => $createdAt,
                'updated_at'              => $createdAt,
            ]);

            // Décrémenter le stock monture (sauf annulé)
            if ($frame && $o['status'] !== OpticalOrderStatus::Cancelled) {
                $frame->decrement('stock_quantity');
            }
        }
    }
}
