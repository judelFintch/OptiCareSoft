<x-opticare-layout>
    <x-slot:pageTitle>Rapport consultations</x-slot:pageTitle>

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
    </div>

    {{-- KPIs --}}
    <div class="grid gap-4 mb-6 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase text-slate-500 tracking-wide">Total consultations</p>
            <p class="mt-2 text-3xl font-bold text-[#0f4c81]">{{ $report['total'] }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase text-slate-500 tracking-wide">Signées</p>
            <p class="mt-2 text-3xl font-bold text-green-600">{{ $report['signed'] }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase text-slate-500 tracking-wide">Diagnostics distincts</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $report['by_diagnosis']->count() }}</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Top diagnostics --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4">Diagnostics fréquents</h3>
            @if($report['by_diagnosis']->count())
                <div id="chart-diagnosis" class="h-64"></div>
            @else
                <p class="text-sm text-slate-400">Aucun diagnostic enregistré sur cette période.</p>
            @endif
        </div>

        {{-- Par médecin --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 px-5 py-3">
                <h3 class="font-semibold text-slate-800">Consultations par médecin</h3>
            </div>
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Médecin</th>
                        <th class="px-4 py-3 text-right">Consultations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($report['by_doctor'] as $row)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $row->doctor?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-right font-bold text-[#0f4c81]">{{ $row->count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="px-4 py-6 text-center text-sm text-slate-400">Aucune donnée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    @if($report['by_diagnosis']->count())
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
    const diagLabels = @json($report['by_diagnosis']->keys()->values());
    const diagValues = @json($report['by_diagnosis']->values());

    new ApexCharts(document.getElementById('chart-diagnosis'), {
        chart: { type: 'bar', height: '100%', toolbar: { show: false }, fontFamily: 'inherit' },
        series: [{ name: 'Consultations', data: diagValues }],
        xaxis: { categories: diagLabels },
        colors: ['#0f4c81'],
        plotOptions: { bar: { borderRadius: 4, horizontal: true } },
        dataLabels: { enabled: false },
    }).render();
    </script>
    @endif
    @endpush
</x-opticare-layout>
