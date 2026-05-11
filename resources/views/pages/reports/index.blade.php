<x-opticare-layout>
    <x-slot:pageTitle>Rapports</x-slot:pageTitle>

    <div class="grid gap-6 md:grid-cols-3">
        <a href="{{ route('reports.daily') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm hover:border-[#0f4c81]">
            <h2 class="text-base font-semibold text-slate-900">Rapport journalier</h2>
            <p class="mt-2 text-sm text-slate-500">Visites, consultations, factures et encaissements.</p>
        </a>
        <a href="{{ route('reports.financial') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm hover:border-[#0f4c81]">
            <h2 class="text-base font-semibold text-slate-900">Rapport financier</h2>
            <p class="mt-2 text-sm text-slate-500">Recettes, dettes et méthodes de paiement.</p>
        </a>
        <a href="{{ route('reports.patients') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm hover:border-[#0f4c81]">
            <h2 class="text-base font-semibold text-slate-900">Rapport patients</h2>
            <p class="mt-2 text-sm text-slate-500">Nouveaux patients et fréquentation.</p>
        </a>
        <a href="{{ route('reports.consultations') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm hover:border-[#0f4c81]">
            <h2 class="text-base font-semibold text-slate-900">Rapport consultations</h2>
            <p class="mt-2 text-sm text-slate-500">Diagnostics fréquents et performance par médecin.</p>
        </a>
        <a href="{{ route('reports.debts') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm hover:border-red-300">
            <h2 class="text-base font-semibold text-slate-900">Dettes patients</h2>
            <p class="mt-2 text-sm text-slate-500">Factures impayées et soldes restants.</p>
        </a>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach([
            'Visites ouvertes' => $daily['visits_opened'] ?? 0,
            'Consultations' => $daily['consultations'] ?? 0,
            'Nouveaux patients' => $daily['new_patients'] ?? 0,
            'Recettes' => number_format((float) ($daily['revenue']['total'] ?? 0), 2),
        ] as $label => $value)
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">{{ $label }}</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $value }}</p>
            </div>
        @endforeach
    </div>
</x-opticare-layout>
