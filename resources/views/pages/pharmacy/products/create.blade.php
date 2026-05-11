<x-opticare-layout>
    <x-slot:pageTitle>Nouveau produit</x-slot:pageTitle>

    @include('pages.pharmacy.products.partials.form', [
        'product'   => null,
        'action'    => route('pharmacy.products.store'),
        'method'    => 'POST',
        'suppliers' => $suppliers,
    ])
</x-opticare-layout>
