<x-opticare-layout>
    <x-slot:pageTitle>Factures</x-slot:pageTitle>

    <div class="mb-6 flex justify-end">
        @can('invoices.create')
            <a href="{{ route('cashier.invoices.create') }}" class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Nouvelle facture</a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">Numéro</th>
                    <th class="px-4 py-3">Patient</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Restant</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($invoices as $invoice)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $invoice->invoice_number }}</td>
                        <td class="px-4 py-3">{{ $invoice->patient?->full_name }}</td>
                        <td class="px-4 py-3">{{ number_format((float) $invoice->total_amount, 2) }}</td>
                        <td class="px-4 py-3">{{ number_format((float) $invoice->remaining_amount, 2) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $invoice->status?->value ?? $invoice->status }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('cashier.invoices.show', $invoice) }}" class="font-medium text-[#0f4c81] hover:underline">Ouvrir</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">Aucune facture trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $invoices->links() }}</div>
</x-opticare-layout>
