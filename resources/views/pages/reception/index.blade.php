<x-opticare-layout>
    <x-slot:pageTitle>Réception</x-slot:pageTitle>

    <div class="grid gap-6 lg:grid-cols-3">
        @can('visits.create')
            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">Ouvrir une visite</h2>
                <form method="POST" action="{{ route('reception.open-visit') }}" class="mt-4 space-y-4">
                    @csrf
                    <select name="patient_id" required class="w-full rounded-md border-slate-300 text-sm shadow-sm">
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->full_name }} · {{ $patient->patient_code }}</option>
                        @endforeach
                    </select>
                    <button class="w-full rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Ouvrir</button>
                    <a href="{{ route('patients.create') }}" class="block text-center text-sm font-medium text-[#0f4c81] hover:underline">Créer un nouveau patient</a>
                </form>
            </section>
        @endcan

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
            <h2 class="text-base font-semibold text-slate-900">File d'attente du jour</h2>
            <div class="mt-4 divide-y divide-slate-100">
                @forelse($visits as $visit)
                    <div class="flex flex-col gap-3 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-medium text-slate-900">{{ $visit->patient?->full_name }}</p>
                            <p class="text-sm text-slate-500">{{ $visit->visit_code }} · {{ $visit->opened_at?->format('H:i') }} · {{ $visit->status?->value ?? $visit->status }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @can('consultations.create')
                                <a href="{{ route('consultations.create', ['visit_id' => $visit->id]) }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Consultation</a>
                            @endcan
                            @can('visits.manage')
                                <form method="POST" action="{{ route('reception.close-visit', $visit) }}">@csrf @method('PATCH')
                                    <button class="rounded-md border border-red-200 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-50">Clôturer</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-slate-500">Aucune visite ouverte aujourd'hui.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-opticare-layout>
