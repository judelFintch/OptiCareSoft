<x-opticare-layout>
    <x-slot:pageTitle>Produits pharmacie</x-slot:pageTitle>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('pharmacy.products.create') }}" class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Nouveau produit</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500"><tr><th class="px-4 py-3">Nom</th><th class="px-4 py-3">Stock</th><th class="px-4 py-3">Prix</th><th class="px-4 py-3 text-right">Actions</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                    <tr><td class="px-4 py-3 font-medium">{{ $product->name }}</td><td class="px-4 py-3">{{ $product->stock_quantity }}</td><td class="px-4 py-3">{{ number_format((float) $product->selling_price, 2) }}</td><td class="px-4 py-3 text-right"><a href="{{ route('pharmacy.products.show', $product) }}" class="font-medium text-[#0f4c81] hover:underline">Ouvrir</a></td></tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">Aucun produit.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-opticare-layout>
