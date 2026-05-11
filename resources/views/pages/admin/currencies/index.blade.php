<x-opticare-layout>
    <x-slot:pageTitle>Devises</x-slot:pageTitle>

    <div class="mb-6 flex items-center justify-between">
        <div class="text-sm text-slate-500">{{ $currencies->total() }} devise(s)</div>
        <a href="{{ route('admin.currencies.create') }}"
           class="rounded-lg bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">
            + Nouvelle devise
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">Symbole</th>
                    <th class="px-4 py-3">Taux</th>
                    <th class="px-4 py-3">Défaut</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($currencies as $currency)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $currency->name }}</td>
                        <td class="px-4 py-3 font-mono font-semibold text-slate-700">{{ $currency->code }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-600">{{ $currency->symbol }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $currency->exchange_rate }}</td>
                        <td class="px-4 py-3">
                            @if($currency->is_default)
                                <span class="rounded-full bg-[#0f4c81]/10 px-2 py-0.5 text-xs font-medium text-[#0f4c81]">Défaut</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $currency->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $currency->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.currencies.edit', $currency) }}"
                               class="font-medium text-[#0f4c81] hover:underline">Modifier</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-sm text-slate-400">Aucune devise.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $currencies->links() }}</div>
</x-opticare-layout>
