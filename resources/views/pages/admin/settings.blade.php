<x-opticare-layout>
    <x-slot:pageTitle>Paramètres</x-slot:pageTitle>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @forelse($settings as $group => $items)
            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900">{{ ucfirst($group) }}</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    @foreach($items as $setting)
                        <div>
                            <label class="text-sm font-medium text-slate-700">{{ $setting->label ?? $setting->key }}</label>
                            <input name="{{ $setting->key }}" value="{{ old($setting->key, $setting->value) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <section class="rounded-lg border border-slate-200 bg-white p-6 text-sm text-slate-500 shadow-sm">Aucun paramètre disponible.</section>
        @endforelse

        <div class="flex justify-end">
            <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Enregistrer</button>
        </div>
    </form>
</x-opticare-layout>
