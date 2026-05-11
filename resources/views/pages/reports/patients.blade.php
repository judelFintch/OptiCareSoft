<x-opticare-layout>
    <x-slot:pageTitle>Rapport patients</x-slot:pageTitle>

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
            <a href="{{ route('reports.export.patients') }}?from={{ $from->format('Y-m-d') }}&to={{ $to->format('Y-m-d') }}"
               class="flex items-center gap-1.5 rounded-lg border border-green-300 bg-green-50 px-4 py-2 text-sm font-medium text-green-700 hover:bg-green-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Excel
            </a>
        @endcan
    </div>

    <div class="grid gap-4 mb-6 sm:grid-cols-2">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase text-slate-500 tracking-wide">Nouveaux patients</p>
            <p class="mt-2 text-3xl font-bold text-[#0f4c81]">{{ $report['new_patients'] }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase text-slate-500 tracking-wide">Visites totales</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $report['total_visits'] }}</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Répartition par genre --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4">Répartition par genre</h3>
            <div id="chart-gender" class="h-56"></div>
        </div>

        {{-- Top patients --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 px-5 py-3">
                <h3 class="font-semibold text-slate-800">Top patients (consultations)</h3>
            </div>
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Patient</th>
                        <th class="px-4 py-3 text-left">Code</th>
                        <th class="px-4 py-3 text-right">Consultations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($report['top_patients'] as $patient)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $patient->full_name }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $patient->patient_code }}</td>
                            <td class="px-4 py-3 text-right font-bold text-[#0f4c81]">{{ $patient->consultations_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-sm text-slate-400">Aucune donnée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
    const genderData = @json($report['by_gender']);
    const genderLabels = Object.keys(genderData).map(k => k === 'male' ? 'Homme' : (k === 'female' ? 'Femme' : 'Autre'));
    const genderValues = Object.values(genderData).map(Number);

    new ApexCharts(document.getElementById('chart-gender'), {
        chart: { type: 'pie', height: '100%', toolbar: { show: false }, fontFamily: 'inherit' },
        series: genderValues,
        labels: genderLabels,
        colors: ['#0f4c81', '#ec4899', '#6366f1'],
        legend: { position: 'bottom' },
        dataLabels: { formatter: (val, opts) => opts.w.globals.labels[opts.seriesIndex] + ': ' + Math.round(val) + '%' },
    }).render();
    </script>
    @endpush
</x-opticare-layout>
