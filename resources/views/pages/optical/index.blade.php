<x-opticare-layout>
    <x-slot:pageTitle>Optique</x-slot:pageTitle>

    <div class="grid gap-6 md:grid-cols-3">
        <a href="{{ route('optical.orders.index') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm hover:border-[#0f4c81]">
            <h2 class="text-base font-semibold text-slate-900">Commandes lunettes</h2>
            <p class="mt-2 text-sm text-slate-500">Suivi des commandes optiques et livraisons.</p>
        </a>
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-base font-semibold text-slate-900">Montures</h2><p class="mt-2 text-sm text-slate-500">Gestion du stock prévue dans la phase optique.</p></div>
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-base font-semibold text-slate-900">Verres</h2><p class="mt-2 text-sm text-slate-500">Gestion du catalogue prévue dans la phase optique.</p></div>
    </div>
</x-opticare-layout>
