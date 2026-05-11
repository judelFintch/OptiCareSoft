<x-opticare-layout>
    <x-slot:pageTitle>Rapport journalier</x-slot:pageTitle>

    <form method="GET" class="mb-6 flex gap-2">
        <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="rounded-md border-slate-300 text-sm shadow-sm">
        <button class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Afficher</button>
    </form>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach([
            'Visites ouvertes' => $report['visits_opened'],
            'Visites clôturées' => $report['visits_closed'],
            'Consultations' => $report['consultations'],
            'Factures émises' => $report['invoices_issued'],
            'Rendez-vous' => $report['appointments_total'],
            'Rendez-vous réalisés' => $report['appointments_done'],
            'Nouveaux patients' => $report['new_patients'],
            'Recettes' => number_format((float) $report['revenue']['total'], 2),
        ] as $label => $value)
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">{{ $label }}</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $value }}</p>
            </div>
        @endforeach
    </div>
</x-opticare-layout>
