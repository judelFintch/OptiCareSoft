<x-opticare-layout>
    <x-slot:pageTitle>Rendez-vous</x-slot:pageTitle>

    {{-- Tabs : Calendrier / Liste --}}
    <div x-data="{ tab: 'calendar' }" class="space-y-4">

        <div class="flex gap-1 rounded-xl border border-slate-200 bg-white p-1 w-fit shadow-sm">
            <button @click="tab = 'calendar'"
                    :class="tab === 'calendar' ? 'bg-[#0f4c81] text-white shadow' : 'text-slate-600 hover:bg-slate-100'"
                    class="rounded-lg px-4 py-2 text-sm font-medium transition-all">
                Calendrier
            </button>
            <button @click="tab = 'list'"
                    :class="tab === 'list' ? 'bg-[#0f4c81] text-white shadow' : 'text-slate-600 hover:bg-slate-100'"
                    class="rounded-lg px-4 py-2 text-sm font-medium transition-all">
                Liste
            </button>
        </div>

        {{-- Calendrier Livewire --}}
        <div x-show="tab === 'calendar'" x-transition>
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <livewire:appointments.appointment-calendar />
            </div>
        </div>

        {{-- Liste classique --}}
        <div x-show="tab === 'list'" x-transition>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                <form method="GET" class="flex gap-2">
                    <input type="date" name="date" value="{{ request('date') }}"
                           class="rounded-lg border-slate-300 text-sm shadow-sm">
                    <button class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Filtrer
                    </button>
                </form>
                @can('appointments.create')
                    <a href="{{ route('appointments.create') }}"
                       class="rounded-lg bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                        + Nouveau rendez-vous
                    </a>
                @endcan
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Date / Heure</th>
                            <th class="px-4 py-3">Patient</th>
                            <th class="px-4 py-3">Médecin</th>
                            <th class="px-4 py-3">Motif</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($appointments as $appointment)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium text-slate-800">
                                    {{ $appointment->appointment_date?->format('d/m/Y') }}
                                    <span class="ml-1 text-slate-500">{{ optional($appointment->appointment_time)->format('H:i') }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $appointment->patient?->full_name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $appointment->doctor?->name }}</td>
                                <td class="px-4 py-3 text-slate-600 max-w-xs truncate">{{ $appointment->reason }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                        {{ match($appointment->status?->value) {
                                            'confirmed'   => 'bg-green-100 text-green-700',
                                            'scheduled'   => 'bg-blue-100 text-blue-700',
                                            'cancelled'   => 'bg-red-100 text-red-700',
                                            'completed'   => 'bg-slate-100 text-slate-600',
                                            default       => 'bg-slate-100 text-slate-700'
                                        } }}">
                                        {{ $appointment->status?->label() ?? $appointment->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('appointments.show', $appointment) }}"
                                       class="font-medium text-[#0f4c81] hover:underline">Ouvrir</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-slate-400">Aucun rendez-vous trouvé.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $appointments->links() }}</div>
        </div>
    </div>
</x-opticare-layout>
