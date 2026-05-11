<x-opticare-layout>
    <x-slot:pageTitle>Pharmacie</x-slot:pageTitle>

    <div class="grid gap-6 md:grid-cols-3">
        <a href="{{ route('pharmacy.products.index') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm hover:border-[#0f4c81]">
            <h2 class="text-base font-semibold text-slate-900">Produits</h2>
            <p class="mt-2 text-sm text-slate-500">Catalogue et stock des produits ophtalmologiques.</p>
        </a>
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-base font-semibold text-slate-900">Stock faible</h2><p class="mt-2 text-sm text-slate-500">Alertes prévues dans la phase pharmacie.</p></div>
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-base font-semibold text-slate-900">Expirations</h2><p class="mt-2 text-sm text-slate-500">Suivi prévu dans la phase pharmacie.</p></div>
    </div>
</x-opticare-layout>
