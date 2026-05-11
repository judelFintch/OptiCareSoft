<x-opticare-layout>
    <x-slot:pageTitle>{{ $invoice->invoice_number }}</x-slot:pageTitle>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
            <h2 class="text-base font-semibold text-slate-900">Lignes</h2>
            <div class="mt-4 divide-y divide-slate-100">
                @foreach($invoice->items as $item)
                    <div class="flex justify-between py-3 text-sm">
                        <span>{{ $item->label }} x {{ $item->quantity }}</span>
                        <span class="font-medium">{{ number_format((float) $item->total, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-900">Paiement</h2>
            <dl class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><dt>Total</dt><dd>{{ number_format((float) $invoice->total_amount, 2) }}</dd></div>
                <div class="flex justify-between"><dt>Payé</dt><dd>{{ number_format((float) $invoice->paid_amount, 2) }}</dd></div>
                <div class="flex justify-between font-semibold"><dt>Restant</dt><dd>{{ number_format((float) $invoice->remaining_amount, 2) }}</dd></div>
            </dl>

            @can('receivePayment', $invoice)
                <form method="POST" action="{{ route('cashier.payment', $invoice) }}" class="mt-6 space-y-3">
                    @csrf
                    <input type="number" name="amount" min="1" max="{{ $invoice->remaining_amount }}" step="0.01" required placeholder="Montant" class="w-full rounded-md border-slate-300 text-sm shadow-sm">
                    <select name="payment_method" required class="w-full rounded-md border-slate-300 text-sm shadow-sm">
                        @foreach(['cash', 'mobile_money', 'bank', 'card', 'other'] as $method)
                            <option value="{{ $method }}">{{ $method }}</option>
                        @endforeach
                    </select>
                    <button class="w-full rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Encaisser</button>
                </form>
            @endcan

            <div class="mt-4 grid gap-2">
                <a href="{{ route('cashier.invoices.pdf', $invoice) }}" target="_blank" class="rounded-md border border-slate-300 px-4 py-2 text-center text-sm font-medium text-slate-700 hover:bg-slate-50">Facture PDF</a>
                <a href="{{ route('cashier.invoices.receipt', $invoice) }}" target="_blank" class="rounded-md border border-slate-300 px-4 py-2 text-center text-sm font-medium text-slate-700 hover:bg-slate-50">Reçu PDF</a>
            </div>
        </section>
    </div>
</x-opticare-layout>
