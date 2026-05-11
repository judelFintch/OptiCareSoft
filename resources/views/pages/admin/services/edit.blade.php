<x-opticare-layout>
    <x-slot:pageTitle>Modifier — {{ $service->name }}</x-slot:pageTitle>

    <div class="mb-4">
        <a href="{{ route('admin.services.index') }}" class="text-sm text-[#0f4c81] hover:underline">← Retour aux services</a>
    </div>

    <div class="max-w-2xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-5">
            @csrf @method('PUT')
            @include('pages.admin.services.partials.form', ['service' => $service])
            <div class="flex justify-between pt-2">
                <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                      onsubmit="return confirm('Supprimer ce service ?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="rounded-lg border border-red-300 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50">
                        Supprimer
                    </button>
                </form>
                <div class="flex gap-3">
                    <a href="{{ route('admin.services.index') }}"
                       class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Annuler
                    </a>
                    <button type="submit"
                            class="rounded-lg bg-[#0f4c81] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                        Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-opticare-layout>
