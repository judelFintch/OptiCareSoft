<x-opticare-layout>
    <x-slot:pageTitle>Commandes optiques</x-slot:pageTitle>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('optical.orders.create') }}" class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Nouvelle commande</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500"><tr><th class="px-4 py-3">Commande</th><th class="px-4 py-3">Patient</th><th class="px-4 py-3">Statut</th><th class="px-4 py-3 text-right">Actions</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                    <tr><td class="px-4 py-3 font-medium">{{ $order->order_number }}</td><td class="px-4 py-3">{{ $order->patient?->full_name }}</td><td class="px-4 py-3">{{ $order->status?->value ?? $order->status }}</td><td class="px-4 py-3 text-right"><a href="{{ route('optical.orders.show', $order) }}" class="font-medium text-[#0f4c81] hover:underline">Ouvrir</a></td></tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">Aucune commande.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-opticare-layout>
