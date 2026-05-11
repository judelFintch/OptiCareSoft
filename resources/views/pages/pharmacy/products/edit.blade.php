<x-opticare-layout>
    <x-slot:pageTitle>Modifier — {{ $product->name }}</x-slot:pageTitle>

    @include('pages.pharmacy.products.partials.form', [
        'product'   => $product,
        'action'    => route('pharmacy.products.update', $product),
        'method'    => 'PUT',
        'suppliers' => $suppliers,
    ])
</x-opticare-layout>
