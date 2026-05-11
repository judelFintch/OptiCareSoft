<x-opticare-layout>
    <x-slot:pageTitle>Pharmacie</x-slot:pageTitle>

    @php
        $lowStockCount   = App\Models\PharmacyProduct::where('is_active', true)->whereColumn('stock_quantity', '<=', 'reorder_level')->count();
        $expiringCount   = App\Models\PharmacyProduct::where('is_active', true)->whereNotNull('expiry_date')->where('expiry_date', '<=', now()->addDays(30))->count();
        $todaySales      = App\Models\PharmacySale::whereDate('created_at', today())->count();
        $todayRevenue    = App\Models\PharmacySale::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total_amount');
    @endphp

    {{-- Stats --}}
    <div class="grid gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase text-slate-500 tracking-wide">Ventes du jour</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $todaySales }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase text-slate-500 tracking-wide">Recettes du jour</p>
            <p class="mt-2 text-3xl font-bold text-[#0f4c81]">{{ number_format($todayRevenue, 0) }}</p>
        </div>
        <div class="rounded-xl border {{ $lowStockCount > 0 ? 'border-red-200 bg-red-50' : 'border-slate-200 bg-white' }} p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide {{ $lowStockCount > 0 ? 'text-red-600' : 'text-slate-500' }}">Stock faible</p>
            <p class="mt-2 text-3xl font-bold {{ $lowStockCount > 0 ? 'text-red-700' : 'text-slate-900' }}">{{ $lowStockCount }}</p>
        </div>
        <div class="rounded-xl border {{ $expiringCount > 0 ? 'border-orange-200 bg-orange-50' : 'border-slate-200 bg-white' }} p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide {{ $expiringCount > 0 ? 'text-orange-600' : 'text-slate-500' }}">Expirations ≤ 30j</p>
            <p class="mt-2 text-3xl font-bold {{ $expiringCount > 0 ? 'text-orange-700' : 'text-slate-900' }}">{{ $expiringCount }}</p>
        </div>
    </div>

    {{-- Modules --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @can('pharmacy.manage')
            <a href="{{ route('pharmacy.sales.create') }}"
               class="group rounded-xl border border-[#0f4c81]/30 bg-[#0f4c81]/5 p-6 shadow-sm hover:border-[#0f4c81] hover:bg-[#0f4c81]/10 transition-all">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-lg bg-[#0f4c81]/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#0f4c81]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <h2 class="font-semibold text-slate-900">Nouvelle vente</h2>
                </div>
                <p class="text-sm text-slate-500">Enregistrer une vente de produits.</p>
            </a>
        @endcan

        <a href="{{ route('pharmacy.sales.index') }}"
           class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm hover:border-[#0f4c81] transition-all">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h2 class="font-semibold text-slate-900">Historique ventes</h2>
            </div>
            <p class="text-sm text-slate-500">Consulter toutes les ventes.</p>
        </a>

        <a href="{{ route('pharmacy.products.index') }}"
           class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm hover:border-[#0f4c81] transition-all">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <h2 class="font-semibold text-slate-900">Catalogue produits</h2>
            </div>
            <p class="text-sm text-slate-500">Gérer les produits ophtalmologiques.</p>
        </a>

        @can('stock.view')
            <a href="{{ route('pharmacy.stock.index') }}"
               class="group rounded-xl border {{ $lowStockCount > 0 ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-white' }} p-6 shadow-sm hover:border-red-400 transition-all">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h2 class="font-semibold text-slate-900">Gestion du stock</h2>
                    @if($lowStockCount > 0)
                        <span class="ml-auto rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white">{{ $lowStockCount }}</span>
                    @endif
                </div>
                <p class="text-sm text-slate-500">Mouvements, alertes et inventaire.</p>
            </a>
        @endcan
    </div>
</x-opticare-layout>
