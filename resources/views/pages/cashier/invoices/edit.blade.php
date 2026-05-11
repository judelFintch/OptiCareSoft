<x-opticare-layout>
    <x-slot:pageTitle>Modifier — {{ $invoice->invoice_number }}</x-slot:pageTitle>

    @if($invoice->isPaid() || $invoice->isCancelled())
        <div class="mb-6 rounded-xl border border-orange-200 bg-orange-50 p-4 text-sm text-orange-800">
            <strong>Attention :</strong> cette facture est
            <strong>{{ $invoice->status->label() }}</strong>.
            Seules les notes et la date d'échéance peuvent être modifiées.
        </div>
    @endif

    <form method="POST" action="{{ route('cashier.invoices.update', $invoice) }}"
          x-data="invoiceEdit()" x-init="init()">
        @csrf @method('PUT')

        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc space-y-1">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Colonne principale --}}
            <div class="space-y-6 lg:col-span-2">

                {{-- En-tête facture (lecture seule) --}}
                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Facture</h2>
                    <div class="grid gap-4 sm:grid-cols-3 text-sm">
                        <div>
                            <p class="text-xs text-slate-400">Numéro</p>
                            <p class="mt-0.5 font-mono font-semibold text-slate-800">{{ $invoice->invoice_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Patient</p>
                            <p class="mt-0.5 font-medium text-slate-800">{{ $invoice->patient->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Type</p>
                            <p class="mt-0.5 font-medium text-slate-800">{{ $invoice->invoice_type->label() }}</p>
                        </div>
                    </div>
                </section>

                {{-- Lignes de facture --}}
                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Lignes</h2>
                        @if(!$invoice->isPaid() && !$invoice->isCancelled())
                            <button type="button" @click="addLine()"
                                    class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200">
                                + Ajouter une ligne
                            </button>
                        @endif
                    </div>

                    <div class="space-y-3" id="lines-container">
                        <template x-for="(line, idx) in lines" :key="idx">
                            <div class="grid grid-cols-12 gap-2 items-start">
                                <div class="col-span-5">
                                    <input type="text" :name="`items[${idx}][label]`" x-model="line.label"
                                           placeholder="Libellé"
                                           :readonly="{{ $invoice->isPaid() || $invoice->isCancelled() ? 'true' : 'false' }}"
                                           class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]"
                                           :class="{ 'bg-slate-100 cursor-not-allowed': {{ $invoice->isPaid() || $invoice->isCancelled() ? 'true' : 'false' }} }">
                                </div>
                                <div class="col-span-2">
                                    <input type="number" :name="`items[${idx}][quantity]`" x-model.number="line.quantity"
                                           min="1" placeholder="Qté"
                                           :readonly="{{ $invoice->isPaid() || $invoice->isCancelled() ? 'true' : 'false' }}"
                                           @input="recalc()"
                                           class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]"
                                           :class="{ 'bg-slate-100 cursor-not-allowed': {{ $invoice->isPaid() || $invoice->isCancelled() ? 'true' : 'false' }} }">
                                </div>
                                <div class="col-span-3">
                                    <input type="number" :name="`items[${idx}][unit_price]`" x-model.number="line.unit_price"
                                           min="0" step="0.01" placeholder="Prix unitaire"
                                           :readonly="{{ $invoice->isPaid() || $invoice->isCancelled() ? 'true' : 'false' }}"
                                           @input="recalc()"
                                           class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]"
                                           :class="{ 'bg-slate-100 cursor-not-allowed': {{ $invoice->isPaid() || $invoice->isCancelled() ? 'true' : 'false' }} }">
                                </div>
                                <div class="col-span-1 flex justify-end pt-2">
                                    @if(!$invoice->isPaid() && !$invoice->isCancelled())
                                        <button type="button" @click="removeLine(idx)"
                                                x-show="lines.length > 1"
                                                class="text-red-400 hover:text-red-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                <div class="col-span-1 pt-2 text-right text-sm font-medium text-slate-700">
                                    <span x-text="formatFC(line.quantity * line.unit_price)"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Remise --}}
                    @if(!$invoice->isPaid() && !$invoice->isCancelled())
                        <div class="mt-4 flex items-center gap-3 border-t border-slate-100 pt-4">
                            <label class="w-40 text-sm text-slate-600 shrink-0">Remise (FC)</label>
                            <input type="number" name="discount_amount" min="0" step="0.01"
                                   value="{{ old('discount_amount', $invoice->discount_amount) }}"
                                   @input="recalc()"
                                   x-model.number="discount"
                                   class="w-40 rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
                        </div>
                    @else
                        <input type="hidden" name="discount_amount" value="{{ $invoice->discount_amount }}">
                    @endif
                </section>

                {{-- Notes --}}
                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Notes</h2>
                    <textarea name="notes" rows="3"
                              placeholder="Informations complémentaires…"
                              class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">{{ old('notes', $invoice->notes) }}</textarea>
                </section>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Récapitulatif --}}
                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500">Récapitulatif</h2>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Sous-total</dt>
                            <dd class="font-medium text-slate-800" x-text="formatFC(subtotal)"></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Remise</dt>
                            <dd class="text-red-600" x-text="'- ' + formatFC(discount)"></dd>
                        </div>
                        <div class="flex justify-between border-t border-slate-100 pt-2 font-semibold">
                            <dt class="text-slate-800">Total</dt>
                            <dd class="text-[#0f4c81]" x-text="formatFC(total)"></dd>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <dt>Déjà payé</dt>
                            <dd class="text-green-600">{{ number_format((float) $invoice->paid_amount, 0, ',', ' ') }} FC</dd>
                        </div>
                        <div class="flex justify-between border-t border-slate-100 pt-2">
                            <dt class="font-semibold text-slate-700">Restant</dt>
                            <dd class="font-bold text-red-600" x-text="formatFC(Math.max(0, total - {{ (float) $invoice->paid_amount }}))"></dd>
                        </div>
                    </dl>
                </section>

                {{-- Date d'échéance --}}
                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Échéance</h2>
                    <input type="date" name="due_date"
                           value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}"
                           class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#0f4c81] focus:ring-[#0f4c81]">
                </section>

                {{-- Boutons --}}
                <div class="flex flex-col gap-2">
                    <button type="submit"
                            class="w-full rounded-lg bg-[#0f4c81] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                        Enregistrer les modifications
                    </button>
                    <a href="{{ route('cashier.invoices.show', $invoice) }}"
                       class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>

    <script>
    function invoiceEdit() {
        return {
            lines: @json($invoice->items->map(fn($i) => ['label' => $i->label, 'quantity' => (int) $i->quantity, 'unit_price' => (float) $i->unit_price])),
            discount: {{ (float) $invoice->discount_amount }},
            subtotal: 0,
            total: 0,

            init() { this.recalc(); },

            addLine() {
                this.lines.push({ label: '', quantity: 1, unit_price: 0 });
            },
            removeLine(idx) {
                this.lines.splice(idx, 1);
                this.recalc();
            },
            recalc() {
                this.subtotal = this.lines.reduce((s, l) => s + (l.quantity * l.unit_price), 0);
                this.total    = Math.max(0, this.subtotal - this.discount);
            },
            formatFC(n) {
                return new Intl.NumberFormat('fr-CD').format(Math.round(n)) + ' FC';
            },
        };
    }
    </script>
</x-opticare-layout>
