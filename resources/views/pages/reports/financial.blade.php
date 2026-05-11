<x-opticare-layout>
    <x-slot:pageTitle>Rapport financier</x-slot:pageTitle>

    <form method="GET" class="mb-6 flex flex-wrap gap-2">
        <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="rounded-md border-slate-300 text-sm shadow-sm">
        <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="rounded-md border-slate-300 text-sm shadow-sm">
        <button class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Afficher</button>
    </form>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Facturé</p><p class="mt-2 text-2xl font-semibold">{{ number_format((float) $report['total_invoiced'], 2) }}</p></div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Encaissé</p><p class="mt-2 text-2xl font-semibold">{{ number_format((float) $report['total_collected'], 2) }}</p></div>
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Dettes</p><p class="mt-2 text-2xl font-semibold">{{ number_format((float) $report['total_debt'], 2) }}</p></div>
    </div>
</x-opticare-layout>
