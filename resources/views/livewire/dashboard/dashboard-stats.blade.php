<div>
    {{-- Stats cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Patients aujourd'hui</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['patients_today'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-3">{{ $stats['waiting_count'] }} en attente</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Consultations</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['consultations_today'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-3">{{ $stats['appointments_today'] }} RDV aujourd'hui</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Recettes du jour</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ number_format($stats['revenue_today'], 0, ',', ' ') }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-xs text-red-400 mt-3">{{ number_format($stats['total_unpaid'], 0, ',', ' ') }} FC impayés</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Optique en cours</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['pending_orders'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-3">{{ number_format($stats['total_patients'], 0, ',', ' ') }} patients au total</p>
        </div>
    </div>

    {{-- Two columns: appointments + queue --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Today appointments --}}
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-700">Rendez-vous du jour</h3>
                <a href="{{ route('appointments.index') }}" class="text-xs text-blue-600 hover:underline">Voir tout</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($todayAppointments as $appt)
                <div class="flex items-center gap-3 px-6 py-3">
                    <div class="text-center w-12 flex-shrink-0">
                        <p class="text-sm font-bold text-slate-700">{{ $appt['time'] }}</p>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $appt['patient'] }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $appt['reason'] }} &mdash; {{ $appt['doctor'] }}</p>
                    </div>
                    <span class="badge bg-{{ $appt['color'] }}-100 text-{{ $appt['color'] }}-700">{{ $appt['status'] }}</span>
                </div>
                @empty
                <div class="px-6 py-8 text-center">
                    <p class="text-sm text-slate-400">Aucun rendez-vous aujourd'hui</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Waiting queue --}}
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-700">File d'attente</h3>
                <a href="{{ route('reception.index') }}" class="text-xs text-blue-600 hover:underline">Gérer</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($waitingQueue as $i => $visit)
                <div class="flex items-center gap-3 px-6 py-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-blue-700">{{ $i + 1 }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $visit['patient'] }}</p>
                        <p class="text-xs text-slate-400">{{ $visit['code'] }} &mdash; {{ $visit['since'] }}</p>
                    </div>
                    <span class="badge bg-{{ $visit['color'] }}-100 text-{{ $visit['color'] }}-700">{{ $visit['status'] }}</span>
                </div>
                @empty
                <div class="px-6 py-8 text-center">
                    <p class="text-sm text-slate-400">File d'attente vide</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
