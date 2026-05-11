<x-opticare-layout>
    <x-slot:pageTitle>Nouveau rendez-vous</x-slot:pageTitle>

    @include('pages.appointments.partials.form', [
        'appointment' => null,
        'action' => route('appointments.store'),
        'method' => 'POST',
    ])
</x-opticare-layout>
