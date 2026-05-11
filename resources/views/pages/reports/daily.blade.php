<x-opticare-layout>
    <x-slot:pageTitle>Rapport journalier</x-slot:pageTitle>

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex flex-wrap gap-2">
            <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="rounded-md border-slate-300 text-sm shadow-sm">
            <button class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Afficher</button>
        </form>
        @can('reports.export')
            <a href="{{ route('reports.daily.pdf', ['date' => $date->format('Y-m-d')]) }}" target="_blank" class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Exporter PDF</a>
        @endcan
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach([
            'Visites ouvertes' => $report['visits_opened'],
            'Visites clôturées' => $report['visits_closed'],
            'Consultations' => $report['consultations'],
            'Factures émises' => $report['invoices_issued'],
            'Montant facturé' => number_format((float) $report['invoiced_total'], 2, ',', ' '),
            'Rendez-vous' => $report['appointments_total'],
            'Rendez-vous réalisés' => $report['appointments_done'],
            'Nouveaux patients' => $report['new_patients'],
            'Recettes' => number_format((float) $report['revenue']['total'], 2, ',', ' '),
        ] as $label => $value)
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">{{ $label }}</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-900">Encaissements par méthode</h2>
            <div class="mt-4 space-y-3">
                @foreach([
                    'Espèces' => $report['revenue']['cash'],
                    'Mobile Money' => $report['revenue']['mobile'],
                    'Banque' => $report['revenue']['bank'],
                    'Carte' => $report['revenue']['card'],
                    'Autre' => $report['revenue']['other'],
                ] as $label => $amount)
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2 text-sm">
                        <span class="text-slate-600">{{ $label }}</span>
                        <span class="font-medium text-slate-900">{{ number_format((float) $amount, 2, ',', ' ') }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-900">Factures par statut</h2>
            <div class="mt-4 space-y-3">
                @forelse($report['invoices_by_status'] as $status => $count)
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2 text-sm">
                        <span class="text-slate-600">{{ str_replace('_', ' ', ucfirst($status)) }}</span>
                        <span class="font-medium text-slate-900">{{ $count }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucune facture émise.</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="mt-6 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-base font-semibold text-slate-900">Paiements du jour</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-3 py-2">Heure</th>
                        <th class="px-3 py-2">Reçu</th>
                        <th class="px-3 py-2">Patient</th>
                        <th class="px-3 py-2">Méthode</th>
                        <th class="px-3 py-2 text-right">Montant</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($report['payments'] as $payment)
                        <tr>
                            <td class="px-3 py-2 text-slate-600">{{ $payment->paid_at?->format('H:i') }}</td>
                            <td class="px-3 py-2 font-medium text-slate-900">{{ $payment->payment_number }}</td>
                            <td class="px-3 py-2 text-slate-600">{{ $payment->patient?->full_name }}</td>
                            <td class="px-3 py-2 text-slate-600">{{ $payment->payment_method?->label() ?? $payment->payment_method }}</td>
                            <td class="px-3 py-2 text-right font-medium text-slate-900">{{ number_format((float) $payment->amount, 2, ',', ' ') }} {{ $payment->currency?->code }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">Aucun paiement enregistré.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="mt-6 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-base font-semibold text-slate-900">Factures émises</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-3 py-2">Facture</th>
                        <th class="px-3 py-2">Patient</th>
                        <th class="px-3 py-2">Type</th>
                        <th class="px-3 py-2">Statut</th>
                        <th class="px-3 py-2 text-right">Total</th>
                        <th class="px-3 py-2 text-right">Reste</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($report['invoices'] as $invoice)
                        <tr>
                            <td class="px-3 py-2"><a href="{{ route('cashier.invoices.show', $invoice) }}" class="font-medium text-[#0f4c81] hover:underline">{{ $invoice->invoice_number }}</a></td>
                            <td class="px-3 py-2 text-slate-600">{{ $invoice->patient?->full_name }}</td>
                            <td class="px-3 py-2 text-slate-600">{{ $invoice->invoice_type?->label() ?? $invoice->invoice_type }}</td>
                            <td class="px-3 py-2 text-slate-600">{{ $invoice->status?->label() ?? $invoice->status }}</td>
                            <td class="px-3 py-2 text-right font-medium text-slate-900">{{ number_format((float) $invoice->total_amount, 2, ',', ' ') }} {{ $invoice->currency?->code }}</td>
                            <td class="px-3 py-2 text-right text-slate-600">{{ number_format((float) $invoice->remaining_amount, 2, ',', ' ') }} {{ $invoice->currency?->code }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-3 py-6 text-center text-slate-500">Aucune facture émise.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-opticare-layout>
