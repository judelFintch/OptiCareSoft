<x-opticare-layout>
    <x-slot:pageTitle>Utilisateurs</x-slot:pageTitle>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.users.create') }}" class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Nouvel utilisateur</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                <tr><th class="px-4 py-3">Nom</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Rôles</th><th class="px-4 py-3">Statut</th><th class="px-4 py-3 text-right">Actions</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->getRoleNames()->join(', ') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->is_active ? 'Actif' : 'Inactif' }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('admin.users.edit', $user) }}" class="font-medium text-[#0f4c81] hover:underline">Modifier</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</x-opticare-layout>
