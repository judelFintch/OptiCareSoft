<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Nom du service <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $service->name ?? '') }}" required
               class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:ring-[#0f4c81] focus:border-[#0f4c81]">
        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Code <span class="text-red-500">*</span></label>
        <input type="text" name="code" value="{{ old('code', $service->code ?? '') }}" required
               class="w-full rounded-lg border-slate-300 shadow-sm text-sm font-mono focus:ring-[#0f4c81] focus:border-[#0f4c81]"
               placeholder="CONSULT-STANDARD">
        @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Catégorie <span class="text-red-500">*</span></label>
        <select name="category" required
                class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:ring-[#0f4c81] focus:border-[#0f4c81]">
            @foreach(['consultation' => 'Consultation', 'exam' => 'Examen', 'optical' => 'Optique', 'pharmacy' => 'Pharmacie', 'general' => 'Général'] as $val => $label)
                <option value="{{ $val }}" {{ old('category', $service->category ?? '') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('category')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Prix par défaut <span class="text-red-500">*</span></label>
        <input type="number" name="default_price" value="{{ old('default_price', $service->default_price ?? 0) }}"
               min="0" step="0.01" required
               class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:ring-[#0f4c81] focus:border-[#0f4c81]">
        @error('default_price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Devise <span class="text-red-500">*</span></label>
        <select name="currency_id" required
                class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:ring-[#0f4c81] focus:border-[#0f4c81]">
            @foreach($currencies as $currency)
                <option value="{{ $currency->id }}"
                        {{ old('currency_id', $service->currency_id ?? '') == $currency->id ? 'selected' : '' }}>
                    {{ $currency->name }} ({{ $currency->code }})
                </option>
            @endforeach
        </select>
        @error('currency_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
        <textarea name="description" rows="3"
                  class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:ring-[#0f4c81] focus:border-[#0f4c81]">{{ old('description', $service->description ?? '') }}</textarea>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" id="is_active" value="1"
               class="rounded border-slate-400 text-[#0f4c81]"
               {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-sm font-medium text-slate-700">Service actif</label>
    </div>
</div>
