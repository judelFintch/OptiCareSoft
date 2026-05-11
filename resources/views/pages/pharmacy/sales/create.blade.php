<x-opticare-layout>
    <x-slot:pageTitle>Nouvelle vente</x-slot:pageTitle>

    <div class="mb-4">
        <a href="{{ route('pharmacy.sales.index') }}" class="text-sm text-[#0f4c81] hover:underline">← Retour aux ventes</a>
    </div>

    <form method="POST" action="{{ route('pharmacy.sales.store') }}"
          x-data="pharmacySale()" class="grid gap-6 lg:grid-cols-3">
        @csrf

        {{-- Produits --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Produits</h2>

                <div class="mb-3 flex gap-2">
                    <select x-model="selectedProduct"
                            class="flex-1 rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                        <option value="">Sélectionner un produit…</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->selling_price }}"
                                    data-stock="{{ $product->stock_quantity }}">
                                {{ $product->name }} — Stock: {{ $product->stock_quantity }} — {{ number_format($product->selling_price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" @click="addProduct"
                            class="rounded-lg bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                        Ajouter
                    </button>
                </div>

                <div class="overflow-hidden rounded-lg border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                            <tr>
                                <th class="px-3 py-2 text-left">Produit</th>
                                <th class="px-3 py-2 text-center">Qté</th>
                                <th class="px-3 py-2 text-right">Prix U.</th>
                                <th class="px-3 py-2 text-right">Total</th>
                                <th class="px-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class="px-3 py-2">
                                        <span x-text="item.name" class="font-medium text-slate-800"></span>
                                        <input type="hidden" :name="'items['+index+'][pharmacy_product_id]'" :value="item.id">
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <input type="number" :name="'items['+index+'][quantity]'"
                                               x-model.number="item.qty" min="1" :max="item.stock"
                                               @input="item.total = item.qty * item.price"
                                               class="w-16 rounded border-slate-300 text-center text-sm">
                                    </td>
                                    <td class="px-3 py-2 text-right font-mono text-slate-700"
                                        x-text="item.price.toFixed(2)"></td>
                                    <td class="px-3 py-2 text-right font-semibold text-slate-800"
                                        x-text="(item.qty * item.price).toFixed(2)"></td>
                                    <td class="px-3 py-2 text-center">
                                        <button type="button" @click="items.splice(index, 1)"
                                                class="text-red-400 hover:text-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="items.length === 0">
                                <td colspan="5" class="px-3 py-6 text-center text-sm text-slate-400">Aucun produit ajouté.</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-slate-50">
                            <tr>
                                <td colspan="3" class="px-3 py-2 text-right font-semibold text-slate-700">Total</td>
                                <td class="px-3 py-2 text-right text-lg font-bold text-[#0f4c81]" x-text="total.toFixed(2)"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Infos vente --}}
        <div class="space-y-4">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-slate-800">Informations</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Patient (optionnel)</label>
                        <select name="patient_id"
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            <option value="">— Sans patient —</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Paiement</label>
                        <select name="payment_status"
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                            <option value="paid">Payé</option>
                            <option value="unpaid">Impayé</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit"
                    :disabled="items.length === 0"
                    class="w-full rounded-lg bg-[#0f4c81] px-4 py-3 text-sm font-semibold text-white hover:bg-[#0b3f6d] disabled:opacity-50 disabled:cursor-not-allowed">
                Enregistrer la vente
            </button>
        </div>
    </form>

    <script>
    function pharmacySale() {
        return {
            selectedProduct: '',
            items: [],
            get total() {
                return this.items.reduce((s, i) => s + (i.qty * i.price), 0);
            },
            addProduct() {
                if (!this.selectedProduct) return;
                const sel = document.querySelector(`option[value="${this.selectedProduct}"]`);
                if (!sel) return;
                const existing = this.items.find(i => i.id == this.selectedProduct);
                if (existing) { existing.qty++; return; }
                this.items.push({
                    id: this.selectedProduct,
                    name: sel.dataset.name,
                    price: parseFloat(sel.dataset.price),
                    stock: parseInt(sel.dataset.stock),
                    qty: 1,
                });
                this.selectedProduct = '';
            }
        }
    }
    </script>
</x-opticare-layout>
