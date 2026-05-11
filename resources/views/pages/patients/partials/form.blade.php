<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-slate-700">Prénom</label>
            <input name="first_name" value="{{ old('first_name', $patient?->first_name) }}" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
            @error('first_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Nom</label>
            <input name="last_name" value="{{ old('last_name', $patient?->last_name) }}" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
            @error('last_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Genre</label>
            <select name="gender" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
                @foreach(['male' => 'Masculin', 'female' => 'Féminin', 'other' => 'Autre'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('gender', $patient?->gender?->value) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Date de naissance</label>
            <input type="date" name="birth_date" value="{{ old('birth_date', $patient?->birth_date?->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Téléphone</label>
            <input name="phone" value="{{ old('phone', $patient?->phone) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $patient?->email) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-700">Adresse</label>
            <input name="address" value="{{ old('address', $patient?->address) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Profession</label>
            <input name="profession" value="{{ old('profession', $patient?->profession) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Contact urgence</label>
            <input name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient?->emergency_contact_phone) }}" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Antécédents médicaux</label>
            <textarea name="medical_history" rows="4" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('medical_history', $patient?->medical_history) }}</textarea>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Antécédents ophtalmologiques</label>
            <textarea name="ophthalmic_history" rows="4" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('ophthalmic_history', $patient?->ophthalmic_history) }}</textarea>
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-700">Allergies</label>
            <textarea name="allergies" rows="3" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('allergies', $patient?->allergies) }}</textarea>
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-700">Photo du patient</label>
            @if($patient?->photo)
                <div class="mt-1 mb-2">
                    <img src="{{ Storage::url($patient->photo) }}" alt="Photo" class="h-20 w-20 rounded-full object-cover border border-slate-200">
                </div>
            @endif
            <input type="file" name="photo" accept="image/*" class="mt-1 text-sm text-slate-600">
            @error('photo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('patients.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
        <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Enregistrer</button>
    </div>
</form>
