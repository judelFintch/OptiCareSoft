<x-opticare-layout>
    <x-slot:pageTitle>Ventes Pharmacie</x-slot:pageTitle>

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Numéro, patient…"
                   class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81] focus:border-[#0f4c81] w-64">
            <select name="status" class="rounded-lg border-slate-300 text-sm shadow-sm">
                <option value="">Tous statuts</option>
                <option value="paid"   {{ request('status') === 'paid'   ? 'selected' : '' }}>Payé</option>
                <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Impayé</option>
            </select>
            <button class="rounded-lg bg-slate-700 px-3 py-2 text-sm font-medium text-white hover:bg-slate-800">Filtrer</button>
        </form>
        @can('pharmacy.manage')
            <a href="{{ route('pharmacy.sales.create') }}"
               class="rounded-lg bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                + Nouvelle vente
            </a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">N° Vente</th>
                    <th class="px-4 py-3">Patient</th>
                    <th class="px-4 py-3">Montant</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3">Servi par</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($sales as $sale)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono font-medium text-slate-800">{{ $sale->sale_number }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $sale->patient?->full_name ?? '—' }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-800">{{ number_format($sale->total_amount, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $sale->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $sale->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $sale->servedBy?->name }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('pharmacy.sales.show', $sale) }}"
                               class="font-medium text-[#0f4c81] hover:underline">Détails</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-sm text-slate-400">Aucune vente enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $sales->links() }}</div>
</x-opticare-layout>
