<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\PharmacyProduct;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = PharmacyProduct::orderBy('name')->paginate(25);
        return view('pages.pharmacy.products.index', compact('products'));
    }

    public function create()
    {
        $suppliers = Supplier::where('category', 'pharmacy')->orderBy('name')->get();
        return view('pages.pharmacy.products.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reference'                => 'required|string|max:30|unique:pharmacy_products,reference',
            'name'                     => 'required|string|max:150',
            'generic_name'             => 'nullable|string|max:150',
            'category'                 => 'required|string',
            'form'                     => 'required|string',
            'dosage'                   => 'nullable|string|max:50',
            'manufacturer'             => 'nullable|string|max:100',
            'purchase_price'           => 'required|numeric|min:0',
            'selling_price'            => 'required|numeric|min:0',
            'stock_quantity'           => 'required|integer|min:0',
            'reorder_level'            => 'nullable|integer|min:0',
            'expiry_date'              => 'nullable|date|after:today',
            'is_prescription_required' => 'boolean',
            'supplier_id'              => 'nullable|exists:suppliers,id',
        ]);

        $data['is_prescription_required'] = (bool) ($data['is_prescription_required'] ?? false);
        $data['is_active'] = true;

        PharmacyProduct::create($data);

        return redirect()->route('pharmacy.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function show(PharmacyProduct $product)
    {
        $product->load(['supplier', 'stockMovements.creator']);
        $movements = $product->stockMovements()->latest()->take(20)->get();
        return view('pages.pharmacy.products.show', compact('product', 'movements'));
    }

    public function edit(PharmacyProduct $product)
    {
        $suppliers = Supplier::where('category', 'pharmacy')->orderBy('name')->get();
        return view('pages.pharmacy.products.edit', compact('product', 'suppliers'));
    }

    public function update(Request $request, PharmacyProduct $product)
    {
        $data = $request->validate([
            'reference'                => 'required|string|max:30|unique:pharmacy_products,reference,' . $product->id,
            'name'                     => 'required|string|max:150',
            'generic_name'             => 'nullable|string|max:150',
            'category'                 => 'required|string',
            'form'                     => 'required|string',
            'dosage'                   => 'nullable|string|max:50',
            'manufacturer'             => 'nullable|string|max:100',
            'purchase_price'           => 'required|numeric|min:0',
            'selling_price'            => 'required|numeric|min:0',
            'reorder_level'            => 'nullable|integer|min:0',
            'expiry_date'              => 'nullable|date',
            'is_prescription_required' => 'boolean',
            'supplier_id'              => 'nullable|exists:suppliers,id',
        ]);

        $data['is_prescription_required'] = (bool) ($data['is_prescription_required'] ?? false);

        $product->update($data);

        return redirect()->route('pharmacy.products.show', $product)
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(PharmacyProduct $product)
    {
        $product->delete();
        return redirect()->route('pharmacy.products.index')
            ->with('success', 'Produit supprimé.');
    }
}
