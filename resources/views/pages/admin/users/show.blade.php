<x-opticare-layout>
    <x-slot:pageTitle>{{ $user->name }}</x-slot:pageTitle>

    {{-- En-tête --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#0f4c81] text-xl font-bold text-white">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-slate-900">{{ $user->name }}</h1>
                    @if($user->is_active)
                        <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Actif</span>
                    @else
                        <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-600">Inactif</span>
                    @endif
                </div>
                <p class="text-sm text-slate-500">{{ $user->email }}</p>
                @if($user->specialty)
                    <p class="text-sm text-slate-500">{{ $user->specialty }}</p>
                @endif
            </div>
        </div>
        <div class="flex shrink-0 gap-2">
            <a href="{{ route('admin.users.edit', $user) }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Modifier
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                ← Retour
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-4">
        @foreach([
            ['label' => 'Consultations', 'value' => $stats['consultations'], 'color' => 'text-[#0f4c81]'],
            ['label' => 'Paiements reçus', 'value' => $stats['payments'], 'color' => 'text-green-600'],
            ['label' => 'Patients créés', 'value' => $stats['patients'], 'color' => 'text-purple-600'],
            ['label' => 'Visites ouvertes', 'value' => $stats['visits'], 'color' => 'text-orange-600'],
        ] as $stat)
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm text-center">
                <p class="text-2xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Colonne principale --}}
        <div class="space-y-6 lg:col-span-2">

            {{-- Consultations récentes --}}
            @if($stats['consultations'] > 0)
                <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Consultations récentes</h2>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @forelse($user->consultations as $consultation)
                            <div class="flex items-center justify-between px-6 py-3 text-sm hover:bg-slate-50">
                                <div>
                                    <p class="font-medium text-slate-800">{{ $consultation->patient?->full_name ?? '—' }}</p>
                                    <p class="text-xs text-slate-400">{{ $consultation->consultation_code }}</p>
                                </div>
                                <div class="text-right">
                                    @if($consultation->primary_diagnosis)
                                        <p class="text-xs text-slate-600">{{ $consultation->primary_diagnosis->label() }}</p>
                                    @endif
                                    <p class="text-xs text-slate-400">{{ $consultation->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="px-6 py-4 text-sm text-slate-400">Aucune consultation.</p>
                        @endforelse
                    </div>
                </section>
            @endif

            {{-- Journal d'activité --}}
            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Activité récente</h2>
                    <span class="text-xs text-slate-400">15 dernières actions</span>
                </div>
                @if($recentActivity->isEmpty())
                    <p class="px-6 py-8 text-center text-sm text-slate-400">Aucune activité enregistrée.</p>
                @else
                    <div class="divide-y divide-slate-50">
                        @foreach($recentActivity as $activity)
                            <div class="flex items-start gap-4 px-6 py-3">
                                <div class="mt-0.5 shrink-0">
                                    @php
                                        $eventColor = match($activity->event) {
                                            'created' => 'bg-green-100 text-green-700',
                                            'updated' => 'bg-blue-100 text-blue-700',
                                            'deleted' => 'bg-red-100 text-red-700',
                                            default   => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $eventColor }}">
                                        {{ ucfirst($activity->event ?? 'action') }}
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 text-sm">
                                    <p class="text-slate-700">
                                        <span class="font-medium">{{ class_basename($activity->subject_type ?? '') }}</span>
                                        @if($activity->subject_id)
                                            <span class="text-slate-400">#{{ $activity->subject_id }}</span>
                                        @endif
                                    </p>
                                    @if($activity->description)
                                        <p class="text-xs text-slate-400 truncate">{{ $activity->description }}</p>
                                    @endif
                                </div>
                                <time class="shrink-0 text-xs text-slate-400">
                                    {{ $activity->created_at->diffForHumans() }}
                                </time>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Informations --}}
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Informations</h2>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-xs text-slate-400">Téléphone</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $user->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Spécialité</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $user->specialty ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Membre depuis</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">{{ $user->created_at->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">Dernière connexion</dt>
                        <dd class="mt-0.5 font-medium text-slate-800">
                            {{ $user->last_login_at?->diffForHumans() ?? '—' }}
                        </dd>
                    </div>
                </dl>
            </section>

            {{-- Rôles & permissions --}}
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Rôles</h2>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->roles as $role)
                        <span class="rounded-full bg-[#0f4c81]/10 px-3 py-1 text-xs font-semibold text-[#0f4c81]">
                            {{ $role->name }}
                        </span>
                    @empty
                        <p class="text-sm text-slate-400">Aucun rôle assigné.</p>
                    @endforelse
                </div>

                @if($user->getAllPermissions()->isNotEmpty())
                    <h3 class="mb-2 mt-5 text-xs font-semibold uppercase tracking-wide text-slate-400">Permissions directes</h3>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($user->getDirectPermissions() as $perm)
                            <span class="rounded bg-slate-100 px-2 py-0.5 font-mono text-xs text-slate-600">
                                {{ $perm->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- Actions --}}
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm space-y-2">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="block w-full rounded-lg bg-[#0f4c81] px-4 py-2 text-center text-sm font-medium text-white hover:bg-[#0b3f6d]">
                    Modifier le compte
                </a>
                @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return confirm('{{ $user->is_active ? 'Désactiver' : 'Supprimer' }} ce compte ?')">
                        @csrf @method('DELETE')
                        <button class="w-full rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                            {{ $user->is_active ? 'Désactiver le compte' : 'Compte déjà inactif' }}
                        </button>
                    </form>
                @endif
            </section>
        </div>
    </div>
</x-opticare-layout>
