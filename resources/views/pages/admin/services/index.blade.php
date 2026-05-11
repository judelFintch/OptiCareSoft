<x-opticare-layout>
    <x-slot:pageTitle>Services & Tarifs</x-slot:pageTitle>

    <div class="mb-6 flex items-center justify-between">
        <div class="text-sm text-slate-500">{{ $services->total() }} service(s)</div>
        <a href="{{ route('admin.services.create') }}"
           class="rounded-lg bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">
            + Nouveau service
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">Catégorie</th>
                    <th class="px-4 py-3">Prix</th>
                    <th class="px-4 py-3">Devise</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($services as $service)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $service->name }}</td>
                        <td class="px-4 py-3 font-mono text-slate-600">{{ $service->code }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">
                                {{ $service->category_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-800">{{ number_format($service->default_price, 2) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $service->currency?->code }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $service->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $service->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.services.edit', $service) }}"
                               class="font-medium text-[#0f4c81] hover:underline">Modifier</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-sm text-slate-400">Aucun service enregistré.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $services->links() }}</div>
</x-opticare-layout>
