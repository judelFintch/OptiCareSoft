<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Setting;
use App\Services\BillingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(private BillingService $billingService) {}

    public function index()
    {
        $invoices = Invoice::with(['patient', 'currency'])
            ->latest()
            ->paginate(20);
        return view('pages.cashier.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $patients = Patient::active()->orderBy('last_name')->get();
        return view('pages.cashier.invoices.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Invoice::class);

        $validated = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'invoice_type' => 'required|in:consultation,optical,pharmacy,exam,global',
            'notes'        => 'nullable|string|max:500',
            'items'        => 'required|array|min:1',
            'items.*.label'      => 'required|string|max:255',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $patient = Patient::findOrFail($validated['patient_id']);
        $invoice = $this->billingService->createInvoice($patient, $validated['invoice_type'], $request->user());

        foreach ($validated['items'] as $item) {
            $this->billingService->addItem($invoice, $item);
        }

        return redirect()->route('cashier.invoices.show', $invoice)->with('success', 'Facture créée.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient', 'items', 'payments.receiver', 'currency', 'creator']);
        return view('pages.cashier.invoices.show', compact('invoice'));
    }

    public function invoicePdf(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load(['patient', 'items', 'payments.receiver', 'currency', 'creator']);
        $settings = $this->pdfSettings();

        return Pdf::loadView('pdf.invoice', compact('invoice', 'settings'))
            ->setPaper('a4')
            ->stream($invoice->invoice_number . '.pdf');
    }

    public function receiptPdf(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load(['patient', 'items', 'payments.receiver', 'currency', 'creator']);
        $settings = $this->pdfSettings();

        return Pdf::loadView('pdf.receipt', compact('invoice', 'settings'))
            ->setPaper('a4')
            ->stream('receipt-' . $invoice->invoice_number . '.pdf');
    }

    private function pdfSettings(): array
    {
        return [
            'clinic_name' => Setting::get('clinic_name', 'OptiCare Soft'),
            'clinic_slogan' => Setting::get('clinic_slogan', 'La solution intelligente pour gérer votre cabinet ophtalmologique.'),
            'clinic_address' => Setting::get('clinic_address', ''),
            'clinic_phone' => Setting::get('clinic_phone', ''),
            'clinic_email' => Setting::get('clinic_email', ''),
            'invoice_footer' => Setting::get('invoice_footer', 'Merci de votre confiance.'),
        ];
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load(['patient', 'items', 'currency']);
        return view('pages.cashier.invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'notes'           => 'nullable|string|max:1000',
            'due_date'        => 'nullable|date',
            'discount_amount' => 'nullable|numeric|min:0',
            'items'           => 'nullable|array',
            'items.*.label'      => 'required_with:items|string|max:255',
            'items.*.quantity'   => 'required_with:items|integer|min:1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
        ]);

        $update = [
            'notes'    => $validated['notes'] ?? null,
            'due_date' => $validated['due_date'] ?? $invoice->due_date,
        ];

        if (!$invoice->isPaid() && !$invoice->isCancelled()) {
            $discount = (float) ($validated['discount_amount'] ?? 0);
            $items    = $validated['items'] ?? [];

            $subtotal = collect($items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
            $total    = max(0, $subtotal - $discount);

            $update += [
                'subtotal'         => $subtotal,
                'discount_amount'  => $discount,
                'total_amount'     => $total,
                'remaining_amount' => max(0, $total - (float) $invoice->paid_amount),
            ];

            $invoice->items()->delete();
            foreach ($items as $item) {
                $invoice->items()->create([
                    'label'      => $item['label'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total'      => $item['quantity'] * $item['unit_price'],
                ]);
            }
        }

        $invoice->update($update);

        return redirect()->route('cashier.invoices.show', $invoice)
            ->with('success', 'Facture mise à jour.');
    }

    public function destroy(Invoice $invoice) { return back(); }
}
