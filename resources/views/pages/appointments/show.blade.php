<x-opticare-layout>
    <x-slot:pageTitle>Rendez-vous</x-slot:pageTitle>

    <div class="space-y-6">
        <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">{{ $appointment->patient?->full_name }}</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $appointment->appointment_date?->format('d/m/Y') }} à {{ $appointment->appointment_time }} avec {{ $appointment->doctor?->name }}</p>
                    <p class="mt-3 text-sm text-slate-700">{{ $appointment->reason }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @can('appointments.confirm')
                        <form method="POST" action="{{ route('appointments.confirm', $appointment) }}">@csrf @method('PATCH')
                            <button class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Confirmer</button>
                        </form>
                    @endcan
                    @can('appointments.cancel')
                        <form method="POST" action="{{ route('appointments.cancel', $appointment) }}">@csrf @method('PATCH')
                            <button class="rounded-md border border-red-200 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50">Annuler</button>
                        </form>
                    @endcan
                    @can('appointments.edit')
                        <a href="{{ route('appointments.edit', $appointment) }}" class="rounded-md bg-[#0f4c81] px-4 py-2 text-sm font-medium text-white hover:bg-[#0b3f6d]">Modifier</a>
                    @endcan
                </div>
            </div>
        </section>
    </div>
</x-opticare-layout>
