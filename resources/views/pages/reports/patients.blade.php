<x-opticare-layout>
    <x-slot:pageTitle>Rapport patients</x-slot:pageTitle>

    <form method="GET" class="mb-6 flex flex-wrap gap-2">
        <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="rounded-md border-slate-300 text-sm shadow-sm">
        <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="rounded-md border-slate-300 text-sm shadow-sm">
        <button class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Afficher</button>
    </form>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Nouveaux patients</p><p class="mt-2 text-2xl font-semibold">{{ $report['new_patients'] }}</p></div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Visites</p><p class="mt-2 text-2xl font-semibold">{{ $report['total_visits'] }}</p></div>
    </div>
</x-opticare-layout>
