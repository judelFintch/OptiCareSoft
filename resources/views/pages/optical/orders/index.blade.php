<x-opticare-layout>
    <x-slot:pageTitle>Commandes optiques</x-slot:pageTitle>

    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <form method="GET" class="flex flex-wrap items-end gap-2">
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="N° commande, patient…"
                       class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Statut</label>
                <select name="status" class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                    <option value="">Tous</option>
                    @foreach(\App\Enums\OpticalOrderStatus::cases() as $case)
                        <option value="{{ $case->value }}" {{ request('status') === $case->value ? 'selected' : '' }}>
                            {{ $case->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button class="rounded-lg bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Filtrer</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('optical.orders.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Réinitialiser</a>
                @endif
            </div>
        </form>
        @can('optical_orders.manage')
            <a href="{{ route('optical.orders.create') }}"
               class="rounded-lg bg-[#0f4c81] px-4 py-2 text-sm font-semibold text-white hover:bg-[#0b3f6d]">
                + Nouvelle commande
            </a>
        @endcan
    </div>

    {{-- Stats rapides --}}
    @php
        $statusCounts = $orders->getCollection()->groupBy(fn($o) => $o->status->value)->map->count();
    @endphp

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden mb-4">
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-left">Numéro</th>
                    <th class="px-4 py-3 text-left">Patient</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-right">Reste</th>
                    <th class="px-4 py-3 text-left">Date prévue</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                    @php
                        $colorMap = [
                            'yellow' => 'bg-yellow-100 text-yellow-800',
                            'blue'   => 'bg-blue-100 text-blue-800',
                            'purple' => 'bg-purple-100 text-purple-800',
                            'teal'   => 'bg-teal-100 text-teal-800',
                            'green'  => 'bg-green-100 text-green-800',
                            'red'    => 'bg-red-100 text-red-800',
                        ];
                        $statusObj = $order->status instanceof \App\Enums\OpticalOrderStatus
                            ? $order->status
                            : \App\Enums\OpticalOrderStatus::from($order->status);
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <a href="{{ route('optical.orders.show', $order) }}" class="font-mono font-semibold text-[#0f4c81] hover:underline">
                                {{ $order->order_number }}
                            </a>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $order->created_at->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('patients.show', $order->patient) }}" class="font-medium text-slate-800 hover:text-[#0f4c81]">
                                {{ $order->patient?->full_name }}
                            </a>
                            <div class="text-xs font-mono text-slate-400">{{ $order->patient?->patient_code }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $colorMap[$statusObj->color()] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $statusObj->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-slate-700">{{ number_format($order->total_price, 2) }}</td>
                        <td class="px-4 py-3 text-right {{ $order->remaining_amount > 0 ? 'text-red-600 font-semibold' : 'text-green-600' }}">
                            {{ number_format($order->remaining_amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-slate-500 text-xs">
                            {{ $order->expected_date ? \Carbon\Carbon::parse($order->expected_date)->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('optical.orders.show', $order) }}" class="text-sm font-medium text-[#0f4c81] hover:underline">Voir</a>
                            @can('optical_orders.manage')
                                <span class="text-slate-200 mx-1">|</span>
                                <a href="{{ route('optical.orders.edit', $order) }}" class="text-sm font-medium text-slate-500 hover:underline">Modifier</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-slate-400">Aucune commande trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="border-t border-slate-100 px-4 py-3">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
</x-opticare-layout>
