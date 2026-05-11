<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\PharmacyProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = PharmacyProduct::orderBy('name')->paginate(25);
        return view('pages.pharmacy.products.index', compact('products'));
    }

    public function create() { return view('pages.pharmacy.products.create'); }
    public function store(Request $request) { return redirect()->route('pharmacy.products.index'); }
    public function show(PharmacyProduct $product) { return view('pages.pharmacy.products.show', compact('product')); }
    public function edit(PharmacyProduct $product) { return view('pages.pharmacy.products.edit', compact('product')); }
    public function update(Request $request, PharmacyProduct $product) { return back(); }
    public function destroy(PharmacyProduct $product) { return back(); }
}
