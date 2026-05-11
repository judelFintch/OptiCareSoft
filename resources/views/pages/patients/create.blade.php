<x-opticare-layout>
    <x-slot:pageTitle>Nouveau patient</x-slot:pageTitle>

    @include('pages.patients.partials.form', [
        'patient' => null,
        'action' => route('patients.store'),
        'method' => 'POST',
    ])
</x-opticare-layout>
