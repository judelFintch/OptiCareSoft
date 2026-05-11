@php
    $items = old('items', $prescription?->items?->map(fn ($item) => $item->only(['drug_name', 'generic_name', 'dosage', 'form', 'frequency', 'duration', 'route', 'instructions']))->toArray() ?? [
        ['drug_name' => '', 'generic_name' => '', 'dosage' => '', 'form' => 'collyre', 'frequency' => '', 'duration' => '', 'route' => 'topique', 'instructions' => ''],
    ]);
@endphp

<form method="POST" action="{{ $action }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="mb-6 rounded-md bg-slate-50 p-4 text-sm text-slate-700">
        Patient: <span class="font-medium text-slate-900">{{ $consultation?->patient?->full_name ?? $prescription?->patient?->full_name }}</span>
    </div>

    <div class="space-y-4">
        @for($i = 0; $i < max(3, count($items)); $i++)
            @php($item = $items[$i] ?? [])
            <section class="rounded-lg border border-slate-200 p-4">
                <h3 class="text-sm font-semibold text-slate-900">Médicament {{ $i + 1 }}</h3>
                <div class="mt-4 grid gap-4 md:grid-cols-4">
                    <input name="items[{{ $i }}][drug_name]" value="{{ $item['drug_name'] ?? '' }}" placeholder="Nom du médicament" @required($i === 0) class="rounded-md border-slate-300 text-sm shadow-sm md:col-span-2">
                    <input name="items[{{ $i }}][dosage]" value="{{ $item['dosage'] ?? '' }}" placeholder="Dosage" class="rounded-md border-slate-300 text-sm shadow-sm">
                    <input name="items[{{ $i }}][form]" value="{{ $item['form'] ?? '' }}" placeholder="Forme" class="rounded-md border-slate-300 text-sm shadow-sm">
                    <input name="items[{{ $i }}][frequency]" value="{{ $item['frequency'] ?? '' }}" placeholder="Fréquence" class="rounded-md border-slate-300 text-sm shadow-sm">
                    <input name="items[{{ $i }}][duration]" value="{{ $item['duration'] ?? '' }}" placeholder="Durée" class="rounded-md border-slate-300 text-sm shadow-sm">
                    <input name="items[{{ $i }}][route]" value="{{ $item['route'] ?? '' }}" placeholder="Voie" class="rounded-md border-slate-300 text-sm shadow-sm">
                    <input name="items[{{ $i }}][generic_name]" value="{{ $item['generic_name'] ?? '' }}" placeholder="Générique" class="rounded-md border-slate-300 text-sm shadow-sm">
                    <textarea name="items[{{ $i }}][instructions]" rows="2" placeholder="Instructions" class="rounded-md border-slate-300 text-sm shadow-sm md:col-span-4">{{ $item['instructions'] ?? '' }}</textarea>
                </div>
            </section>
        @endfor
    </div>

    <div class="mt-6 grid gap-5 md:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-slate-700">Valide jusqu'au</label>
            <input type="date" name="valid_until" value="{{ old('valid_until', $prescription?->valid_until?->format('Y-m-d') ?? now()->addMonth()->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Instructions générales</label>
            <input name="instructions" value="{{ old('instructions', $prescription?->instructions) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-700">Notes</label>
            <textarea name="notes" rows="3" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('notes', $prescription?->notes) }}</textarea>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ $consultation ? route('consultations.show', $consultation) : route('medical-prescriptions.show', $prescription) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
        <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Enregistrer</button>
    </div>
</form>
