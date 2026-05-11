<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Patient;
use App\Services\BillingService;
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

    public function edit(Invoice $invoice) { return view('pages.cashier.invoices.edit', compact('invoice')); }
    public function update(Request $request, Invoice $invoice) { return back(); }
    public function destroy(Invoice $invoice) { return back(); }
}
