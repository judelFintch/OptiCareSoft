<x-opticare-layout>
    <x-slot:pageTitle>Gestion des rôles</x-slot:pageTitle>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <h2 class="font-semibold text-slate-800">Rôles et permissions</h2>
            <p class="mt-0.5 text-sm text-slate-500">Cliquez sur un rôle pour gérer ses permissions.</p>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($roles as $role)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#0f4c81]/10 text-[#0f4c81] font-bold text-sm">
                            {{ strtoupper(substr($role->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ $role->name }}</p>
                            <p class="text-sm text-slate-500">{{ $role->permissions_count }} permission(s)</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.roles.show', $role) }}"
                       class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                        Gérer les permissions
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-opticare-layout>
