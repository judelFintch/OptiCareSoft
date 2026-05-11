<x-opticare-layout>
    <x-slot:pageTitle>Patients</x-slot:pageTitle>

    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex w-full gap-2 sm:max-w-md">
                <input name="search" value="{{ request('search') }}" placeholder="Rechercher un patient" class="w-full rounded-md border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
                <button class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Filtrer</button>
            </form>
            @can('patients.create')
                <a href="{{ route('patients.create') }}" class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Nouveau patient</a>
            @endcan
        </div>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Code</th>
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Téléphone</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($patients as $patient)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $patient->patient_code }}</td>
                            <td class="px-4 py-3">{{ $patient->full_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $patient->phone ?: '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $patient->status?->value ?? $patient->status }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('patients.show', $patient) }}" class="font-medium text-[#0f4c81] hover:underline">Ouvrir</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Aucun patient trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $patients->links() }}
    </div>
</x-opticare-layout>
