<x-opticare-layout>
    <x-slot:pageTitle>Prescription optique</x-slot:pageTitle>

    @include('pages.consultations.optical-prescriptions.partials.form', [
        'prescription' => null,
        'consultation' => $consultation,
        'action' => route('consultations.optical-prescriptions.store', $consultation),
        'method' => 'POST',
    ])
</x-opticare-layout>
