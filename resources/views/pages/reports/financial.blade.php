<x-opticare-layout>
    <x-slot:pageTitle>Rapport financier</x-slot:pageTitle>

    {{-- Filtres --}}
    <div class="mb-6 flex flex-wrap items-end gap-3">
        <form method="GET" class="flex flex-wrap gap-2">
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Du</label>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
                       class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Au</label>
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
                       class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
            </div>
            <div class="flex items-end">
                <button class="rounded-lg bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                    Afficher
                </button>
            </div>
        </form>
        @can('reports.export')
            <a href="{{ route('reports.export.financial') }}?from={{ $from->format('Y-m-d') }}&to={{ $to->format('Y-m-d') }}"
               class="flex items-center gap-1.5 rounded-lg border border-green-300 bg-green-50 px-4 py-2 text-sm font-medium text-green-700 hover:bg-green-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Excel
            </a>
        @endcan
    </div>

    {{-- KPIs --}}
    <div class="grid gap-4 mb-6 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase text-slate-500 tracking-wide">Total facturé</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format((float) $report['total_invoiced'], 0) }}</p>
        </div>
        <div class="rounded-xl border border-green-200 bg-green-50 p-5 shadow-sm">
            <p class="text-xs font-medium uppercase text-green-600 tracking-wide">Encaissé</p>
            <p class="mt-2 text-3xl font-bold text-green-700">{{ number_format((float) $report['total_collected'], 0) }}</p>
        </div>
        <div class="rounded-xl border border-red-200 bg-red-50 p-5 shadow-sm">
            <p class="text-xs font-medium uppercase text-red-600 tracking-wide">Dettes en cours</p>
            <p class="mt-2 text-3xl font-bold text-red-700">{{ number_format((float) $report['total_debt'], 0) }}</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2 mb-6">
        {{-- Graphique tendance mensuelle --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4">Tendance des encaissements</h3>
            <div id="chart-trend" class="h-64"></div>
        </div>

        {{-- Graphique par méthode --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4">Par méthode de paiement</h3>
            <div id="chart-methods" class="h-64"></div>
        </div>
    </div>

    {{-- Tableau par type de facturation --}}
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 px-5 py-3">
                <h3 class="font-semibold text-slate-800">Par type de facture</h3>
            </div>
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-right">Montant</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($report['by_type'] as $type => $amount)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-800 capitalize">{{ str_replace('_', ' ', $type) }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-slate-700">{{ number_format((float) $amount, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 px-5 py-3">
                <h3 class="font-semibold text-slate-800">Statuts des factures</h3>
            </div>
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Statut</th>
                        <th class="px-4 py-3 text-right">Nombre</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($report['invoices_by_status'] as $status => $count)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ match($status) {
                                        'paid'           => 'bg-green-100 text-green-700',
                                        'unpaid'         => 'bg-red-100 text-red-700',
                                        'partially_paid' => 'bg-yellow-100 text-yellow-700',
                                        'cancelled'      => 'bg-slate-100 text-slate-600',
                                        default          => 'bg-slate-100 text-slate-600',
                                    } }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-slate-700">{{ $count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
    // Tendance mensuelle
    const trendData = @json($report['monthly_trend']);
    const months    = Object.keys(trendData);
    const amounts   = Object.values(trendData).map(Number);

    new ApexCharts(document.getElementById('chart-trend'), {
        chart: { type: 'area', height: '100%', toolbar: { show: false }, fontFamily: 'inherit' },
        series: [{ name: 'Encaissements', data: amounts }],
        xaxis: { categories: months },
        colors: ['#0f4c81'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
        stroke: { curve: 'smooth', width: 2 },
        dataLabels: { enabled: false },
        tooltip: { y: { formatter: v => new Intl.NumberFormat('fr-FR').format(v) } },
        grid: { borderColor: '#f1f5f9' },
    }).render();

    // Méthodes de paiement
    const methodsRaw = @json($report['by_method']);
    const labels = Object.keys(methodsRaw).map(k => k.replace('_', ' '));
    const values = Object.values(methodsRaw).map(Number);

    new ApexCharts(document.getElementById('chart-methods'), {
        chart: { type: 'donut', height: '100%', toolbar: { show: false }, fontFamily: 'inherit' },
        series: values,
        labels: labels,
        colors: ['#0f4c81', '#10b981', '#f59e0b', '#6366f1', '#ef4444'],
        legend: { position: 'bottom' },
        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
        tooltip: { y: { formatter: v => new Intl.NumberFormat('fr-FR').format(v) } },
        plotOptions: { pie: { donut: { size: '60%' } } },
    }).render();
    </script>
    @endpush
</x-opticare-layout>
