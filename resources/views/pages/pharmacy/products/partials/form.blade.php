<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Identification --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-sm font-semibold uppercase tracking-wide text-slate-500">Identification</h2>
        <div class="grid gap-5 md:grid-cols-3">
            <div>
                <label class="text-sm font-medium text-slate-700">Référence <span class="text-red-500">*</span></label>
                <input name="reference" value="{{ old('reference', $product?->reference) }}" required
                       placeholder="PH-XXX-000"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
            </div>
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-700">Nom commercial <span class="text-red-500">*</span></label>
                <input name="name" value="{{ old('name', $product?->name) }}" required
                       placeholder="Tobradex Collyre"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
            </div>
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-slate-700">Nom générique / DCI</label>
                <input name="generic_name" value="{{ old('generic_name', $product?->generic_name) }}"
                       placeholder="Tobramycine / Dexaméthasone"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Fabricant</label>
                <input name="manufacturer" value="{{ old('manufacturer', $product?->manufacturer) }}"
                       placeholder="Alcon, MSD…"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
            </div>
        </div>
    </div>

    {{-- Classification --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-sm font-semibold uppercase tracking-wide text-slate-500">Classification</h2>
        <div class="grid gap-5 md:grid-cols-3">
            <div>
                <label class="text-sm font-medium text-slate-700">Catégorie <span class="text-red-500">*</span></label>
                <select name="category" required class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
                    @foreach([
                        'collyre'            => 'Collyre / Antibiotique',
                        'larme_artificielle' => 'Larme artificielle',
                        'anti_inflammatoire' => 'Anti-inflammatoire',
                        'antiglaucome'       => 'Antiglaucome',
                        'antiallergique'     => 'Antiallergique',
                        'mydriatique'        => 'Mydriatique / Cycloplégique',
                        'anesthesique'       => 'Anesthésique',
                        'vitamine'           => 'Vitamine / Complément',
                        'autre'              => 'Autre',
                    ] as $val => $label)
                        <option value="{{ $val }}" {{ old('category', $product?->category) === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Forme galénique <span class="text-red-500">*</span></label>
                <select name="form" required class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
                    @foreach(['collyre', 'gel', 'pommade', 'comprimé', 'gélule', 'injectable', 'autre'] as $f)
                        <option value="{{ $f }}" {{ old('form', $product?->form) === $f ? 'selected' : '' }}>
                            {{ ucfirst($f) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Dosage / Conditionnement</label>
                <input name="dosage" value="{{ old('dosage', $product?->dosage) }}"
                       placeholder="5 ml, 30 cp…"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Fournisseur</label>
                <select name="supplier_id" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
                    <option value="">— Aucun —</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $product?->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Date d'expiration</label>
                <input type="date" name="expiry_date"
                       value="{{ old('expiry_date', $product?->expiry_date?->format('Y-m-d')) }}"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
            </div>
            <div class="flex items-center gap-3 pt-6">
                <input type="hidden" name="is_prescription_required" value="0">
                <input type="checkbox" id="rx_required" name="is_prescription_required" value="1"
                       {{ old('is_prescription_required', $product?->is_prescription_required) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-slate-300 text-[#0f4c81] focus:ring-[#0f4c81]">
                <label for="rx_required" class="text-sm font-medium text-slate-700">Nécessite une ordonnance</label>
            </div>
        </div>
    </div>

    {{-- Prix & Stock --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-sm font-semibold uppercase tracking-wide text-slate-500">Prix & Stock</h2>
        <div class="grid gap-5 md:grid-cols-4">
            <div>
                <label class="text-sm font-medium text-slate-700">Prix d'achat (FC) <span class="text-red-500">*</span></label>
                <input type="number" name="purchase_price" min="0" step="0.01"
                       value="{{ old('purchase_price', $product?->purchase_price) }}" required
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Prix de vente (FC) <span class="text-red-500">*</span></label>
                <input type="number" name="selling_price" min="0" step="0.01"
                       value="{{ old('selling_price', $product?->selling_price) }}" required
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Stock actuel</label>
                <input type="number" name="stock_quantity" min="0"
                       value="{{ old('stock_quantity', $product?->stock_quantity ?? 0) }}"
                       {{ $product ? 'readonly class="mt-1 w-full rounded-lg border-slate-200 bg-slate-100 text-sm shadow-sm cursor-not-allowed"' : 'class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]"' }}>
                @if($product)
                    <p class="mt-1 text-xs text-slate-400">Modifiable via un mouvement de stock</p>
                @endif
            </div>
            <div>
                <label class="text-sm font-medium text-slate-700">Niveau réapprovisionnement</label>
                <input type="number" name="reorder_level" min="0"
                       value="{{ old('reorder_level', $product?->reorder_level ?? 5) }}"
                       class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex justify-end gap-3">
        <a href="{{ route('pharmacy.products.index') }}"
           class="rounded-lg border border-slate-300 px-5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
            Annuler
        </a>
        <button type="submit"
                class="rounded-lg bg-[#0f4c81] px-5 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">
            {{ $product ? 'Enregistrer les modifications' : 'Créer le produit' }}
        </button>
    </div>
</form>
