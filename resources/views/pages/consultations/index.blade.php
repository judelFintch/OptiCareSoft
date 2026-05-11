<x-opticare-layout>
    <x-slot:pageTitle>Consultations</x-slot:pageTitle>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">Patient</th>
                    <th class="px-4 py-3">Médecin</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($consultations as $consultation)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $consultation->consultation_code }}</td>
                        <td class="px-4 py-3">{{ $consultation->patient?->full_name }}</td>
                        <td class="px-4 py-3">{{ $consultation->doctor?->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $consultation->status?->value ?? $consultation->status }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('consultations.show', $consultation) }}" class="font-medium text-[#0f4c81] hover:underline">Ouvrir</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Aucune consultation trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $consultations->links() }}</div>
</x-opticare-layout>
