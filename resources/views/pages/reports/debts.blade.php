<x-opticare-layout>
    <x-slot:pageTitle>Rapport dettes patients</x-slot:pageTitle>

    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div class="rounded-xl border border-red-200 bg-red-50 px-6 py-4 shadow-sm">
            <p class="text-xs font-medium uppercase text-red-500 tracking-wide">Total dû</p>
            <p class="mt-1 text-2xl font-bold text-red-700">{{ number_format($total, 2) }}</p>
        </div>
        @can('reports.export')
            <a href="{{ route('reports.export.debts') }}"
               class="flex items-center gap-1.5 rounded-lg border border-green-300 bg-green-50 px-4 py-2 text-sm font-medium text-green-700 hover:bg-green-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Excel
            </a>
        @endcan
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-left">Patient</th>
                    <th class="px-4 py-3 text-left">Facture</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-right">Payé</th>
                    <th class="px-4 py-3 text-right">Reste</th>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($debtors as $invoice)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <a href="{{ route('patients.show', $invoice->patient) }}" class="font-medium text-[#0f4c81] hover:underline">
                                {{ $invoice->patient?->full_name ?? '—' }}
                            </a>
                            <div class="text-xs font-mono text-slate-400">{{ $invoice->patient?->patient_code }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('cashier.invoices.show', $invoice) }}" class="font-mono text-sm text-slate-700 hover:text-[#0f4c81]">
                                {{ $invoice->invoice_number }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-right text-slate-700">{{ number_format($invoice->total_amount, 2) }}</td>
                        <td class="px-4 py-3 text-right text-green-700">{{ number_format($invoice->paid_amount, 2) }}</td>
                        <td class="px-4 py-3 text-right font-bold text-red-700">{{ number_format($invoice->remaining_amount, 2) }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $invoice->issued_at?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusLabel = ['unpaid' => 'Non payée', 'partially_paid' => 'Partiel'];
                                $statusColor = ['unpaid' => 'bg-red-100 text-red-700', 'partially_paid' => 'bg-yellow-100 text-yellow-700'];
                                $sv = $invoice->status instanceof \App\Enums\InvoiceStatus ? $invoice->status->value : $invoice->status;
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusColor[$sv] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $statusLabel[$sv] ?? $sv }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-slate-400">Aucune dette en cours.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-opticare-layout>
