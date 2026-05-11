<div>
    {{-- Header navigation --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2">
            <button wire:click="previousWeek" class="rounded-lg border border-slate-300 p-2 hover:bg-slate-50 text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button wire:click="goToday" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Aujourd'hui</button>
            <button wire:click="nextWeek" class="rounded-lg border border-slate-300 p-2 hover:bg-slate-50 text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <span class="text-sm font-semibold text-slate-700">
                {{ $weekStart->isoFormat('D MMM') }} – {{ $weekEnd->isoFormat('D MMM YYYY') }}
            </span>
        </div>

        <div class="flex items-center gap-3">
            <select wire:model.live="doctorFilter" class="rounded-lg border-slate-300 text-sm shadow-sm">
                <option value="">Tous les médecins</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </select>
            @can('appointments.create')
                <a href="{{ route('appointments.create') }}"
                   class="rounded-lg bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                    + Nouveau
                </a>
            @endcan
        </div>
    </div>

    {{-- Calendar grid --}}
    <div class="grid grid-cols-7 gap-1 overflow-x-auto">
        @foreach($days as $day)
            @php
                $isToday   = $day->isToday();
                $dayKey    = $day->toDateString();
                $dayAppts  = $appointments->get($dayKey, collect());
            @endphp
            <div class="min-w-0">
                {{-- Day header --}}
                <div class="mb-1 rounded-lg py-2 text-center {{ $isToday ? 'bg-[#0f4c81] text-white' : 'bg-slate-100 text-slate-600' }}">
                    <p class="text-xs font-medium uppercase">{{ $day->isoFormat('ddd') }}</p>
                    <p class="text-lg font-bold leading-tight">{{ $day->format('d') }}</p>
                </div>

                {{-- Appointments --}}
                <div class="space-y-1">
                    @forelse($dayAppts as $appt)
                        @php
                            $colors = [
                                'scheduled'      => 'bg-blue-100 border-blue-300 text-blue-800',
                                'confirmed'      => 'bg-green-100 border-green-300 text-green-800',
                                'waiting'        => 'bg-yellow-100 border-yellow-300 text-yellow-800',
                                'in_consultation'=> 'bg-purple-100 border-purple-300 text-purple-800',
                                'completed'      => 'bg-slate-100 border-slate-300 text-slate-600',
                                'cancelled'      => 'bg-red-100 border-red-300 text-red-700',
                                'missed'         => 'bg-orange-100 border-orange-300 text-orange-700',
                            ];
                            $color = $colors[$appt->status->value] ?? 'bg-slate-100 border-slate-300 text-slate-700';
                        @endphp
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                    class="w-full rounded border {{ $color }} px-1.5 py-1 text-left transition-all hover:shadow-sm">
                                <p class="truncate text-xs font-semibold">{{ $appt->patient?->last_name }}</p>
                                <p class="text-xs opacity-75">{{ substr($appt->appointment_time ?? '', 0, 5) }}</p>
                            </button>

                            {{-- Popup --}}
                            <div x-show="open" @click.outside="open = false"
                                 class="absolute left-0 top-full z-50 mt-1 w-60 rounded-xl border border-slate-200 bg-white shadow-xl p-4">
                                <div class="mb-3">
                                    <p class="font-semibold text-slate-900">{{ $appt->patient?->full_name }}</p>
                                    <p class="text-xs text-slate-500">{{ substr($appt->appointment_time ?? '', 0, 5) }} — {{ $appt->doctor?->name }}</p>
                                    @if($appt->reason)
                                        <p class="mt-1 text-xs text-slate-600 italic">{{ $appt->reason }}</p>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    <a href="{{ route('appointments.show', $appt) }}"
                                       class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-slate-200">
                                        Détails
                                    </a>
                                    @if($appt->status->value === 'scheduled')
                                        <button wire:click="confirm({{ $appt->id }})" @click="open = false"
                                                class="rounded-md bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700 hover:bg-green-200">
                                            Confirmer
                                        </button>
                                    @endif
                                    @if(!in_array($appt->status->value, ['cancelled', 'completed']))
                                        <button wire:click="cancel({{ $appt->id }})"
                                                wire:confirm="Annuler ce rendez-vous ?"
                                                @click="open = false"
                                                class="rounded-md bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700 hover:bg-red-200">
                                            Annuler
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="py-2 text-center text-xs text-slate-300">—</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    {{-- Today's list --}}
    @php $todayAppts = $appointments->get(today()->toDateString(), collect()); @endphp
    @if($todayAppts->isNotEmpty())
        <div class="mt-6">
            <h3 class="mb-3 text-sm font-semibold text-slate-700">Liste du jour</h3>
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Heure</th>
                            <th class="px-4 py-3 text-left">Patient</th>
                            <th class="px-4 py-3 text-left">Médecin</th>
                            <th class="px-4 py-3 text-left">Motif</th>
                            <th class="px-4 py-3 text-left">Statut</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($todayAppts as $appt)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium">{{ substr($appt->appointment_time ?? '', 0, 5) }}</td>
                                <td class="px-4 py-3">{{ $appt->patient?->full_name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $appt->doctor?->name }}</td>
                                <td class="px-4 py-3 text-slate-600 max-w-xs truncate">{{ $appt->reason }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                        {{ match($appt->status->value) {
                                            'confirmed'   => 'bg-green-100 text-green-700',
                                            'scheduled'   => 'bg-blue-100 text-blue-700',
                                            'waiting'     => 'bg-yellow-100 text-yellow-700',
                                            'completed'   => 'bg-slate-100 text-slate-600',
                                            'cancelled'   => 'bg-red-100 text-red-700',
                                            default       => 'bg-slate-100 text-slate-700'
                                        } }}">
                                        {{ $appt->status->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('appointments.show', $appt) }}"
                                       class="font-medium text-[#0f4c81] hover:underline text-xs">Voir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
