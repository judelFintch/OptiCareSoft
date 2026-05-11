<form method="POST" action="{{ $action }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-slate-700">Nom</label>
            <input name="name" value="{{ old('name', $user?->name) }}" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user?->email) }}" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Téléphone</label>
            <input name="phone" value="{{ old('phone', $user?->phone) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Spécialité</label>
            <input name="specialty" value="{{ old('specialty', $user?->specialty) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Mot de passe</label>
            <input type="password" name="password" @required(!$user) class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Confirmation</label>
            <input type="password" name="password_confirmation" @required(!$user) class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Rôle</label>
            <select name="role" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" @selected(old('role', $user?->getRoleNames()->first()) === $role->name)>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        @if($user)
            <label class="flex items-center gap-2 pt-7 text-sm text-slate-700">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active)) class="rounded border-slate-300">
                Compte actif
            </label>
        @endif
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.users.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
        <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Enregistrer</button>
    </div>
</form>
