<x-opticare-layout>
    <x-slot:pageTitle>Journal d'activité</x-slot:pageTitle>

    {{-- Filtres --}}
    <form method="GET" class="mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Utilisateur</label>
            <input type="text" name="user" value="{{ request('user') }}" placeholder="Nom ou email…"
                   class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Action</label>
            <select name="event" class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
                <option value="">Toutes</option>
                <option value="created"  {{ request('event')==='created'  ? 'selected':'' }}>Créé</option>
                <option value="updated"  {{ request('event')==='updated'  ? 'selected':'' }}>Modifié</option>
                <option value="deleted"  {{ request('event')==='deleted'  ? 'selected':'' }}>Supprimé</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Modèle</label>
            <input type="text" name="subject" value="{{ request('subject') }}" placeholder="Patient, Invoice…"
                   class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Du</label>
            <input type="date" name="from" value="{{ request('from') }}"
                   class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Au</label>
            <input type="date" name="to" value="{{ request('to') }}"
                   class="rounded-lg border-slate-300 text-sm shadow-sm focus:ring-[#0f4c81]">
        </div>
        <div class="flex items-end gap-2">
            <button class="rounded-lg bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">
                Filtrer
            </button>
            <a href="{{ route('admin.activity-log') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                Réinitialiser
            </a>
        </div>
    </form>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Utilisateur</th>
                    <th class="px-4 py-3 text-left">Action</th>
                    <th class="px-4 py-3 text-left">Sujet</th>
                    <th class="px-4 py-3 text-left">Modifications</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($activities as $activity)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-500 whitespace-nowrap">
                            {{ $activity->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($activity->causer)
                                <div class="font-medium text-slate-800">{{ $activity->causer->name }}</div>
                                <div class="text-xs text-slate-400">{{ $activity->causer->email }}</div>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $eventColors = [
                                    'created' => 'bg-green-100 text-green-700',
                                    'updated' => 'bg-blue-100 text-blue-700',
                                    'deleted' => 'bg-red-100 text-red-700',
                                ];
                                $eventLabels = [
                                    'created' => 'Créé',
                                    'updated' => 'Modifié',
                                    'deleted' => 'Supprimé',
                                ];
                                $ev = $activity->event ?? 'updated';
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $eventColors[$ev] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $eventLabels[$ev] ?? $ev }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($activity->subject)
                                <div class="font-medium text-slate-800">
                                    {{ class_basename($activity->subject_type) }}
                                </div>
                                <div class="text-xs text-slate-400 font-mono">
                                    #{{ $activity->subject_id }}
                                    @if(method_exists($activity->subject, 'getActivityLogIdentifier'))
                                        — {{ $activity->subject->getActivityLogIdentifier() }}
                                    @elseif(isset($activity->subject->full_name))
                                        — {{ $activity->subject->full_name }}
                                    @elseif(isset($activity->subject->name))
                                        — {{ $activity->subject->name }}
                                    @elseif(isset($activity->subject->order_number))
                                        — {{ $activity->subject->order_number }}
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-400 text-xs">{{ class_basename($activity->subject_type ?? '') }} #{{ $activity->subject_id }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 max-w-xs">
                            @php $changes = $activity->properties['attributes'] ?? []; @endphp
                            @if(count($changes))
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_keys($changes) as $field)
                                        <span class="inline-block rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-600">{{ $field }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-400">Aucune activité enregistrée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="border-t border-slate-100 px-4 py-3">
            {{ $activities->withQueryString()->links() }}
        </div>
    </div>
</x-opticare-layout>
