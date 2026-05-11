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
    </div>
</x-opticare-layout>
