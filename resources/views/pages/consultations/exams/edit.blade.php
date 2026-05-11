<x-opticare-layout>
    <x-slot:pageTitle>Examens de consultation</x-slot:pageTitle>

    <form method="POST" action="{{ route('consultations.exams.update', $consultation) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Patient</p>
            <h2 class="mt-1 text-lg font-semibold text-slate-900">{{ $consultation->patient?->full_name }}</h2>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Acuité visuelle</h3>
            <div class="mt-4 grid gap-4 md:grid-cols-4">
                @foreach([
                    'right_eye_sc' => 'OD sans correction',
                    'left_eye_sc' => 'OG sans correction',
                    'right_eye_cc' => 'OD avec correction',
                    'left_eye_cc' => 'OG avec correction',
                    'near_right_sc' => 'Près OD sans correction',
                    'near_left_sc' => 'Près OG sans correction',
                    'near_right_cc' => 'Près OD avec correction',
                    'near_left_cc' => 'Près OG avec correction',
                ] as $field => $label)
                    <div>
                        <label class="text-sm font-medium text-slate-700">{{ $label }}</label>
                        <input name="visual_acuity[{{ $field }}]" value="{{ old('visual_acuity.' . $field, $consultation->visualAcuity?->{$field}) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
                    </div>
                @endforeach
                <div class="md:col-span-4">
                    <label class="text-sm font-medium text-slate-700">Remarques</label>
                    <textarea name="visual_acuity[remarks]" rows="3" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('visual_acuity.remarks', $consultation->visualAcuity?->remarks) }}</textarea>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Réfraction</h3>
            <div class="mt-4 overflow-hidden rounded-lg border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                        <tr><th class="px-4 py-3">Œil</th><th class="px-4 py-3">Sphère</th><th class="px-4 py-3">Cylindre</th><th class="px-4 py-3">Axe</th><th class="px-4 py-3">Addition</th><th class="px-4 py-3">DP</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-3 font-medium">OD</td>
                            <td class="px-4 py-3"><input type="number" step="0.25" name="refraction[right_sphere]" value="{{ old('refraction.right_sphere', $consultation->refraction?->right_sphere) }}" class="w-24 rounded-md border-slate-300 text-sm shadow-sm"></td>
                            <td class="px-4 py-3"><input type="number" step="0.25" name="refraction[right_cylinder]" value="{{ old('refraction.right_cylinder', $consultation->refraction?->right_cylinder) }}" class="w-24 rounded-md border-slate-300 text-sm shadow-sm"></td>
                            <td class="px-4 py-3"><input type="number" name="refraction[right_axis]" value="{{ old('refraction.right_axis', $consultation->refraction?->right_axis) }}" class="w-20 rounded-md border-slate-300 text-sm shadow-sm"></td>
                            <td class="px-4 py-3"><input type="number" step="0.25" name="refraction[right_addition]" value="{{ old('refraction.right_addition', $consultation->refraction?->right_addition) }}" class="w-24 rounded-md border-slate-300 text-sm shadow-sm"></td>
                            <td class="px-4 py-3"><input type="number" step="0.5" name="refraction[pd_right]" value="{{ old('refraction.pd_right', $consultation->refraction?->pd_right) }}" class="w-20 rounded-md border-slate-300 text-sm shadow-sm"></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium">OG</td>
                            <td class="px-4 py-3"><input type="number" step="0.25" name="refraction[left_sphere]" value="{{ old('refraction.left_sphere', $consultation->refraction?->left_sphere) }}" class="w-24 rounded-md border-slate-300 text-sm shadow-sm"></td>
                            <td class="px-4 py-3"><input type="number" step="0.25" name="refraction[left_cylinder]" value="{{ old('refraction.left_cylinder', $consultation->refraction?->left_cylinder) }}" class="w-24 rounded-md border-slate-300 text-sm shadow-sm"></td>
                            <td class="px-4 py-3"><input type="number" name="refraction[left_axis]" value="{{ old('refraction.left_axis', $consultation->refraction?->left_axis) }}" class="w-20 rounded-md border-slate-300 text-sm shadow-sm"></td>
                            <td class="px-4 py-3"><input type="number" step="0.25" name="refraction[left_addition]" value="{{ old('refraction.left_addition', $consultation->refraction?->left_addition) }}" class="w-24 rounded-md border-slate-300 text-sm shadow-sm"></td>
                            <td class="px-4 py-3"><input type="number" step="0.5" name="refraction[pd_left]" value="{{ old('refraction.pd_left', $consultation->refraction?->pd_left) }}" class="w-20 rounded-md border-slate-300 text-sm shadow-sm"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <input name="refraction[lens_type]" value="{{ old('refraction.lens_type', $consultation->refraction?->lens_type) }}" placeholder="Type de verre" class="rounded-md border-slate-300 text-sm shadow-sm">
                <input name="refraction[remarks]" value="{{ old('refraction.remarks', $consultation->refraction?->remarks) }}" placeholder="Remarques" class="rounded-md border-slate-300 text-sm shadow-sm">
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Pression intraoculaire</h3>
            <div class="mt-4 grid gap-4 md:grid-cols-4">
                <input type="number" step="0.1" name="eye_pressure[right_eye_pressure]" value="{{ old('eye_pressure.right_eye_pressure', $consultation->eyePressure?->right_eye_pressure) }}" placeholder="OD mmHg" class="rounded-md border-slate-300 text-sm shadow-sm">
                <input type="number" step="0.1" name="eye_pressure[left_eye_pressure]" value="{{ old('eye_pressure.left_eye_pressure', $consultation->eyePressure?->left_eye_pressure) }}" placeholder="OG mmHg" class="rounded-md border-slate-300 text-sm shadow-sm">
                <input name="eye_pressure[measurement_method]" value="{{ old('eye_pressure.measurement_method', $consultation->eyePressure?->measurement_method) }}" placeholder="Méthode" class="rounded-md border-slate-300 text-sm shadow-sm">
                <input type="datetime-local" name="eye_pressure[measured_at]" value="{{ old('eye_pressure.measured_at', $consultation->eyePressure?->measured_at?->format('Y-m-d\TH:i')) }}" class="rounded-md border-slate-300 text-sm shadow-sm">
                <textarea name="eye_pressure[remarks]" rows="3" placeholder="Remarques" class="rounded-md border-slate-300 text-sm shadow-sm md:col-span-4">{{ old('eye_pressure.remarks', $consultation->eyePressure?->remarks) }}</textarea>
            </div>
        </section>

        <div class="flex justify-end gap-2">
            <a href="{{ route('consultations.show', $consultation) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
            <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Enregistrer</button>
        </div>
    </form>
</x-opticare-layout>
