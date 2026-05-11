<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PaymentMethod;
use App\Models\Consultation;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OpticalOrder;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InvoicePaymentSeeder extends Seeder
{
    public function run(): void
    {
        $cashier   = User::role('Cashier')->first() ?? User::first();
        $currency  = Currency::where('is_default', true)->first();
        $patients  = Patient::all();
        $consults  = Consultation::all();

        if ($patients->isEmpty() || !$currency) return;

        $invNum = 1;
        $payNum = 1;

        // Factures de consultation
        foreach ($consults->take(15) as $idx => $consultation) {
            $patient   = $consultation->patient;
            $issueDate = Carbon::parse($consultation->created_at);

            $subtotal = 25000; // Consultation standard 25 000 FC
            if ($idx % 4 === 0) $subtotal = 40000; // Bilan complet
            if ($idx % 7 === 0) $subtotal = 15000; // Simple contrôle

            $total = $subtotal;

            // Statut paiement variable
            $isPaid        = $idx % 4 !== 2;
            $isPartial     = $idx % 5 === 1;
            $paidAmount    = $isPaid ? ($isPartial ? intval($total * 0.6) : $total) : 0;
            $remaining     = $total - $paidAmount;

            $status = match(true) {
                $paidAmount === 0    => InvoiceStatus::Unpaid,
                $paidAmount < $total => InvoiceStatus::PartiallyPaid,
                default              => InvoiceStatus::Paid,
            };

            $invoice = Invoice::create([
                'invoice_number'   => 'FAC-' . date('Y') . '-' . str_pad($invNum++, 5, '0', STR_PAD_LEFT),
                'patient_id'       => $patient->id,
                'consultation_id'  => $consultation->id,
                'invoice_type'     => InvoiceType::Consultation,
                'status'           => $status,
                'subtotal'         => $subtotal,
                'discount_amount'  => 0,
                'tax_amount'       => 0,
                'total_amount'     => $total,
                'paid_amount'      => $paidAmount,
                'remaining_amount' => $remaining,
                'currency_id'      => $currency->id,
                'exchange_rate'    => 1,
                'created_by'       => $cashier->id,
                'issued_at'        => $issueDate,
                'due_date'         => $issueDate->copy()->addDays(30),
                'paid_at'          => $status === InvoiceStatus::Paid ? $issueDate->copy()->addHours(rand(1, 6)) : null,
                'created_at'       => $issueDate,
                'updated_at'       => $issueDate,
            ]);

            InvoiceItem::create([
                'invoice_id'       => $invoice->id,
                'item_type'        => 'consultation',
                'label'            => 'Consultation ophtalmologique',
                'description'      => 'Consultation Dr. ' . $consultation->doctor?->name . ' — ' . $consultation->consultation_code,
                'quantity'         => 1,
                'unit_price'       => $subtotal,
                'discount_percent' => 0,
                'total'            => $subtotal,
            ]);

            // Enregistrer le paiement
            if ($paidAmount > 0) {
                $method = match($idx % 3) {
                    0 => PaymentMethod::Cash,
                    1 => PaymentMethod::MobileMoney,
                    2 => PaymentMethod::Cash,
                    default => PaymentMethod::Cash,
                };
                Payment::create([
                    'payment_number' => 'PAY-' . str_pad($payNum++, 6, '0', STR_PAD_LEFT),
                    'invoice_id'     => $invoice->id,
                    'patient_id'     => $patient->id,
                    'amount'         => $paidAmount,
                    'currency_id'    => $currency->id,
                    'exchange_rate'  => 1,
                    'payment_method' => $method,
                    'reference'      => $method === PaymentMethod::MobileMoney ? 'MM-' . rand(100000, 999999) : null,
                    'received_by'    => $cashier->id,
                    'paid_at'        => $issueDate->copy()->addHours(rand(1, 5)),
                    'created_at'     => $issueDate,
                    'updated_at'     => $issueDate,
                ]);
            }
        }

        // Factures optiques (commandes livrées)
        $deliveredOrders = OpticalOrder::where('status', 'delivered')->get();
        foreach ($deliveredOrders as $order) {
            $patient   = $order->patient;
            $issueDate = Carbon::parse($order->created_at)->addDays(6);

            $total   = (float) $order->total_price;
            $deposit = (float) $order->deposit_paid;

            $invoice = Invoice::create([
                'invoice_number'   => 'FAC-' . date('Y') . '-' . str_pad($invNum++, 5, '0', STR_PAD_LEFT),
                'patient_id'       => $patient->id,
                'invoice_type'     => InvoiceType::Optical,
                'status'           => $deposit >= $total ? InvoiceStatus::Paid : InvoiceStatus::PartiallyPaid,
                'subtotal'         => $total,
                'discount_amount'  => 0,
                'tax_amount'       => 0,
                'total_amount'     => $total,
                'paid_amount'      => $deposit,
                'remaining_amount' => max(0, $total - $deposit),
                'currency_id'      => $currency->id,
                'exchange_rate'    => 1,
                'created_by'       => $cashier->id,
                'issued_at'        => $issueDate,
                'due_date'         => $issueDate->copy()->addDays(30),
                'paid_at'          => $deposit >= $total ? $issueDate : null,
                'notes'            => 'Commande optique ' . $order->order_number,
                'created_at'       => $issueDate,
                'updated_at'       => $issueDate,
            ]);

            InvoiceItem::create([
                'invoice_id'       => $invoice->id,
                'item_type'        => 'optical',
                'label'            => 'Équipement optique — ' . $order->order_number,
                'description'      => 'Monture + verres',
                'quantity'         => 1,
                'unit_price'       => $total,
                'discount_percent' => 0,
                'total'            => $total,
            ]);

            if ($deposit > 0) {
                Payment::create([
                    'payment_number' => 'PAY-' . str_pad($payNum++, 6, '0', STR_PAD_LEFT),
                    'invoice_id'     => $invoice->id,
                    'patient_id'     => $patient->id,
                    'amount'         => $deposit,
                    'currency_id'    => $currency->id,
                    'exchange_rate'  => 1,
                    'payment_method' => PaymentMethod::Cash,
                    'received_by'    => $cashier->id,
                    'paid_at'        => Carbon::parse($order->created_at)->addHours(1),
                    'notes'          => 'Acompte commande ' . $order->order_number,
                    'created_at'     => Carbon::parse($order->created_at),
                    'updated_at'     => Carbon::parse($order->created_at),
                ]);
            }
        }

        // 3 factures impayées anciennes pour le rapport dettes
        foreach ($patients->take(3) as $i => $patient) {
            $issueDate = Carbon::now()->subDays(rand(35, 60));
            $total     = [30000, 45000, 20000][$i];
            $invoice = Invoice::create([
                'invoice_number'   => 'FAC-' . date('Y') . '-' . str_pad($invNum++, 5, '0', STR_PAD_LEFT),
                'patient_id'       => $patient->id,
                'invoice_type'     => InvoiceType::Consultation,
                'status'           => InvoiceStatus::Unpaid,
                'subtotal'         => $total,
                'discount_amount'  => 0,
                'tax_amount'       => 0,
                'total_amount'     => $total,
                'paid_amount'      => 0,
                'remaining_amount' => $total,
                'currency_id'      => $currency->id,
                'exchange_rate'    => 1,
                'created_by'       => $cashier->id,
                'issued_at'        => $issueDate,
                'due_date'         => $issueDate->copy()->addDays(30),
                'created_at'       => $issueDate,
                'updated_at'       => $issueDate,
            ]);
            InvoiceItem::create([
                'invoice_id'       => $invoice->id,
                'item_type'        => 'consultation',
                'label'            => 'Consultation ophtalmologique',
                'quantity'         => 1,
                'unit_price'       => $total,
                'discount_percent' => 0,
                'total'            => $total,
            ]);
        }
    }
}
