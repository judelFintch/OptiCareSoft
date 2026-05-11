<form method="POST" action="{{ $action }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-slate-700">Patient</label>
            <select name="patient_id" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm" @disabled($appointment)>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}" @selected(old('patient_id', $appointment?->patient_id) == $patient->id)>{{ $patient->full_name }} · {{ $patient->patient_code }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Médecin</label>
            <select name="doctor_id" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm" @disabled($appointment)>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" @selected(old('doctor_id', $appointment?->doctor_id) == $doctor->id)>{{ $doctor->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Date</label>
            <input type="date" name="appointment_date" value="{{ old('appointment_date', $appointment?->appointment_date?->format('Y-m-d')) }}" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Heure</label>
            <input type="time" name="appointment_time" value="{{ old('appointment_time', $appointment?->appointment_time) }}" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Durée</label>
            <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $appointment?->duration_minutes ?? 30) }}" min="5" max="120" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Motif</label>
            <input name="reason" value="{{ old('reason', $appointment?->reason) }}" required class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">
        </div>
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-slate-700">Notes</label>
            <textarea name="notes" rows="3" class="mt-1 w-full rounded-md border-slate-300 text-sm shadow-sm">{{ old('notes', $appointment?->notes) }}</textarea>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('appointments.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuler</a>
        <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Enregistrer</button>
    </div>
</form>
