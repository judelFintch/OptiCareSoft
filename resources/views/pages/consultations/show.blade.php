<x-opticare-layout>
    <x-slot:pageTitle>{{ $consultation->consultation_code }}</x-slot:pageTitle>

    <div class="space-y-6">
        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">{{ $consultation->patient?->full_name }}</h2>
                    <p class="mt-2 text-sm text-slate-500">Médecin: {{ $consultation->doctor?->name }} · Statut: {{ $consultation->status?->value ?? $consultation->status }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @can('optical_prescriptions.create')
                        <a href="{{ route('consultations.optical-prescriptions.create', $consultation) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Prescription optique</a>
                    @endcan
                    @can('update', $consultation)
                        <a href="{{ route('consultations.edit', $consultation) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Modifier</a>
                    @endcan
                    @can('sign', $consultation)
                        <form method="POST" action="{{ route('consultations.sign', $consultation) }}">@csrf @method('PATCH')
                            <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Signer</button>
                        </form>
                    @endcan
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Résumé clinique</h3>
            <dl class="mt-4 grid gap-4 text-sm md:grid-cols-2">
                <div><dt class="font-medium text-slate-500">Motif</dt><dd class="mt-1 text-slate-900">{{ $consultation->chief_complaint ?: '—' }}</dd></div>
                <div><dt class="font-medium text-slate-500">Diagnostic</dt><dd class="mt-1 text-slate-900">{{ $consultation->primary_diagnosis ?: '—' }}</dd></div>
                <div><dt class="font-medium text-slate-500">Constats</dt><dd class="mt-1 text-slate-900">{{ $consultation->clinical_findings ?: '—' }}</dd></div>
                <div><dt class="font-medium text-slate-500">Plan</dt><dd class="mt-1 text-slate-900">{{ $consultation->treatment_plan ?: '—' }}</dd></div>
            </dl>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Prescriptions optiques</h3>
            <div class="mt-4 divide-y divide-slate-100">
                @forelse($consultation->opticalPrescriptions as $prescription)
                    <div class="flex flex-col gap-2 py-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-medium text-slate-900">{{ $prescription->prescription_number }}</p>
                            <p class="text-sm text-slate-500">Verres: {{ $prescription->lens_type?->label() ?? '—' }} · Valide jusqu'au {{ $prescription->valid_until?->format('d/m/Y') ?: '—' }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('optical-prescriptions.show', $prescription) }}" class="text-sm font-medium text-[#0f4c81] hover:underline">Ouvrir</a>
                            <a href="{{ route('optical-prescriptions.pdf', $prescription) }}" target="_blank" class="text-sm font-medium text-[#0f4c81] hover:underline">PDF</a>
                        </div>
                    </div>
                @empty
                    <p class="py-3 text-sm text-slate-500">Aucune prescription optique.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-opticare-layout>
