<x-opticare-layout>
    <x-slot:pageTitle>Gestion du stock</x-slot:pageTitle>

    {{-- Alertes --}}
    <div class="grid gap-4 mb-6 md:grid-cols-2">
        {{-- Stock faible --}}
        <div class="rounded-xl border border-red-200 bg-red-50 p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h3 class="font-semibold text-red-800">Stock faible ({{ $lowStock->count() }})</h3>
            </div>
            @forelse($lowStock as $product)
                <div class="flex justify-between items-center py-1.5 border-b border-red-200 last:border-0 text-sm">
                    <span class="text-red-900 font-medium">{{ $product->name }}</span>
                    <span class="font-bold text-red-700">{{ $product->stock_quantity }} / {{ $product->reorder_level }}</span>
                </div>
            @empty
                <p class="text-sm text-red-600 italic">Aucun produit en rupture.</p>
            @endforelse
        </div>

        {{-- Expirations --}}
        <div class="rounded-xl border border-orange-200 bg-orange-50 p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="font-semibold text-orange-800">Expirations proches ({{ $expiringSoon->count() }})</h3>
            </div>
            @forelse($expiringSoon as $product)
                <div class="flex justify-between items-center py-1.5 border-b border-orange-200 last:border-0 text-sm">
                    <span class="text-orange-900 font-medium">{{ $product->name }}</span>
                    <span class="font-bold text-orange-700">{{ $product->expiry_date?->format('d/m/Y') }}</span>
                </div>
            @empty
                <p class="text-sm text-orange-600 italic">Aucun produit expirant bientôt.</p>
            @endforelse
        </div>
    </div>

    {{-- Nouveau mouvement --}}
    @can('stock.manage')
        <div class="mb-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm" x-data="{ open: false }">
            <button @click="open = !open"
                    class="flex w-full items-center justify-between font-semibold text-slate-800">
                <span>Enregistrer un mouvement de stock</span>
                <svg class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition class="mt-4">
                <form method="POST" action="{{ route('pharmacy.stock.store') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Produit <span class="text-red-500">*</span></label>
                        <select name="pharmacy_product_id" required
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            <option value="">Choisir…</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->stock_quantity }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Type <span class="text-red-500">*</span></label>
                        <select name="movement_type" required
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            @foreach(App\Enums\StockMovementType::cases() as $type)
                                <option value="{{ $type->value }}">{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Quantité <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" min="1" required
                               class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Référence</label>
                        <input type="text" name="reference"
                               class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]"
                               placeholder="BL, ordonnance…">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                        <input type="text" name="notes"
                               class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-3 flex justify-end">
                        <button type="submit"
                                class="rounded-lg bg-[#0f4c81] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endcan

    {{-- Historique --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <h3 class="font-semibold text-slate-800">Historique des mouvements</h3>
        </div>
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-left">Produit</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-center">Qté</th>
                    <th class="px-4 py-3 text-center">Avant</th>
                    <th class="px-4 py-3 text-center">Après</th>
                    <th class="px-4 py-3 text-left">Référence</th>
                    <th class="px-4 py-3 text-left">Par</th>
                    <th class="px-4 py-3 text-left">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($movements as $mv)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $mv->stockable?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                {{ in_array($mv->movement_type->value, ['in','return']) ? 'bg-green-100 text-green-700' : ($mv->movement_type->value === 'adjustment' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }}">
                                {{ $mv->movement_type->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center font-semibold">{{ $mv->quantity }}</td>
                        <td class="px-4 py-3 text-center text-slate-500">{{ $mv->stock_before }}</td>
                        <td class="px-4 py-3 text-center font-semibold text-slate-800">{{ $mv->stock_after }}</td>
                        <td class="px-4 py-3 text-slate-600 font-mono text-xs">{{ $mv->reference ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $mv->creator?->name }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $mv->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-sm text-slate-400">Aucun mouvement.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $movements->links() }}</div>
    </div>
</x-opticare-layout>
