<div wire:poll.15s>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-slate-900">File d'attente — {{ now()->format('d/m/Y') }}</h2>
        <span class="text-sm text-slate-500">{{ $visits->count() }} patient(s) en attente</span>
    </div>

    <div class="space-y-3">
        @forelse($visits as $i => $visit)
            @php
                $statusColors = [
                    'open'        => 'bg-blue-50 border-blue-200',
                    'in_progress' => 'bg-purple-50 border-purple-200',
                    'pending'     => 'bg-yellow-50 border-yellow-200',
                ];
                $badgeColors = [
                    'open'        => 'bg-blue-100 text-blue-700',
                    'in_progress' => 'bg-purple-100 text-purple-700',
                    'pending'     => 'bg-yellow-100 text-yellow-700',
                ];
                $statusVal  = $visit->status->value ?? $visit->status;
                $statusLabel = $visit->status->label();
                $cardColor  = $statusColors[$statusVal] ?? 'bg-slate-50 border-slate-200';
                $badge      = $badgeColors[$statusVal] ?? 'bg-slate-100 text-slate-700';
            @endphp
            <div class="rounded-xl border p-4 {{ $cardColor }} transition-all">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-[#0f4c81]/10 text-[#0f4c81] font-bold text-sm flex-shrink-0">
                            {{ $i + 1 }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ $visit->patient?->full_name }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $visit->visit_code }}
                                · Arrivée : {{ $visit->opened_at?->format('H:i') }}
                                @if($visit->opened_at)
                                    · {{ $visit->opened_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badge }}">
                            {{ $statusLabel }}
                        </span>

                        @can('consultations.create')
                            @if($visit->consultations->isEmpty())
                                <a href="{{ route('consultations.create', ['visit_id' => $visit->id]) }}"
                                   class="rounded-md bg-[#0f4c81] px-3 py-1.5 text-xs font-medium text-white hover:bg-[#0b3f6d]">
                                    Consulter
                                </a>
                            @else
                                <a href="{{ route('consultations.show', $visit->consultations->first()) }}"
                                   class="rounded-md border border-purple-300 px-3 py-1.5 text-xs font-medium text-purple-700 hover:bg-purple-50">
                                    Voir consultation
                                </a>
                            @endif
                        @endcan

                        @can('invoices.create')
                            <a href="{{ route('cashier.invoices.create', ['visit_id' => $visit->id]) }}"
                               class="rounded-md border border-green-300 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-50">
                                Facturer
                            </a>
                        @endcan

                        @can('visits.manage')
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                        class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-100">
                                    Statut ▾
                                </button>
                                <div x-show="open" @click.outside="open = false"
                                     class="absolute right-0 mt-1 w-44 rounded-xl border border-slate-200 bg-white shadow-lg z-10 py-1">
                                    @foreach(App\Enums\VisitStatus::cases() as $s)
                                        @if(!in_array($s->value, ['closed', 'cancelled']))
                                            <button wire:click="updateStatus({{ $visit->id }}, '{{ $s->value }}')"
                                                    @click="open = false"
                                                    class="w-full text-left px-4 py-2 text-sm hover:bg-slate-50 {{ $s->value === $statusVal ? 'font-semibold text-[#0f4c81]' : 'text-slate-700' }}">
                                                {{ $s->label() }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <button wire:click="closeVisit({{ $visit->id }})"
                                    wire:confirm="Clôturer cette visite ?"
                                    class="rounded-md border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">
                                Clôturer
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center">
                <svg class="mx-auto w-10 h-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-sm text-slate-500">Aucune visite active pour aujourd'hui.</p>
            </div>
        @endforelse
    </div>
</div>
