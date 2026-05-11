<x-opticare-layout>
    <x-slot:pageTitle>Ordonnance médicale</x-slot:pageTitle>

    @include('pages.consultations.medical-prescriptions.partials.form', [
        'prescription' => null,
        'consultation' => $consultation,
        'action' => route('consultations.medical-prescriptions.store', $consultation),
        'method' => 'POST',
    ])
</x-opticare-layout>
