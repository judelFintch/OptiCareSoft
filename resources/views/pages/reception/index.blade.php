<x-opticare-layout>
    <x-slot:pageTitle>Réception</x-slot:pageTitle>

    <div class="space-y-6">
        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">File de réception</h2>
                    <p class="mt-1 text-sm text-slate-500">Ouverture et suivi des visites du jour.</p>
                </div>
                @can('visits.create')
                    <a href="{{ route('patients.index') }}" class="inline-flex items-center justify-center rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#0b3f6d]">
                        Sélectionner un patient
                    </a>
                @endcan
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Aucune visite à afficher pour le moment.</p>
        </section>
    </div>
</x-opticare-layout>
