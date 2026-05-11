<x-opticare-layout>
    <x-slot:pageTitle>Ouvrir une consultation</x-slot:pageTitle>

    <form method="POST" action="{{ route('consultations.store') }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        <input type="hidden" name="visit_id" value="{{ $visit->id }}">

        <div class="mb-6 rounded-md bg-slate-50 p-4 text-sm text-slate-700">
            Patient: <span class="font-medium text-slate-900">{{ $visit->patient?->full_name }}</span>
        </div>

        <div class="grid gap-5">
            <div>
                <label class="text-sm font-medium text-slate-700">Motif principal</label>
                <textarea name="chief_complaint" rows="3" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('chief_complaint') }}</textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Antécédents médicaux</label>
                <textarea name="medical_history" rows="3" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('medical_history', $visit->patient?->medical_history) }}</textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Antécédents ophtalmologiques</label>
                <textarea name="ophthalmic_history" rows="3" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('ophthalmic_history', $visit->patient?->ophthalmic_history) }}</textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Traitements en cours</label>
                <textarea name="current_medications" rows="3" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('current_medications') }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <a href="{{ route('reception.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
            <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Ouvrir</button>
        </div>
    </form>
</x-opticare-layout>
