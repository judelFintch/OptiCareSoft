<x-opticare-layout>
    <x-slot:pageTitle>{{ $order->order_number ?? 'Commande optique' }}</x-slot:pageTitle>
    <div class="rounded-lg border border-slate-200 bg-white p-6 text-sm text-slate-700 shadow-sm">Patient: {{ $order->patient?->full_name ?? '—' }}</div>
</x-opticare-layout>
