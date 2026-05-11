<x-opticare-layout>
    <x-slot:pageTitle>{{ $product->name }}</x-slot:pageTitle>
    <div class="rounded-lg border border-slate-200 bg-white p-6 text-sm text-slate-700 shadow-sm">Stock: {{ $product->stock_quantity }}</div>
</x-opticare-layout>
