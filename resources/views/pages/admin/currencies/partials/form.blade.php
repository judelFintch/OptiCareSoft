<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Nom <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $currency->name ?? '') }}" required
               class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:ring-[#0f4c81] focus:border-[#0f4c81]"
               placeholder="Franc CFA">
        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Code ISO <span class="text-red-500">*</span></label>
        <input type="text" name="code" value="{{ old('code', $currency->code ?? '') }}" required
               maxlength="10"
               class="w-full rounded-lg border-slate-300 shadow-sm text-sm font-mono focus:ring-[#0f4c81] focus:border-[#0f4c81]"
               placeholder="XAF">
        @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Symbole <span class="text-red-500">*</span></label>
        <input type="text" name="symbol" value="{{ old('symbol', $currency->symbol ?? '') }}" required
               maxlength="10"
               class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:ring-[#0f4c81] focus:border-[#0f4c81]"
               placeholder="FCFA">
        @error('symbol')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1">Taux de change <span class="text-red-500">*</span></label>
        <input type="number" name="exchange_rate" value="{{ old('exchange_rate', $currency->exchange_rate ?? 1) }}"
               min="0.000001" step="0.000001" required
               class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:ring-[#0f4c81] focus:border-[#0f4c81]">
        @error('exchange_rate')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_default" id="is_default" value="1"
               class="rounded border-slate-400 text-[#0f4c81]"
               {{ old('is_default', $currency->is_default ?? false) ? 'checked' : '' }}>
        <label for="is_default" class="text-sm font-medium text-slate-700">Devise par défaut</label>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" id="is_active" value="1"
               class="rounded border-slate-400 text-[#0f4c81]"
               {{ old('is_active', $currency->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
    </div>
</div>
