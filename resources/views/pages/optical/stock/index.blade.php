<x-opticare-layout>
    <x-slot:pageTitle>Stock Optique</x-slot:pageTitle>

    <div x-data="{ tab: 'frames' }" class="space-y-6">

        <div class="flex gap-1 rounded-xl border border-slate-200 bg-white p-1 w-fit shadow-sm">
            <button @click="tab = 'frames'"
                    :class="tab === 'frames' ? 'bg-[#0f4c81] text-white shadow' : 'text-slate-600 hover:bg-slate-100'"
                    class="rounded-lg px-4 py-2 text-sm font-medium transition-all">
                Montures ({{ $frames->count() }})
            </button>
            <button @click="tab = 'lenses'"
                    :class="tab === 'lenses' ? 'bg-[#0f4c81] text-white shadow' : 'text-slate-600 hover:bg-slate-100'"
                    class="rounded-lg px-4 py-2 text-sm font-medium transition-all">
                Verres ({{ $lenses->count() }})
            </button>
        </div>

        {{-- Alertes --}}
        @if($lowFrames->isNotEmpty() || $lowLenses->isNotEmpty())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                <p class="font-semibold text-red-800 mb-2">Alertes stock faible</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($lowFrames as $f)
                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                            Monture: {{ $f->brand }} {{ $f->model }} ({{ $f->stock_quantity }})
                        </span>
                    @endforeach
                    @foreach($lowLenses as $l)
                        <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-700">
                            Verre: {{ $l->brand }} {{ $l->type }} ({{ $l->stock_quantity }})
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Montures --}}
        <div x-show="tab === 'frames'" x-transition>
            <div class="grid gap-6 lg:grid-cols-3">
                @can('stock.manage')
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm self-start">
                        <h3 class="font-semibold text-slate-800 mb-4">Mouvement monture</h3>
                        <form method="POST" action="{{ route('optical.stock.frames') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Monture</label>
                                <select name="frame_id" required
                                        class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                                    <option value="">Choisir…</option>
                                    @foreach($frames as $frame)
                                        <option value="{{ $frame->id }}">{{ $frame->brand }} {{ $frame->model }} ({{ $frame->stock_quantity }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Type</label>
                                <select name="movement_type" required
                                        class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                                    <option value="in">Entrée</option>
                                    <option value="out">Sortie</option>
                                    <option value="adjustment">Ajustement</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Quantité</label>
                                <input type="number" name="quantity" min="1" required
                                       class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                                <input type="text" name="notes"
                                       class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            </div>
                            <button type="submit"
                                    class="w-full rounded-lg bg-[#0f4c81] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                                Enregistrer
                            </button>
                        </form>
                    </div>
                @endcan

                <div class="lg:col-span-2 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50 px-5 py-3">
                        <h3 class="font-semibold text-slate-800">Catalogue montures</h3>
                    </div>
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                            <tr>
                                <th class="px-4 py-3 text-left">Réf.</th>
                                <th class="px-4 py-3 text-left">Marque / Modèle</th>
                                <th class="px-4 py-3 text-left">Couleur</th>
                                <th class="px-4 py-3 text-center">Stock</th>
                                <th class="px-4 py-3 text-right">Prix vente</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($frames as $frame)
                                <tr class="hover:bg-slate-50 {{ $frame->isLowStock() ? 'bg-red-50/50' : '' }}">
                                    <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $frame->reference }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $frame->brand }} {{ $frame->model }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $frame->color }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="font-bold {{ $frame->isLowStock() ? 'text-red-600' : 'text-slate-800' }}">
                                            {{ $frame->stock_quantity }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-slate-700">
                                        {{ number_format($frame->selling_price, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-slate-400">Aucune monture.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Verres --}}
        <div x-show="tab === 'lenses'" x-transition>
            <div class="grid gap-6 lg:grid-cols-3">
                @can('stock.manage')
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm self-start">
                        <h3 class="font-semibold text-slate-800 mb-4">Mouvement verre</h3>
                        <form method="POST" action="{{ route('optical.stock.lenses') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Verre</label>
                                <select name="lens_id" required
                                        class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                                    <option value="">Choisir…</option>
                                    @foreach($lenses as $lens)
                                        <option value="{{ $lens->id }}">{{ $lens->brand }} {{ $lens->type }} ({{ $lens->stock_quantity }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Type</label>
                                <select name="movement_type" required
                                        class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                                    <option value="in">Entrée</option>
                                    <option value="out">Sortie</option>
                                    <option value="adjustment">Ajustement</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Quantité</label>
                                <input type="number" name="quantity" min="1" required
                                       class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            </div>
                            <button type="submit"
                                    class="w-full rounded-lg bg-[#0f4c81] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                                Enregistrer
                            </button>
                        </form>
                    </div>
                @endcan

                <div class="lg:col-span-2 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50 px-5 py-3">
                        <h3 class="font-semibold text-slate-800">Catalogue verres</h3>
                    </div>
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                            <tr>
                                <th class="px-4 py-3 text-left">Réf.</th>
                                <th class="px-4 py-3 text-left">Marque / Type</th>
                                <th class="px-4 py-3 text-left">Indice</th>
                                <th class="px-4 py-3 text-center">Stock</th>
                                <th class="px-4 py-3 text-right">Prix</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($lenses as $lens)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $lens->reference }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $lens->brand }} {{ $lens->type }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $lens->index ?? '—' }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-slate-800">{{ $lens->stock_quantity }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-slate-700">{{ number_format($lens->selling_price, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-slate-400">Aucun verre.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-opticare-layout>
