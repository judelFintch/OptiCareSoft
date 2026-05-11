<x-opticare-layout>
    <x-slot:pageTitle>{{ $product->name }}</x-slot:pageTitle>

    {{-- En-tête --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-bold text-slate-900">{{ $product->name }}</h1>
                @if($product->is_active)
                    <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Actif</span>
                @else
                    <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-500">Inactif</span>
                @endif
                @if($product->isLowStock())
                    <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">Stock faible</span>
                @endif
                @if($product->isExpired())
                    <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">Expiré</span>
                @elseif($product->isExpiringSoon())
                    <span class="rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-medium text-orange-700">Expire bientôt</span>
                @endif
            </div>
            <p class="mt-1 text-sm text-slate-500">
                Réf. <span class="font-mono font-medium text-slate-700">{{ $product->reference }}</span>
                @if($product->generic_name)
                    · {{ $product->generic_name }}
                @endif
            </p>
        </div>
        <div class="flex shrink-0 gap-2">
            @can('pharmacy.manage')
                <a href="{{ route('pharmacy.products.edit', $product) }}"
                   class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Modifier
                </a>
            @endcan
            <a href="{{ route('pharmacy.products.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                ← Retour
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Colonne principale --}}
        <div class="space-y-6 lg:col-span-2">

            {{-- Informations produit --}}
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Informations</h2>
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs text-slate-400">Catégorie</dt>
                        <dd class="mt-0.5 text-sm font-medium text-slate-800">
                            {{ match($product->category) {
                                'collyre'            => 'Collyre / Antibiotique',
                                'larme_artificielle' => 'Larme artificielle',
                                'anti_inflammatoire' => 'Anti-inflammatoire',
                                'antiglaucome'       => 'Antiglaucome',
                                'antiallergique'     => 'Antiallergique',
                                'mydriatique'        => 'Mydriatique / Cycloplégique',
                                'anesthesique'       => 'Anesthésique',
                                'vitamine'           => 'Vitamine / Complément',
                                default              => ucfirst($product->category),
                            } }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Forme galénique</dt>
                        <dd class="mt-0.5 text-sm font-medium text-slate-800">{{ ucfirst($product->form ?? '—') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Dosage / Conditionnement</dt>
                        <dd class="mt-0.5 text-sm font-medium text-slate-800">{{ $product->dosage ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Fabricant</dt>
                        <dd class="mt-0.5 text-sm font-medium text-slate-800">{{ $product->manufacturer ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Fournisseur</dt>
                        <dd class="mt-0.5 text-sm font-medium text-slate-800">{{ $product->supplier?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Ordonnance requise</dt>
                        <dd class="mt-0.5 text-sm font-medium {{ $product->is_prescription_required ? 'text-orange-600' : 'text-green-600' }}">
                            {{ $product->is_prescription_required ? 'Oui' : 'Non' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Date d'expiration</dt>
                        <dd class="mt-0.5 text-sm font-medium {{ $product->isExpired() ? 'text-red-600' : ($product->isExpiringSoon() ? 'text-orange-600' : 'text-slate-800') }}">
                            {{ $product->expiry_date?->format('d/m/Y') ?? '—' }}
                        </dd>
                    </div>
                </dl>
            </section>

            {{-- Historique mouvements de stock --}}
            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Historique du stock</h2>
                    <span class="text-xs text-slate-400">20 derniers mouvements</span>
                </div>
                @if($movements->isEmpty())
                    <p class="px-6 py-8 text-center text-sm text-slate-400">Aucun mouvement enregistré.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Type</th>
                                    <th class="px-4 py-3 text-right">Qté</th>
                                    <th class="px-4 py-3 text-right">Avant</th>
                                    <th class="px-4 py-3 text-right">Après</th>
                                    <th class="px-4 py-3">Référence</th>
                                    <th class="px-4 py-3">Par</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($movements as $mv)
                                    @php $positive = $mv->movement_type->isPositive(); @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-slate-500">{{ $mv->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                                {{ $positive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $mv->movement_type->label() }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold
                                            {{ $positive ? 'text-green-700' : 'text-red-600' }}">
                                            {{ $positive ? '+' : '-' }}{{ $mv->quantity }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-slate-500">{{ $mv->stock_before }}</td>
                                        <td class="px-4 py-3 text-right font-medium text-slate-800">{{ $mv->stock_after }}</td>
                                        <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $mv->reference ?? '—' }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $mv->creator?->name ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Stock & Prix --}}
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Stock & Prix</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                        <span class="text-sm text-slate-500">Stock actuel</span>
                        <span class="text-xl font-bold {{ $product->isLowStock() ? 'text-red-600' : 'text-slate-900' }}">
                            {{ $product->stock_quantity }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 px-4 py-3">
                        <span class="text-sm text-slate-500">Seuil réapprovisionnement</span>
                        <span class="text-sm font-semibold text-slate-700">{{ $product->reorder_level }}</span>
                    </div>
                    <div class="border-t border-slate-100 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Prix d'achat</span>
                            <span class="font-medium text-slate-700">{{ number_format((float) $product->purchase_price, 0, ',', ' ') }} FC</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Prix de vente</span>
                            <span class="font-semibold text-slate-900">{{ number_format((float) $product->selling_price, 0, ',', ' ') }} FC</span>
                        </div>
                        @php $margin = $product->purchase_price > 0
                            ? round((($product->selling_price - $product->purchase_price) / $product->purchase_price) * 100, 1)
                            : 0;
                        @endphp
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Marge</span>
                            <span class="font-medium {{ $margin >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $margin }}%
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Valeur stock --}}
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Valeur du stock</p>
                <p class="mt-1 text-2xl font-bold text-[#0f4c81]">
                    {{ number_format($product->stock_quantity * (float) $product->selling_price, 0, ',', ' ') }} FC
                </p>
                <p class="mt-0.5 text-xs text-slate-400">Au prix de vente · {{ $product->stock_quantity }} unités</p>
            </section>

            {{-- Danger zone --}}
            @can('pharmacy.manage')
                <section class="rounded-xl border border-red-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-3 text-sm font-semibold text-red-700">Zone dangereuse</h3>
                    <form method="POST" action="{{ route('pharmacy.products.destroy', $product) }}"
                          onsubmit="return confirm('Supprimer {{ addslashes($product->name) }} ? Cette action est irréversible.')">
                        @csrf @method('DELETE')
                        <button class="w-full rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                            Supprimer ce produit
                        </button>
                    </form>
                </section>
            @endcan
        </div>
    </div>
</x-opticare-layout>
