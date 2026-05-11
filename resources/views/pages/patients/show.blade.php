<x-opticare-layout>
    <x-slot:pageTitle>{{ $patient->full_name }}</x-slot:pageTitle>

    <div class="space-y-6">
        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-center gap-4">
                    @if($patient->photo)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($patient->photo) }}" alt="Photo"
                             class="h-16 w-16 rounded-full object-cover border-2 border-[#0f4c81] shrink-0">
                    @else
                        <div class="h-16 w-16 rounded-full bg-[#0f4c81] flex items-center justify-center text-white text-xl font-bold shrink-0">
                            {{ mb_strtoupper(mb_substr($patient->first_name, 0, 1) . mb_substr($patient->last_name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-[#0f4c81]">{{ $patient->patient_code }}</p>
                        <h2 class="mt-0.5 text-xl font-semibold text-slate-900">{{ $patient->full_name }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $patient->phone ?: 'Téléphone non renseigné' }} · {{ $patient->age ? $patient->age . ' ans' : 'Age non renseigné' }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('patients.pdf', $patient) }}" target="_blank"
                       class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        PDF fiche
                    </a>
                    @can('patients.edit')
                        <a href="{{ route('patients.edit', $patient) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Modifier</a>
                    @endcan
                    @can('visits.create')
                        <form method="POST" action="{{ route('reception.open-visit') }}">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                            <button class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Ouvrir visite</button>
                        </form>
                    @endcan
                </div>
            </div>
        </section>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                <h3 class="text-base font-semibold text-slate-900">Informations médicales</h3>
                <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                    <div><dt class="font-medium text-slate-500">Antécédents médicaux</dt><dd class="mt-1 text-slate-900">{{ $patient->medical_history ?: '—' }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Antécédents ophtalmologiques</dt><dd class="mt-1 text-slate-900">{{ $patient->ophthalmic_history ?: '—' }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Allergies</dt><dd class="mt-1 text-slate-900">{{ $patient->allergies ?: '—' }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Contact urgence</dt><dd class="mt-1 text-slate-900">{{ $patient->emergency_contact_name ?: '—' }} {{ $patient->emergency_contact_phone }}</dd></div>
                </dl>
            </section>

            <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900">Résumé financier</h3>
                <p class="mt-4 text-2xl font-semibold text-slate-900">{{ number_format((float) $patient->total_debt, 2) }}</p>
                <p class="text-sm text-slate-500">Dette actuelle</p>
            </section>
        </div>

        {{-- Consultations --}}
        @if($patient->consultations->count())
        <section class="rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-6 py-4">
                <h3 class="text-base font-semibold text-slate-900">Consultations ({{ $patient->consultations->count() }})</h3>
                @can('consultations.create')
                    <a href="{{ route('consultations.create', ['patient_id' => $patient->id]) }}"
                       class="rounded-lg bg-[#0f4c81] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#0b3f6d]">
                        + Nouvelle
                    </a>
                @endcan
            </div>
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="text-xs font-semibold uppercase text-slate-500 bg-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Code</th>
                        <th class="px-4 py-3 text-left">Médecin</th>
                        <th class="px-4 py-3 text-left">Diagnostic</th>
                        <th class="px-4 py-3 text-left">Statut</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($patient->consultations->take(10) as $c)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-slate-500 text-xs">{{ $c->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $c->consultation_code }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $c->doctor?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $c->primary_diagnosis ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php $cs = $c->status instanceof \App\Enums\ConsultationStatus ? $c->status->value : $c->status; @endphp
                                <span class="inline-block rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ in_array($cs, ['signed','completed']) ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $cs ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('consultations.show', $c) }}" class="text-xs font-medium text-[#0f4c81] hover:underline">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
        @endif

        {{-- Ordonnances optiques --}}
        @if($patient->opticalPrescriptions->count())
        <section class="rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <h3 class="text-base font-semibold text-slate-900">Ordonnances optiques ({{ $patient->opticalPrescriptions->count() }})</h3>
            </div>
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="text-xs font-semibold uppercase text-slate-500 bg-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-center">OD Sph</th>
                        <th class="px-4 py-3 text-center">OD Cyl</th>
                        <th class="px-4 py-3 text-center">OG Sph</th>
                        <th class="px-4 py-3 text-center">OG Cyl</th>
                        <th class="px-4 py-3 text-center">DP</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($patient->opticalPrescriptions->take(5) as $rx)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-slate-500 text-xs">{{ $rx->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-center text-slate-700">{{ $rx->right_sphere ?? '—' }}</td>
                            <td class="px-4 py-3 text-center text-slate-700">{{ $rx->right_cylinder ?? '—' }}</td>
                            <td class="px-4 py-3 text-center text-slate-700">{{ $rx->left_sphere ?? '—' }}</td>
                            <td class="px-4 py-3 text-center text-slate-700">{{ $rx->left_cylinder ?? '—' }}</td>
                            <td class="px-4 py-3 text-center text-slate-700">{{ $rx->pupillary_distance ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('optical-prescriptions.show', $rx) }}" class="text-xs font-medium text-[#0f4c81] hover:underline">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
        @endif

        {{-- Documents --}}
        <section class="rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-6 py-4">
                <h3 class="text-base font-semibold text-slate-900">Documents ({{ $patient->documents->count() }})</h3>
                @can('patients.edit')
                    <button onclick="document.getElementById('upload-form').classList.toggle('hidden')"
                            class="rounded-lg bg-[#0f4c81] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#0b3f6d]">
                        + Ajouter
                    </button>
                @endcan
            </div>

            @can('patients.edit')
                <div id="upload-form" class="hidden border-b border-slate-100 bg-slate-50 px-6 py-4">
                    <form method="POST" action="{{ route('patients.documents.store', $patient) }}"
                          enctype="multipart/form-data" class="flex flex-wrap items-end gap-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Fichier <span class="text-red-500">*</span></label>
                            <input type="file" name="file" required class="text-sm text-slate-600">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Nom</label>
                            <input type="text" name="name" placeholder="Nom du document"
                                   class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Catégorie</label>
                            <select name="category" class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                                <option value="">—</option>
                                <option value="ordonnance">Ordonnance</option>
                                <option value="bilan">Bilan / Examen</option>
                                <option value="compte_rendu">Compte rendu</option>
                                <option value="assurance">Assurance</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <button class="rounded-lg bg-[#0f4c81] px-4 py-2 text-sm font-semibold text-white hover:bg-[#0b3f6d]">
                            Téléverser
                        </button>
                    </form>
                </div>
            @endcan

            @if($patient->documents->count())
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="text-xs font-semibold uppercase text-slate-500 bg-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Nom</th>
                            <th class="px-4 py-3 text-left">Catégorie</th>
                            <th class="px-4 py-3 text-left">Taille</th>
                            <th class="px-4 py-3 text-left">Ajouté le</th>
                            <th class="px-4 py-3 text-left">Par</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($patient->documents as $doc)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $doc->name }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $doc->category ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-400 text-xs">{{ $doc->file_size_formatted }}</td>
                                <td class="px-4 py-3 text-slate-400 text-xs">{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-slate-500 text-xs">{{ $doc->uploader?->name ?? '—' }}</td>
                                <td class="px-4 py-3 flex items-center gap-2 justify-end">
                                    <a href="{{ route('patients.documents.download', [$patient, $doc]) }}"
                                       class="text-[#0f4c81] hover:underline text-xs font-medium">Télécharger</a>
                                    @can('patients.edit')
                                        <form method="POST" action="{{ route('patients.documents.destroy', [$patient, $doc]) }}"
                                              onsubmit="return confirm('Supprimer ce document ?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 hover:text-red-700 text-xs">Supprimer</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="px-6 py-8 text-center text-sm text-slate-400">Aucun document pour ce patient.</p>
            @endif
        </section>
    </div>
</x-opticare-layout>
