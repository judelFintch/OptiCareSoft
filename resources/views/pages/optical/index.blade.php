<x-opticare-layout>
    <x-slot:pageTitle>Optique</x-slot:pageTitle>

    @php
        $pendingOrders  = App\Models\OpticalOrder::whereIn('status', ['pending','ordered','in_production'])->count();
        $readyOrders    = App\Models\OpticalOrder::where('status', 'ready')->count();
        $lowFrames      = App\Models\Frame::where('is_active', true)->whereColumn('stock_quantity', '<=', 'reorder_level')->count();
        $unpaidOrders   = App\Models\OpticalOrder::where('remaining_amount', '>', 0)->whereNotIn('status', ['cancelled'])->count();
    @endphp

    <div class="grid gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase text-slate-500 tracking-wide">Commandes en cours</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $pendingOrders }}</p>
        </div>
        <div class="rounded-xl border {{ $readyOrders > 0 ? 'border-green-200 bg-green-50' : 'border-slate-200 bg-white' }} p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide {{ $readyOrders > 0 ? 'text-green-600' : 'text-slate-500' }}">Prêtes à livrer</p>
            <p class="mt-2 text-3xl font-bold {{ $readyOrders > 0 ? 'text-green-700' : 'text-slate-900' }}">{{ $readyOrders }}</p>
        </div>
        <div class="rounded-xl border {{ $lowFrames > 0 ? 'border-red-200 bg-red-50' : 'border-slate-200 bg-white' }} p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide {{ $lowFrames > 0 ? 'text-red-600' : 'text-slate-500' }}">Stock faible</p>
            <p class="mt-2 text-3xl font-bold {{ $lowFrames > 0 ? 'text-red-700' : 'text-slate-900' }}">{{ $lowFrames }}</p>
        </div>
        <div class="rounded-xl border {{ $unpaidOrders > 0 ? 'border-yellow-200 bg-yellow-50' : 'border-slate-200 bg-white' }} p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide {{ $unpaidOrders > 0 ? 'text-yellow-600' : 'text-slate-500' }}">Soldes impayés</p>
            <p class="mt-2 text-3xl font-bold {{ $unpaidOrders > 0 ? 'text-yellow-700' : 'text-slate-900' }}">{{ $unpaidOrders }}</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('optical.orders.index') }}"
           class="group rounded-xl border border-[#0f4c81]/30 bg-[#0f4c81]/5 p-6 shadow-sm hover:border-[#0f4c81] hover:bg-[#0f4c81]/10 transition-all">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-lg bg-[#0f4c81]/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#0f4c81]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h2 class="font-semibold text-slate-900">Commandes lunettes</h2>
            </div>
            <p class="text-sm text-slate-500">Suivi, fabrication et livraisons.</p>
        </a>

        @can('stock.view')
            <a href="{{ route('optical.stock.index') }}"
               class="group rounded-xl border {{ $lowFrames > 0 ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-white' }} p-6 shadow-sm hover:border-[#0f4c81] transition-all">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h2 class="font-semibold text-slate-900">Stock montures & verres</h2>
                    @if($lowFrames > 0)
                        <span class="ml-auto rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white">{{ $lowFrames }}</span>
                    @endif
                </div>
                <p class="text-sm text-slate-500">Inventaire et mouvements de stock.</p>
            </a>
        @endcan

        @can('optical_orders.manage')
            <a href="{{ route('optical.orders.create') }}"
               class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm hover:border-[#0f4c81] transition-all">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <h2 class="font-semibold text-slate-900">Nouvelle commande</h2>
                </div>
                <p class="text-sm text-slate-500">Créer une commande pour un patient.</p>
            </a>
        @endcan
    </div>
</x-opticare-layout>
