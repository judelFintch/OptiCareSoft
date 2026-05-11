<x-opticare-layout>
    <x-slot:pageTitle>Modifier le patient</x-slot:pageTitle>

    @include('pages.patients.partials.form', [
        'patient' => $patient,
        'action' => route('patients.update', $patient),
        'method' => 'PUT',
    ])
</x-opticare-layout>
