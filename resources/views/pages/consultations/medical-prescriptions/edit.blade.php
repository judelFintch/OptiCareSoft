<x-opticare-layout>
    <x-slot:pageTitle>Modifier l'ordonnance médicale</x-slot:pageTitle>

    @include('pages.consultations.medical-prescriptions.partials.form', [
        'prescription' => $prescription,
        'consultation' => $prescription->consultation,
        'action' => route('medical-prescriptions.update', $prescription),
        'method' => 'PUT',
    ])
</x-opticare-layout>
