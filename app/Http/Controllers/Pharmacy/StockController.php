<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Enums\StockMovementType;
use App\Models\PharmacyProduct;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $this->authorize('stock.view');

        $lowStock = PharmacyProduct::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'reorder_level')
            ->orderBy('stock_quantity')
            ->get();

        $expiringSoon = PharmacyProduct::where('is_active', true)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->orderBy('expiry_date')
            ->get();

        $movements = StockMovement::with(['stockable', 'creator'])
            ->where('stockable_type', PharmacyProduct::class)
            ->latest()
            ->paginate(20);

        $products = PharmacyProduct::where('is_active', true)->orderBy('name')->get();

        return view('pages.pharmacy.stock.index', compact('lowStock', 'expiringSoon', 'movements', 'products'));
    }

    public function store(Request $request)
    {
        $this->authorize('stock.manage');

        $validated = $request->validate([
            'pharmacy_product_id' => 'required|exists:pharmacy_products,id',
            'movement_type'       => 'required|in:in,out,adjustment,loss,return',
            'quantity'            => 'required|integer|min:1',
            'unit_cost'           => 'nullable|numeric|min:0',
            'reference'           => 'nullable|string|max:100',
            'notes'               => 'nullable|string|max:255',
        ]);

        /** @var PharmacyProduct $product */
        $product     = PharmacyProduct::findOrFail($validated['pharmacy_product_id']);
        $type        = StockMovementType::from($validated['movement_type']);
        $stockBefore = $product->stock_quantity;

        if ($type === StockMovementType::Out || $type === StockMovementType::Loss) {
            if ($product->stock_quantity < $validated['quantity']) {
                return back()->with('error', "Stock insuffisant. Disponible : {$product->stock_quantity}");
            }
            $stockAfter = $stockBefore - $validated['quantity'];
        } elseif ($type === StockMovementType::Adjustment) {
            $stockAfter = $validated['quantity'];
        } else {
            $stockAfter = $stockBefore + $validated['quantity'];
        }

        StockMovement::create([
            'stockable_type'  => PharmacyProduct::class,
            'stockable_id'    => $product->id,
            'movement_type'   => $type->value,
            'quantity'        => $validated['quantity'],
            'stock_before'    => $stockBefore,
            'stock_after'     => $stockAfter,
            'unit_cost'       => $validated['unit_cost'],
            'reference'       => $validated['reference'],
            'notes'           => $validated['notes'],
            'created_by'      => auth()->id(),
        ]);

        $product->update(['stock_quantity' => $stockAfter]);

        return redirect()->route('pharmacy.stock.index')
            ->with('success', 'Mouvement de stock enregistré.');
    }
}
