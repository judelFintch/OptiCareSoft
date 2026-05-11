<form method="POST" action="{{ $action }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="mb-6 rounded-md bg-slate-50 p-4 text-sm text-slate-700">
        Patient: <span class="font-medium text-slate-900">{{ $consultation?->patient?->full_name ?? $prescription?->patient?->full_name }}</span>
    </div>

    <div class="overflow-hidden rounded-lg border border-slate-200">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">Œil</th>
                    <th class="px-4 py-3">Sphère</th>
                    <th class="px-4 py-3">Cylindre</th>
                    <th class="px-4 py-3">Axe</th>
                    <th class="px-4 py-3">Addition</th>
                    <th class="px-4 py-3">DP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr>
                    <td class="px-4 py-3 font-medium">OD</td>
                    <td class="px-4 py-3"><input type="number" step="0.25" name="right_sphere" value="{{ old('right_sphere', $prescription?->right_sphere) }}" class="w-24 rounded-md border-slate-300 text-sm shadow-sm"></td>
                    <td class="px-4 py-3"><input type="number" step="0.25" name="right_cylinder" value="{{ old('right_cylinder', $prescription?->right_cylinder) }}" class="w-24 rounded-md border-slate-300 text-sm shadow-sm"></td>
                    <td class="px-4 py-3"><input type="number" name="right_axis" value="{{ old('right_axis', $prescription?->right_axis) }}" class="w-20 rounded-md border-slate-300 text-sm shadow-sm"></td>
                    <td class="px-4 py-3"><input type="number" step="0.25" name="right_addition" value="{{ old('right_addition', $prescription?->right_addition) }}" class="w-24 rounded-md border-slate-300 text-sm shadow-sm"></td>
                    <td class="px-4 py-3"><input type="number" step="0.5" name="pd_right" value="{{ old('pd_right', $prescription?->pd_right) }}" class="w-20 rounded-md border-slate-300 text-sm shadow-sm"></td>
                </tr>
                <tr>
                    <td class="px-4 py-3 font-medium">OG</td>
                    <td class="px-4 py-3"><input type="number" step="0.25" name="left_sphere" value="{{ old('left_sphere', $prescription?->left_sphere) }}" class="w-24 rounded-md border-slate-300 text-sm shadow-sm"></td>
                    <td class="px-4 py-3"><input type="number" step="0.25" name="left_cylinder" value="{{ old('left_cylinder', $prescription?->left_cylinder) }}" class="w-24 rounded-md border-slate-300 text-sm shadow-sm"></td>
                    <td class="px-4 py-3"><input type="number" name="left_axis" value="{{ old('left_axis', $prescription?->left_axis) }}" class="w-20 rounded-md border-slate-300 text-sm shadow-sm"></td>
                    <td class="px-4 py-3"><input type="number" step="0.25" name="left_addition" value="{{ old('left_addition', $prescription?->left_addition) }}" class="w-24 rounded-md border-slate-300 text-sm shadow-sm"></td>
                    <td class="px-4 py-3"><input type="number" step="0.5" name="pd_left" value="{{ old('pd_left', $prescription?->pd_left) }}" class="w-20 rounded-md border-slate-300 text-sm shadow-sm"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-6 grid gap-5 md:grid-cols-3">
        <div>
            <label class="text-sm font-medium text-slate-700">Type de verre</label>
            <select name="lens_type" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
                <option value="">Non précisé</option>
                @foreach(\App\Enums\LensType::cases() as $type)
                    <option value="{{ $type->value }}" @selected(old('lens_type', $prescription?->lens_type?->value) === $type->value)>{{ $type->label() }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Usage</label>
            <select name="usage" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
                @foreach(['' => 'Non précisé', 'distance' => 'Loin', 'near' => 'Près', 'mixed' => 'Mixte'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('usage', $prescription?->usage) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Valide jusqu'au</label>
            <input type="date" name="valid_until" value="{{ old('valid_until', $prescription?->valid_until?->format('Y-m-d') ?? now()->addYear()->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
    </div>

    <div class="mt-6">
        <label class="text-sm font-medium text-slate-700">Remarques</label>
        <textarea name="remarks" rows="4" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('remarks', $prescription?->remarks) }}</textarea>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ $consultation ? route('consultations.show', $consultation) : route('optical-prescriptions.show', $prescription) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
        <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Enregistrer</button>
    </div>
</form>
