<x-opticare-layout>
    <x-slot:pageTitle>Modifier l'utilisateur</x-slot:pageTitle>
    @include('pages.admin.users.partials.form', ['user' => $user, 'action' => route('admin.users.update', $user), 'method' => 'PUT'])
</x-opticare-layout>
