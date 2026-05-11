<x-opticare-layout>
    <x-slot:pageTitle>{{ $user->name }}</x-slot:pageTitle>
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm text-slate-500">{{ $user->email }}</p>
        <p class="mt-2 text-sm text-slate-700">Rôles: {{ $user->getRoleNames()->join(', ') }}</p>
    </section>
</x-opticare-layout>
