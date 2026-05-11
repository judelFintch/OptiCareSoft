<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function recordPayment(Invoice $invoice, array $data, User $receiver): Payment
    {
        throw_if(! $invoice->canBePaid(), \Exception::class, 'Cette facture ne peut plus être payée.');
        throw_if($data['amount'] > $invoice->remaining_amount, \Exception::class,
            'Le montant dépasse le reste à payer (' . $invoice->remaining_amount . ').');

        return DB::transaction(function () use ($invoice, $data, $receiver) {
            $payment = Payment::create([
                'payment_number' => $this->generatePaymentNumber(),
                'invoice_id'     => $invoice->id,
                'patient_id'     => $invoice->patient_id,
                'amount'         => $data['amount'],
                'currency_id'    => $invoice->currency_id,
                'exchange_rate'  => $invoice->exchange_rate,
                'payment_method' => $data['payment_method'],
                'reference'      => $data['reference'] ?? null,
                'paid_by'        => $data['paid_by'] ?? null,
                'received_by'    => $receiver->id,
                'paid_at'        => $data['paid_at'] ?? now(),
                'notes'          => $data['notes'] ?? null,
            ]);

            $this->updateInvoiceAfterPayment($invoice, $data['amount']);

            return $payment;
        });
    }

    public function getPatientDebt(Patient $patient): float
    {
        return $patient->invoices()
            ->whereIn('status', [InvoiceStatus::Unpaid->value, InvoiceStatus::PartiallyPaid->value])
            ->sum('remaining_amount');
    }

    public function getInvoiceBalance(Invoice $invoice): float
    {
        return $invoice->remaining_amount;
    }

    private function updateInvoiceAfterPayment(Invoice $invoice, float $amount): void
    {
        $newPaid      = $invoice->paid_amount + $amount;
        $newRemaining = $invoice->total_amount - $newPaid;

        $status = match(true) {
            $newRemaining <= 0 => InvoiceStatus::Paid,
            $newPaid > 0      => InvoiceStatus::PartiallyPaid,
            default            => InvoiceStatus::Unpaid,
        };

        $invoice->update([
            'paid_amount'      => $newPaid,
            'remaining_amount' => max(0, $newRemaining),
            'status'           => $status,
            'paid_at'          => $status === InvoiceStatus::Paid ? now() : null,
        ]);
    }

    private function generatePaymentNumber(): string
    {
        $prefix = 'PAY-' . now()->format('Ymd') . '-';
        $last   = Payment::where('payment_number', 'like', $prefix . '%')
            ->orderByDesc('payment_number')
            ->value('payment_number');

        $seq = $last ? ((int) substr($last, -4) + 1) : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
