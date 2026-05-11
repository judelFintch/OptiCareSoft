<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\Consultation;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Setting;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;

class BillingService
{
    public function createInvoice(
        Patient $patient,
        string  $type,
        User    $creator,
        ?Visit  $visit = null,
        array   $meta  = []
    ): Invoice {
        $currency = Currency::getDefault() ?? Currency::first();

        return DB::transaction(function () use ($patient, $type, $creator, $visit, $meta, $currency) {
            return Invoice::create([
                'invoice_number'  => $this->generateInvoiceNumber(),
                'patient_id'      => $patient->id,
                'visit_id'        => $visit?->id,
                'consultation_id' => $meta['consultation_id'] ?? null,
                'invoice_type'    => $type,
                'status'          => InvoiceStatus::Unpaid,
                'subtotal'        => 0,
                'discount_amount' => 0,
                'tax_amount'      => 0,
                'total_amount'    => 0,
                'paid_amount'     => 0,
                'remaining_amount'=> 0,
                'currency_id'     => $currency->id,
                'exchange_rate'   => $currency->exchange_rate,
                'notes'           => $meta['notes'] ?? null,
                'created_by'      => $creator->id,
                'issued_at'       => now(),
            ]);
        });
    }

    public function addItem(Invoice $invoice, array $item): InvoiceItem
    {
        $discount = $item['discount_percent'] ?? 0;
        $total    = $item['quantity'] * $item['unit_price'] * (1 - $discount / 100);

        $line = $invoice->items()->create([
            'item_type'       => $item['item_type'] ?? null,
            'item_id'         => $item['item_id'] ?? null,
            'label'           => $item['label'],
            'description'     => $item['description'] ?? null,
            'quantity'        => $item['quantity'],
            'unit_price'      => $item['unit_price'],
            'discount_percent'=> $discount,
            'total'           => round($total, 2),
        ]);

        $this->recalculate($invoice);

        return $line;
    }

    public function createConsultationInvoice(Consultation $consultation, User $creator): Invoice
    {
        $existingInvoice = Invoice::with('items')
            ->where('consultation_id', $consultation->id)
            ->where('invoice_type', InvoiceType::Consultation->value)
            ->where('status', '!=', InvoiceStatus::Cancelled->value)
            ->first();

        if ($existingInvoice) {
            return $existingInvoice;
        }

        return DB::transaction(function () use ($consultation, $creator) {
            $invoice = $this->createInvoice(
                $consultation->patient,
                InvoiceType::Consultation->value,
                $creator,
                $consultation->visit,
                [
                    'consultation_id' => $consultation->id,
                    'notes' => 'Facture générée depuis la consultation ' . $consultation->consultation_code,
                ]
            );

            $this->addItem($invoice, [
                'item_type' => Consultation::class,
                'item_id' => $consultation->id,
                'label' => 'Consultation ophtalmologique',
                'description' => $consultation->consultation_code,
                'quantity' => 1,
                'unit_price' => (float) Setting::get('consultation_fee', 0),
            ]);

            return $invoice->fresh(['items']);
        });
    }

    public function removeItem(InvoiceItem $item): void
    {
        $invoice = $item->invoice;
        $item->delete();
        $this->recalculate($invoice);
    }

    public function recalculate(Invoice $invoice): Invoice
    {
        $subtotal = $invoice->items()->sum('total');
        $total    = $subtotal - $invoice->discount_amount + $invoice->tax_amount;
        $remaining = $total - $invoice->paid_amount;

        $invoice->update([
            'subtotal'        => $subtotal,
            'total_amount'    => $total,
            'remaining_amount'=> max(0, $remaining),
        ]);

        return $invoice->fresh();
    }

    public function cancelInvoice(Invoice $invoice, string $reason, User $canceller): Invoice
    {
        throw_if($invoice->isCancelled(), \Exception::class, 'Cette facture est déjà annulée.');
        throw_if($invoice->isPaid(), \Exception::class, 'Une facture payée ne peut pas être annulée directement.');

        $invoice->update([
            'status'               => InvoiceStatus::Cancelled,
            'cancellation_reason'  => $reason,
            'cancelled_by'         => $canceller->id,
            'cancelled_at'         => now(),
        ]);

        return $invoice->fresh();
    }

    public function applyDiscount(Invoice $invoice, float $amount): Invoice
    {
        $invoice->update(['discount_amount' => $amount]);
        return $this->recalculate($invoice);
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';
        $last   = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $seq = $last ? ((int) substr($last, -4) + 1) : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
