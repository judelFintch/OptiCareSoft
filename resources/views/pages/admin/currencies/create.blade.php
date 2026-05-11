<x-opticare-layout>
    <x-slot:pageTitle>Nouvelle devise</x-slot:pageTitle>

    <div class="mb-4">
        <a href="{{ route('admin.currencies.index') }}" class="text-sm text-[#0f4c81] hover:underline">← Retour aux devises</a>
    </div>

    <div class="max-w-lg rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.currencies.store') }}" class="space-y-5">
            @csrf
            @include('pages.admin.currencies.partials.form')
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.currencies.index') }}"
                   class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Annuler
                </a>
                <button type="submit"
                        class="rounded-lg bg-[#0f4c81] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                    Créer
                </button>
            </div>
        </form>
    </div>
</x-opticare-layout>
