<x-opticare-layout>
    <x-slot:pageTitle>Vente {{ $sale->sale_number }}</x-slot:pageTitle>

    <div class="mb-4">
        <a href="{{ route('pharmacy.sales.index') }}" class="text-sm text-[#0f4c81] hover:underline">← Retour aux ventes</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-4">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-800">Produits vendus</h2>
                    <span class="rounded-full px-3 py-1 text-sm font-medium
                        {{ $sale->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $sale->status_label }}
                    </span>
                </div>
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="px-3 py-2 text-left">Produit</th>
                            <th class="px-3 py-2 text-center">Qté</th>
                            <th class="px-3 py-2 text-right">Prix U.</th>
                            <th class="px-3 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($sale->items as $item)
                            <tr>
                                <td class="px-3 py-3 font-medium text-slate-800">{{ $item->product?->name }}</td>
                                <td class="px-3 py-3 text-center text-slate-600">{{ $item->quantity }}</td>
                                <td class="px-3 py-3 text-right text-slate-600">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-3 py-3 text-right font-semibold text-slate-800">{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50">
                        <tr>
                            <td colspan="3" class="px-3 py-3 text-right font-semibold text-slate-700">Total</td>
                            <td class="px-3 py-3 text-right text-xl font-bold text-[#0f4c81]">
                                {{ number_format($sale->total_amount, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm self-start">
            <h3 class="mb-4 font-semibold text-slate-800">Informations</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">N° vente</dt>
                    <dd class="font-mono font-semibold text-slate-800">{{ $sale->sale_number }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Patient</dt>
                    <dd class="text-slate-700">{{ $sale->patient?->full_name ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Servi par</dt>
                    <dd class="text-slate-700">{{ $sale->servedBy?->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Date</dt>
                    <dd class="text-slate-700">{{ $sale->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                @if($sale->medicalPrescription)
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Ordonnance</dt>
                        <dd>
                            <a href="{{ route('medical-prescriptions.show', $sale->medicalPrescription) }}"
                               class="text-[#0f4c81] hover:underline text-xs">Voir</a>
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
</x-opticare-layout>
