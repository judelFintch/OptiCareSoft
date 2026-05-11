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
                    @can('medical_prescriptions.create')
                        <a href="{{ route('medical-prescriptions.edit', $prescription) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Modifier</a>
                    @endcan
                    <a href="{{ route('medical-prescriptions.pdf', $prescription) }}" target="_blank" class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">PDF</a>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Médicaments</h3>
            <div class="mt-4 divide-y divide-slate-100">
                @foreach($prescription->items as $item)
                    <div class="py-4">
                        <p class="font-medium text-slate-900">{{ $item->drug_name }} <span class="text-sm font-normal text-slate-500">{{ $item->dosage }}</span></p>
                        <p class="mt-1 text-sm text-slate-600">{{ $item->form }} · {{ $item->route }} · {{ $item->frequency }} · {{ $item->duration }}</p>
                        @if($item->instructions)
                            <p class="mt-1 text-sm text-slate-500">{{ $item->instructions }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-opticare-layout>
