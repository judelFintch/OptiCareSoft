<x-opticare-layout>
    <x-slot:pageTitle>{{ $order->order_number }}</x-slot:pageTitle>

    <div class="mb-4 flex items-center gap-3">
        <a href="{{ route('optical.orders.index') }}" class="text-sm text-[#0f4c81] hover:underline">← Retour aux commandes</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Main content --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Header: numéro + statut --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium uppercase text-slate-400 tracking-wide">Commande</p>
                        <h1 class="text-xl font-bold text-slate-800">{{ $order->order_number }}</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $statusObj = $order->status instanceof \App\Enums\OpticalOrderStatus
                                ? $order->status
                                : \App\Enums\OpticalOrderStatus::from($order->status);
                            $s = $statusObj->value;
                            $colorMap = [
                                'yellow' => 'bg-yellow-100 text-yellow-800',
                                'blue'   => 'bg-blue-100 text-blue-800',
                                'purple' => 'bg-purple-100 text-purple-800',
                                'teal'   => 'bg-teal-100 text-teal-800',
                                'green'  => 'bg-green-100 text-green-800',
                                'red'    => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $colorMap[$statusObj->color()] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $statusObj->label() }}
                        </span>
                        @can('optical_orders.manage')
                            @if(!in_array($s, ['delivered','cancelled']))
                                <form method="POST" action="{{ route('optical.orders.status', $order) }}" class="flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <select name="status" class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                                        <option value="pending"       {{ $s==='pending'       ? 'selected':'' }}>En attente</option>
                                        <option value="ordered"       {{ $s==='ordered'       ? 'selected':'' }}>Commandé</option>
                                        <option value="in_production" {{ $s==='in_production' ? 'selected':'' }}>En fabrication</option>
                                        <option value="ready"         {{ $s==='ready'         ? 'selected':'' }}>Prêt</option>
                                        <option value="cancelled"     {{ $s==='cancelled'     ? 'selected':'' }}>Annulé</option>
                                    </select>
                                    <button class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200">Mettre à jour</button>
                                </form>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>

            {{-- Correction OD/OG --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Correction prescrite</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-xs font-semibold uppercase text-slate-500">
                                <th class="pb-2 pr-4 text-left">Œil</th>
                                <th class="pb-2 px-3 text-center">Sphère</th>
                                <th class="pb-2 px-3 text-center">Cylindre</th>
                                <th class="pb-2 px-3 text-center">Axe</th>
                                <th class="pb-2 px-3 text-center">Addition</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="py-2 pr-4 font-semibold text-slate-700">OD</td>
                                <td class="py-2 px-3 text-center">{{ $order->right_sphere ?? '—' }}</td>
                                <td class="py-2 px-3 text-center">{{ $order->right_cylinder ?? '—' }}</td>
                                <td class="py-2 px-3 text-center">{{ $order->right_axis ?? '—' }}</td>
                                <td class="py-2 px-3 text-center">{{ $order->right_addition ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 pr-4 font-semibold text-slate-700">OG</td>
                                <td class="py-2 px-3 text-center">{{ $order->left_sphere ?? '—' }}</td>
                                <td class="py-2 px-3 text-center">{{ $order->left_cylinder ?? '—' }}</td>
                                <td class="py-2 px-3 text-center">{{ $order->left_axis ?? '—' }}</td>
                                <td class="py-2 px-3 text-center">{{ $order->left_addition ?? '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if($order->pupillary_distance)
                    <p class="mt-3 text-sm text-slate-600">Distance pupillaire : <strong>{{ $order->pupillary_distance }} mm</strong></p>
                @endif
            </div>

            {{-- Équipement --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Équipement</h2>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-xs font-medium uppercase text-slate-400 mb-1">Monture</p>
                        @if($order->frame)
                            <p class="font-semibold text-slate-800">{{ $order->frame->brand }} {{ $order->frame->model }}</p>
                            <p class="text-xs text-slate-500">{{ $order->frame->color }}</p>
                        @else
                            <p class="text-slate-400 text-sm">—</p>
                        @endif
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-xs font-medium uppercase text-slate-400 mb-1">Verre OD</p>
                        @if($order->rightLens)
                            <p class="font-semibold text-slate-800 text-sm">{{ $order->rightLens->full_description }}</p>
                        @else
                            <p class="text-slate-400 text-sm">—</p>
                        @endif
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-xs font-medium uppercase text-slate-400 mb-1">Verre OG</p>
                        @if($order->leftLens)
                            <p class="font-semibold text-slate-800 text-sm">{{ $order->leftLens->full_description }}</p>
                        @else
                            <p class="text-slate-400 text-sm">—</p>
                        @endif
                    </div>
                </div>
                @if($order->special_instructions)
                    <div class="mt-4">
                        <p class="text-xs font-medium uppercase text-slate-400 mb-1">Instructions spéciales</p>
                        <p class="text-sm text-slate-700">{{ $order->special_instructions }}</p>
                    </div>
                @endif
            </div>

            {{-- Paiement --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Paiement</h2>
                <div class="grid gap-4 sm:grid-cols-3 mb-5">
                    <div class="rounded-lg bg-slate-50 p-4 text-center">
                        <p class="text-xs font-medium uppercase text-slate-400 mb-1">Total</p>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($order->total_price, 2) }}</p>
                    </div>
                    <div class="rounded-lg bg-green-50 p-4 text-center">
                        <p class="text-xs font-medium uppercase text-green-600 mb-1">Versé</p>
                        <p class="text-2xl font-bold text-green-700">{{ number_format($order->deposit_paid, 2) }}</p>
                    </div>
                    <div class="rounded-lg bg-blue-50 border border-blue-200 p-4 text-center">
                        <p class="text-xs font-medium uppercase text-blue-600 mb-1">Reste à payer</p>
                        <p class="text-2xl font-bold text-blue-800">{{ number_format($order->remaining_amount, 2) }}</p>
                    </div>
                </div>

                @can('optical_orders.manage')
                    @if($order->remaining_amount > 0 && $s !== 'cancelled')
                        <form method="POST" action="{{ route('optical.orders.deposit', $order) }}" class="flex items-end gap-3">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Ajouter un acompte</label>
                                <input type="number" name="amount" step="0.01" min="0.01" max="{{ $order->remaining_amount }}"
                                       placeholder="Montant"
                                       class="w-40 rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]" required>
                            </div>
                            <button class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                                Enregistrer
                            </button>
                        </form>
                    @endif

                    @if($s === 'ready')
                        <form method="POST" action="{{ route('optical.orders.deliver', $order) }}" class="mt-4">
                            @csrf @method('PATCH')
                            <button class="rounded-lg bg-[#0f4c81] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#0b3f6d]">
                                Marquer comme livrée
                            </button>
                        </form>
                    @endif
                @endcan
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Informations</h2>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-xs font-medium uppercase text-slate-400">Numéro</dt>
                        <dd class="font-mono font-semibold text-slate-800">{{ $order->order_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-slate-400">Patient</dt>
                        <dd>
                            <a href="{{ route('patients.show', $order->patient) }}" class="text-[#0f4c81] hover:underline font-medium">
                                {{ $order->patient->full_name }}
                            </a>
                            <span class="ml-1 text-xs text-slate-400 font-mono">{{ $order->patient->patient_code }}</span>
                        </dd>
                    </div>
                    @if($order->prescription)
                        <div>
                            <dt class="text-xs font-medium uppercase text-slate-400">Ordonnance</dt>
                            <dd>
                                <a href="{{ route('optical-prescriptions.show', $order->prescription) }}" class="text-[#0f4c81] hover:underline">
                                    Voir l'ordonnance
                                </a>
                            </dd>
                        </div>
                    @endif
                    @if($order->supplier)
                        <div>
                            <dt class="text-xs font-medium uppercase text-slate-400">Fournisseur</dt>
                            <dd class="text-slate-700">{{ $order->supplier->name }}</dd>
                        </div>
                    @endif
                    @if($order->assignedTo)
                        <div>
                            <dt class="text-xs font-medium uppercase text-slate-400">Assigné à</dt>
                            <dd class="text-slate-700">{{ $order->assignedTo->name }}</dd>
                        </div>
                    @endif
                    @if($order->expected_date)
                        <div>
                            <dt class="text-xs font-medium uppercase text-slate-400">Date prévue</dt>
                            <dd class="text-slate-700">{{ \Carbon\Carbon::parse($order->expected_date)->format('d/m/Y') }}</dd>
                        </div>
                    @endif
                    @if($order->delivery_date)
                        <div>
                            <dt class="text-xs font-medium uppercase text-slate-400">Date de livraison</dt>
                            <dd class="text-green-700 font-medium">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium uppercase text-slate-400">Créé par</dt>
                        <dd class="text-slate-700">{{ $order->creator?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-slate-400">Créé le</dt>
                        <dd class="text-slate-700">{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @if($order->notes)
                        <div>
                            <dt class="text-xs font-medium uppercase text-slate-400">Notes</dt>
                            <dd class="text-slate-600">{{ $order->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <a href="{{ route('optical.orders.pdf', $order) }}" target="_blank"
                   class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Bon de commande PDF
                </a>
            </div>

            @can('optical_orders.manage')
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm space-y-2">
                    <a href="{{ route('optical.orders.edit', $order) }}"
                       class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Modifier
                    </a>
                    @if(!in_array($s, ['delivered', 'cancelled']))
                        <form method="POST" action="{{ route('optical.orders.destroy', $order) }}"
                              onsubmit="return confirm('Supprimer cette commande ?')">
                            @csrf @method('DELETE')
                            <button class="w-full rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm font-medium text-red-700 hover:bg-red-100">
                                Supprimer
                            </button>
                        </form>
                    @endif
                </div>
            @endcan
        </div>
    </div>
</x-opticare-layout>
