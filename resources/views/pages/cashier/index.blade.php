<x-opticare-layout>
    <x-slot:pageTitle>Caisse</x-slot:pageTitle>

    <div class="grid gap-6 md:grid-cols-3">
        <a href="{{ route('cashier.invoices.index') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm hover:border-[#0f4c81]">
            <h2 class="text-base font-semibold text-slate-900">Factures</h2>
            <p class="mt-2 text-sm text-slate-500">Créer, suivre et encaisser les factures.</p>
        </a>
        <a href="{{ route('reports.daily') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm hover:border-[#0f4c81]">
            <h2 class="text-base font-semibold text-slate-900">Rapport caisse</h2>
            <p class="mt-2 text-sm text-slate-500">Consulter les recettes journalières.</p>
        </a>
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-base font-semibold text-slate-900">Paiements</h2>
            <p class="mt-2 text-sm text-slate-500">Les paiements sont saisis depuis une facture.</p>
        </div>
    </div>
</x-opticare-layout>
