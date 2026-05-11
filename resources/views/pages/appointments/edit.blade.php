<x-opticare-layout>
    <x-slot:pageTitle>Modifier le rendez-vous</x-slot:pageTitle>

    @include('pages.appointments.partials.form', [
        'appointment' => $appointment,
        'action' => route('appointments.update', $appointment),
        'method' => 'PUT',
    ])
</x-opticare-layout>
