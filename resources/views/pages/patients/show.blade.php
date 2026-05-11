<x-opticare-layout>
    <x-slot:pageTitle>{{ $patient->full_name }}</x-slot:pageTitle>

    <div class="space-y-6">
        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-[#0f4c81]">{{ $patient->patient_code }}</p>
                    <h2 class="mt-1 text-xl font-semibold text-slate-900">{{ $patient->full_name }}</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $patient->phone ?: 'Téléphone non renseigné' }} · {{ $patient->age ? $patient->age . ' ans' : 'Age non renseigné' }}</p>
                </div>
                <div class="flex gap-2">
                    @can('patients.edit')
                        <a href="{{ route('patients.edit', $patient) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Modifier</a>
                    @endcan
                    @can('visits.create')
                        <form method="POST" action="{{ route('reception.open-visit') }}">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                            <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Ouvrir visite</button>
                        </form>
                    @endcan
                </div>
            </div>
        </section>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                <h3 class="text-base font-semibold text-slate-900">Informations médicales</h3>
                <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                    <div><dt class="font-medium text-slate-500">Antécédents médicaux</dt><dd class="mt-1 text-slate-900">{{ $patient->medical_history ?: '—' }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Antécédents ophtalmologiques</dt><dd class="mt-1 text-slate-900">{{ $patient->ophthalmic_history ?: '—' }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Allergies</dt><dd class="mt-1 text-slate-900">{{ $patient->allergies ?: '—' }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Contact urgence</dt><dd class="mt-1 text-slate-900">{{ $patient->emergency_contact_name ?: '—' }} {{ $patient->emergency_contact_phone }}</dd></div>
                </dl>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900">Résumé financier</h3>
                <p class="mt-4 text-2xl font-semibold text-slate-900">{{ number_format((float) $patient->total_debt, 2) }}</p>
                <p class="text-sm text-slate-500">Dette actuelle</p>
            </section>
        </div>
    </div>
</x-opticare-layout>
