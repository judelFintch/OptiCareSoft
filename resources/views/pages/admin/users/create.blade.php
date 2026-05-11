<x-opticare-layout>
    <x-slot:pageTitle>Nouvel utilisateur</x-slot:pageTitle>
    @include('pages.admin.users.partials.form', ['user' => null, 'action' => route('admin.users.store'), 'method' => 'POST'])
</x-opticare-layout>
