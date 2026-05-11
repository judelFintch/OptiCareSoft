<x-opticare-layout>
    <x-slot:pageTitle>Nouvelle facture</x-slot:pageTitle>

    <form method="POST" action="{{ route('cashier.invoices.store') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium text-slate-700">Patient</label>
                <select name="patient_id" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->full_name }} · {{ $patient->patient_code }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Type</label>
                <select name="invoice_type" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
                    @foreach(['consultation', 'optical', 'pharmacy', 'exam', 'global'] as $type)
                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-6 rounded-md border border-slate-200 p-4">
            <h3 class="text-sm font-semibold text-slate-900">Ligne de facture</h3>
            <div class="mt-4 grid gap-4 md:grid-cols-3">
                <input name="items[0][label]" placeholder="Libellé" required class="rounded-md border-slate-300 text-sm shadow-sm">
                <input type="number" name="items[0][quantity]" value="1" min="1" required class="rounded-md border-slate-300 text-sm shadow-sm">
                <input type="number" name="items[0][unit_price]" value="0" min="0" step="0.01" required class="rounded-md border-slate-300 text-sm shadow-sm">
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <a href="{{ route('cashier.invoices.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
            <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Créer</button>
        </div>
    </form>
</x-opticare-layout>
