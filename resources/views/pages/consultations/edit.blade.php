<x-opticare-layout>
    <x-slot:pageTitle>Modifier la consultation</x-slot:pageTitle>

    <form method="POST" action="{{ route('consultations.update', $consultation) }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        <div class="grid gap-5">
            <div>
                <label class="text-sm font-medium text-slate-700">Motif principal</label>
                <textarea name="chief_complaint" rows="3" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('chief_complaint', $consultation->chief_complaint) }}</textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Histoire de la maladie</label>
                <textarea name="history_of_present_illness" rows="3" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('history_of_present_illness', $consultation->history_of_present_illness) }}</textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Constats cliniques</label>
                <textarea name="clinical_findings" rows="3" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('clinical_findings', $consultation->clinical_findings) }}</textarea>
            </div>
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-slate-700">Diagnostic principal</label>
                    <input name="primary_diagnosis" value="{{ old('primary_diagnosis', $consultation->primary_diagnosis) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Code ICD</label>
                    <input name="icd_code" value="{{ old('icd_code', $consultation->icd_code) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Plan de traitement</label>
                <textarea name="treatment_plan" rows="3" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('treatment_plan', $consultation->treatment_plan) }}</textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Recommandations</label>
                <textarea name="recommendations" rows="3" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('recommendations', $consultation->recommendations) }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <a href="{{ route('consultations.show', $consultation) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
            <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Enregistrer</button>
        </div>
    </form>
</x-opticare-layout>
