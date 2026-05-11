<x-opticare-layout>
    <x-slot:pageTitle>Nouvelle commande optique</x-slot:pageTitle>

    <div class="mb-4">
        <a href="{{ route('optical.orders.index') }}" class="text-sm text-[#0f4c81] hover:underline">← Retour aux commandes</a>
    </div>

    <form method="POST" action="{{ route('optical.orders.store') }}"
          x-data="opticalOrder()" class="grid gap-6 lg:grid-cols-3"
          @submit.prevent="$el.submit()">
        @csrf

        <div class="lg:col-span-2 space-y-5">

            {{-- Patient --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Patient</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Patient <span class="text-red-500">*</span></label>
                        <select name="patient_id" x-model="patientId" @change="loadPrescriptions" required
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            <option value="">Choisir un patient…</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->full_name }} — {{ $p->patient_code }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Ordonnance optique liée</label>
                        <select name="optical_prescription_id"
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            <option value="">— Aucune —</option>
                            <template x-for="rx in prescriptions" :key="rx.id">
                                <option :value="rx.id" x-text="rx.label"></option>
                            </template>
                        </select>
                        <p x-show="loadingRx" class="mt-1 text-xs text-slate-400">Chargement…</p>
                    </div>
                </div>
            </div>

            {{-- Correction (OD / OG) --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Correction prescrite</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-xs font-semibold uppercase text-slate-500">
                                <th class="pb-2 pr-4 text-left">Œil</th>
                                <th class="pb-2 px-2 text-center">Sphère</th>
                                <th class="pb-2 px-2 text-center">Cylindre</th>
                                <th class="pb-2 px-2 text-center">Axe</th>
                                <th class="pb-2 px-2 text-center">Addition</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="py-2 pr-4 font-semibold text-slate-700">OD</td>
                                <td class="py-2 px-2"><input type="number" name="right_sphere" value="{{ old('right_sphere') }}" step="0.25" class="w-20 rounded-lg border-slate-300 text-sm text-center shadow-sm focus:ring-[#0f4c81]"></td>
                                <td class="py-2 px-2"><input type="number" name="right_cylinder" value="{{ old('right_cylinder') }}" step="0.25" class="w-20 rounded-lg border-slate-300 text-sm text-center shadow-sm focus:ring-[#0f4c81]"></td>
                                <td class="py-2 px-2"><input type="number" name="right_axis" value="{{ old('right_axis') }}" min="0" max="180" class="w-20 rounded-lg border-slate-300 text-sm text-center shadow-sm focus:ring-[#0f4c81]"></td>
                                <td class="py-2 px-2"><input type="number" name="right_addition" value="{{ old('right_addition') }}" step="0.25" class="w-20 rounded-lg border-slate-300 text-sm text-center shadow-sm focus:ring-[#0f4c81]"></td>
                            </tr>
                            <tr>
                                <td class="py-2 pr-4 font-semibold text-slate-700">OG</td>
                                <td class="py-2 px-2"><input type="number" name="left_sphere" value="{{ old('left_sphere') }}" step="0.25" class="w-20 rounded-lg border-slate-300 text-sm text-center shadow-sm focus:ring-[#0f4c81]"></td>
                                <td class="py-2 px-2"><input type="number" name="left_cylinder" value="{{ old('left_cylinder') }}" step="0.25" class="w-20 rounded-lg border-slate-300 text-sm text-center shadow-sm focus:ring-[#0f4c81]"></td>
                                <td class="py-2 px-2"><input type="number" name="left_axis" value="{{ old('left_axis') }}" min="0" max="180" class="w-20 rounded-lg border-slate-300 text-sm text-center shadow-sm focus:ring-[#0f4c81]"></td>
                                <td class="py-2 px-2"><input type="number" name="left_addition" value="{{ old('left_addition') }}" step="0.25" class="w-20 rounded-lg border-slate-300 text-sm text-center shadow-sm focus:ring-[#0f4c81]"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Distance pupillaire (mm)</label>
                    <input type="number" name="pupillary_distance" value="{{ old('pupillary_distance') }}"
                           step="0.5" min="40" max="80"
                           class="w-32 rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                </div>
            </div>

            {{-- Équipement --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Équipement</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Monture</label>
                        <select name="frame_id" x-model="frameId" @change="updatePrice"
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            <option value="">— Aucune —</option>
                            @foreach($frames as $frame)
                                <option value="{{ $frame->id }}"
                                        data-price="{{ $frame->selling_price }}"
                                        {{ old('frame_id') == $frame->id ? 'selected' : '' }}>
                                    {{ $frame->brand }} {{ $frame->model }} — {{ $frame->color }}
                                    (stock: {{ $frame->stock_quantity }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Verre OD</label>
                        <select name="right_lens_id" x-model="rightLensId" @change="updatePrice"
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            <option value="">— Aucun —</option>
                            @foreach($lenses as $lens)
                                <option value="{{ $lens->id }}"
                                        data-price="{{ $lens->selling_price }}"
                                        {{ old('right_lens_id') == $lens->id ? 'selected' : '' }}>
                                    {{ $lens->full_description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Verre OG</label>
                        <select name="left_lens_id" x-model="leftLensId" @change="updatePrice"
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            <option value="">— Aucun —</option>
                            @foreach($lenses as $lens)
                                <option value="{{ $lens->id }}"
                                        data-price="{{ $lens->selling_price }}"
                                        {{ old('left_lens_id') == $lens->id ? 'selected' : '' }}>
                                    {{ $lens->full_description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Date prévue</label>
                        <input type="date" name="expected_date" value="{{ old('expected_date') }}"
                               class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Instructions spéciales</label>
                        <textarea name="special_instructions" rows="2"
                                  class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">{{ old('special_instructions') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Fournisseur / Opticien --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Fabrication</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Fournisseur</label>
                        <select name="supplier_id"
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            <option value="">— Aucun —</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Assigné à</label>
                        <select name="assigned_to"
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            <option value="">— Aucun —</option>
                            @foreach($doctors as $doc)
                                <option value="{{ $doc->id }}" {{ old('assigned_to') == $doc->id ? 'selected' : '' }}>
                                    {{ $doc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2"
                                  class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar tarification --}}
        <div class="space-y-4">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sticky top-24">
                <h2 class="mb-4 font-semibold text-slate-800">Tarification</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Prix monture <span class="text-red-500">*</span></label>
                        <input type="number" name="price_frame" x-model.number="priceFrame"
                               @input="calcTotal" min="0" step="0.01" required
                               class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Prix verres <span class="text-red-500">*</span></label>
                        <input type="number" name="price_lenses" x-model.number="priceLenses"
                               @input="calcTotal" min="0" step="0.01" required
                               class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                    </div>
                    <div class="rounded-lg bg-slate-50 p-3">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-500">Total</span>
                            <span class="font-bold text-slate-800" x-text="total.toFixed(2)"></span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Acompte versé <span class="text-red-500">*</span></label>
                        <input type="number" name="deposit_paid" x-model.number="deposit"
                               @input="calcTotal" min="0" step="0.01" required
                               class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                    </div>
                    <div class="rounded-lg bg-blue-50 border border-blue-200 p-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-blue-700 font-medium">Reste à payer</span>
                            <span class="font-bold text-blue-900" x-text="remaining.toFixed(2)"></span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 space-y-2">
                    <button type="submit"
                            class="w-full rounded-lg bg-[#0f4c81] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#0b3f6d]">
                        Créer la commande
                    </button>
                    <a href="{{ route('optical.orders.index') }}"
                       class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>

    <script>
    function opticalOrder() {
        return {
            frameId: '{{ old('frame_id', '') }}',
            rightLensId: '{{ old('right_lens_id', '') }}',
            leftLensId: '{{ old('left_lens_id', '') }}',
            patientId: '{{ old('patient_id', '') }}',
            prescriptions: [],
            loadingRx: false,
            priceFrame: {{ old('price_frame', 0) }},
            priceLenses: {{ old('price_lenses', 0) }},
            deposit: {{ old('deposit_paid', 0) }},
            get total() { return this.priceFrame + this.priceLenses; },
            get remaining() { return Math.max(0, this.total - this.deposit); },
            calcTotal() {},
            async loadPrescriptions() {
                if (!this.patientId) { this.prescriptions = []; return; }
                this.loadingRx = true;
                try {
                    const res = await fetch(`{{ url('optical/prescriptions-for-patient') }}?patient_id=${this.patientId}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    this.prescriptions = await res.json();
                } finally {
                    this.loadingRx = false;
                }
            },
            updatePrice() {
                const getPrice = (sel, id) => {
                    if (!id) return 0;
                    const opt = sel.querySelector(`option[value="${id}"]`);
                    return opt ? parseFloat(opt.dataset.price || 0) : 0;
                };
                const frameSel = document.querySelector('select[name="frame_id"]');
                const rSel = document.querySelector('select[name="right_lens_id"]');
                const lSel = document.querySelector('select[name="left_lens_id"]');
                if (this.frameId) this.priceFrame = getPrice(frameSel, this.frameId);
                this.priceLenses = getPrice(rSel, this.rightLensId) + getPrice(lSel, this.leftLensId);
            },
            init() {
                if (this.patientId) this.loadPrescriptions();
            }
        }
    }
    </script>
</x-opticare-layout>
