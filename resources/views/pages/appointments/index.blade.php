<x-opticare-layout>
    <x-slot:pageTitle>Rendez-vous</x-slot:pageTitle>

    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex gap-2">
                <input type="date" name="date" value="{{ request('date') }}" class="rounded-md border-slate-300 text-sm shadow-sm">
                <button class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Filtrer</button>
            </form>
            @can('appointments.create')
                <a href="{{ route('appointments.create') }}" class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Nouveau rendez-vous</a>
            @endcan
        </div>

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Médecin</th>
                        <th class="px-4 py-3">Motif</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($appointments as $appointment)
                        <tr>
                            <td class="px-4 py-3">{{ $appointment->appointment_date?->format('d/m/Y') }} {{ $appointment->appointment_time }}</td>
                            <td class="px-4 py-3">{{ $appointment->patient?->full_name }}</td>
                            <td class="px-4 py-3">{{ $appointment->doctor?->name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $appointment->reason }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $appointment->status?->value ?? $appointment->status }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('appointments.show', $appointment) }}" class="font-medium text-[#0f4c81] hover:underline">Ouvrir</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">Aucun rendez-vous trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $appointments->links() }}
    </div>
</x-opticare-layout>
