<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\BillingService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function __construct(
        private BillingService $billingService,
        private PaymentService $paymentService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Invoice::class);
        return view('pages.cashier.index');
    }

    public function addPayment(Request $request, Invoice $invoice)
    {
        $this->authorize('receivePayment', $invoice);

        $validated = $request->validate([
            'amount'         => 'required|numeric|min:1|max:' . $invoice->remaining_amount,
            'payment_method' => 'required|in:cash,mobile_money,bank,card,other',
            'reference'      => 'nullable|string|max:100',
            'paid_by'        => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:300',
        ]);

        $this->paymentService->recordPayment($invoice, $validated, $request->user());

        return back()->with('success', 'Paiement enregistré.');
    }

    public function cancelInvoice(Request $request, Invoice $invoice)
    {
        $this->authorize('cancel', $invoice);

        $validated = $request->validate([
            'reason' => 'required|string|max:300',
        ]);

        $this->billingService->cancelInvoice($invoice, $validated['reason'], $request->user());

        return back()->with('success', 'Facture annulée.');
    }
}
