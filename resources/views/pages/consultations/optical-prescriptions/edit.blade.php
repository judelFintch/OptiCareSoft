<x-opticare-layout>
    <x-slot:pageTitle>Modifier la prescription optique</x-slot:pageTitle>

    @include('pages.consultations.optical-prescriptions.partials.form', [
        'prescription' => $prescription,
        'consultation' => $prescription->consultation,
        'action' => route('optical-prescriptions.update', $prescription),
        'method' => 'PUT',
    ])
</x-opticare-layout>
