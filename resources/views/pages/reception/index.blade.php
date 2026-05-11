<x-opticare-layout>
    <x-slot:pageTitle>Réception</x-slot:pageTitle>

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Panneau gauche : ouvrir visite --}}
        <div class="space-y-4">
            @can('visits.create')
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 font-semibold text-slate-800">Ouvrir une visite</h2>
                    <form method="POST" action="{{ route('reception.open-visit') }}" class="space-y-4"
                          x-data="{ search: '', showList: false }">
                        @csrf
                        <div class="relative">
                            <input type="text" x-model="search" @input="showList = search.length > 1"
                                   placeholder="Nom, code patient…"
                                   class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            <div x-show="showList" @click.outside="showList = false"
                                 class="absolute z-10 left-0 right-0 mt-1 max-h-56 overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-lg">
                                @foreach($patients as $patient)
                                    <button type="button"
                                            x-show="search.length < 2 || '{{ strtolower($patient->full_name) }}'.includes(search.toLowerCase()) || '{{ $patient->patient_code }}'.includes(search)"
                                            @click="$refs.patientId.value='{{ $patient->id }}'; search='{{ $patient->full_name }}'; showList=false"
                                            class="w-full px-4 py-2.5 text-left text-sm hover:bg-slate-50">
                                        <span class="font-medium text-slate-900">{{ $patient->full_name }}</span>
                                        <span class="ml-2 text-xs text-slate-500">{{ $patient->patient_code }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="patient_id" x-ref="patientId" required>
                        <button type="submit"
                                class="w-full rounded-lg bg-[#0f4c81] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                            Ouvrir la visite
                        </button>
                        <a href="{{ route('patients.create') }}"
                           class="block text-center text-sm font-medium text-[#0f4c81] hover:underline">
                            + Nouveau patient
                        </a>
                    </form>
                </div>
            @endcan

            {{-- Statistiques du jour --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Aujourd'hui</h2>
                <div class="space-y-3">
                    @php
                        $todayVisits = $visits->count();
                        $inProgress  = $visits->filter(fn($v) => $v->status->value === 'in_progress')->count();
                        $pending     = $visits->filter(fn($v) => $v->status->value === 'pending')->count();
                    @endphp
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-600">Visites ouvertes</span>
                        <span class="font-bold text-[#0f4c81]">{{ $todayVisits }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-600">En consultation</span>
                        <span class="font-bold text-purple-600">{{ $inProgress }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-600">En attente paiement</span>
                        <span class="font-bold text-yellow-600">{{ $pending }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- File d'attente Livewire --}}
        <div class="lg:col-span-2">
            <livewire:reception.waiting-queue />
        </div>
    </div>
</x-opticare-layout>
