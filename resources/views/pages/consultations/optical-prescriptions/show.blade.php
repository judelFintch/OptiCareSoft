<x-opticare-layout>
    <x-slot:pageTitle>{{ $prescription->prescription_number }}</x-slot:pageTitle>

    <div class="space-y-6">
        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-[#0f4c81]">{{ $prescription->prescription_number }}</p>
                    <h2 class="mt-1 text-xl font-semibold text-slate-900">{{ $prescription->patient?->full_name }}</h2>
                    <p class="mt-2 text-sm text-slate-500">Médecin: {{ $prescription->doctor?->name }} · Valide jusqu'au {{ $prescription->valid_until?->format('d/m/Y') ?: '—' }}</p>
                </div>
                <div class="flex gap-2">
                    @can('optical_prescriptions.create')
                        <a href="{{ route('optical-prescriptions.edit', $prescription) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Modifier</a>
                    @endcan
                    <a href="{{ route('optical-prescriptions.pdf', $prescription) }}" target="_blank" class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">PDF</a>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Correction</h3>
            <div class="mt-4 overflow-hidden rounded-lg border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                        <tr><th class="px-4 py-3">Œil</th><th class="px-4 py-3">Sphère</th><th class="px-4 py-3">Cylindre</th><th class="px-4 py-3">Axe</th><th class="px-4 py-3">Addition</th><th class="px-4 py-3">DP</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr><td class="px-4 py-3 font-medium">OD</td><td class="px-4 py-3">{{ $prescription->right_sphere ?? '—' }}</td><td class="px-4 py-3">{{ $prescription->right_cylinder ?? '—' }}</td><td class="px-4 py-3">{{ $prescription->right_axis ?? '—' }}</td><td class="px-4 py-3">{{ $prescription->right_addition ?? '—' }}</td><td class="px-4 py-3">{{ $prescription->pd_right ?? '—' }}</td></tr>
                        <tr><td class="px-4 py-3 font-medium">OG</td><td class="px-4 py-3">{{ $prescription->left_sphere ?? '—' }}</td><td class="px-4 py-3">{{ $prescription->left_cylinder ?? '—' }}</td><td class="px-4 py-3">{{ $prescription->left_axis ?? '—' }}</td><td class="px-4 py-3">{{ $prescription->left_addition ?? '—' }}</td><td class="px-4 py-3">{{ $prescription->pd_left ?? '—' }}</td></tr>
                    </tbody>
                </table>
            </div>
            <p class="mt-4 text-sm text-slate-600">Type: {{ $prescription->lens_type?->label() ?? '—' }} · Usage: {{ $prescription->usage ?: '—' }}</p>
            <p class="mt-2 text-sm text-slate-600">{{ $prescription->remarks ?: 'Aucune remarque.' }}</p>
        </section>
    </div>
</x-opticare-layout>
