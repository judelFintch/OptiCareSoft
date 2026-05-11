<x-opticare-layout>
    <x-slot:pageTitle>Permissions — {{ $role->name }}</x-slot:pageTitle>

    <div class="mb-4">
        <a href="{{ route('admin.roles.index') }}" class="text-sm text-[#0f4c81] hover:underline">← Retour aux rôles</a>
    </div>

    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf @method('PUT')

        <div class="space-y-4">
            @foreach($allPermissions as $group => $permissions)
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 bg-slate-50 px-6 py-3">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-slate-800 capitalize">{{ str_replace('_', ' ', $group) }}</h3>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer select-none">
                                <input type="checkbox"
                                       class="rounded border-slate-400 text-[#0f4c81]"
                                       x-data
                                       @change="$el.closest('.perm-group').querySelectorAll('input[type=checkbox]').forEach(c => c.checked = $el.checked)"
                                       title="Tout sélectionner">
                                <span>Tout sélectionner</span>
                            </label>
                        </div>
                    </div>
                    <div class="perm-group grid grid-cols-2 gap-3 px-6 py-4 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach($permissions as $permission)
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->id }}"
                                       class="rounded border-slate-400 text-[#0f4c81]"
                                       {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                <span class="text-sm text-slate-700">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.roles.index') }}"
               class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Annuler
            </a>
            <button type="submit"
                    class="rounded-lg bg-[#0f4c81] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                Enregistrer les permissions
            </button>
        </div>
    </form>
</x-opticare-layout>
