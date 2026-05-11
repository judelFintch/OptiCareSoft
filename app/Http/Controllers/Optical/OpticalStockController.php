<?php

namespace App\Http\Controllers\Optical;

use App\Http\Controllers\Controller;
use App\Models\Frame;
use App\Models\Lens;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class OpticalStockController extends Controller
{
    public function index()
    {
        $this->authorize('stock.view');

        $lowFrames = Frame::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'reorder_level')
            ->orderBy('stock_quantity')
            ->get();

        $lowLenses = Lens::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'reorder_level')
            ->orderBy('stock_quantity')
            ->get();

        $frames = Frame::where('is_active', true)->orderBy('brand')->get();
        $lenses = Lens::where('is_active', true)->orderBy('brand')->get();

        return view('pages.optical.stock.index', compact('lowFrames', 'lowLenses', 'frames', 'lenses'));
    }

    public function storeFrame(Request $request)
    {
        $this->authorize('stock.manage');

        $validated = $request->validate([
            'frame_id'      => 'required|exists:frames,id',
            'movement_type' => 'required|in:in,out,adjustment',
            'quantity'      => 'required|integer|min:1',
            'notes'         => 'nullable|string|max:255',
        ]);

        /** @var Frame $frame */
        $frame       = Frame::findOrFail($validated['frame_id']);
        $stockBefore = $frame->stock_quantity;

        $stockAfter = match($validated['movement_type']) {
            'in'         => $stockBefore + $validated['quantity'],
            'out'        => max(0, $stockBefore - $validated['quantity']),
            'adjustment' => $validated['quantity'],
        };

        StockMovement::create([
            'stockable_type' => Frame::class,
            'stockable_id'   => $frame->id,
            'movement_type'  => $validated['movement_type'],
            'quantity'       => $validated['quantity'],
            'stock_before'   => $stockBefore,
            'stock_after'    => $stockAfter,
            'notes'          => $validated['notes'],
            'created_by'     => auth()->id(),
        ]);

        $frame->update(['stock_quantity' => $stockAfter]);

        return back()->with('success', 'Stock monture mis à jour.');
    }

    public function storeLens(Request $request)
    {
        $this->authorize('stock.manage');

        $validated = $request->validate([
            'lens_id'       => 'required|exists:lenses,id',
            'movement_type' => 'required|in:in,out,adjustment',
            'quantity'      => 'required|integer|min:1',
            'notes'         => 'nullable|string|max:255',
        ]);

        /** @var Lens $lens */
        $lens        = Lens::findOrFail($validated['lens_id']);
        $stockBefore = $lens->stock_quantity;

        $stockAfter = match($validated['movement_type']) {
            'in'         => $stockBefore + $validated['quantity'],
            'out'        => max(0, $stockBefore - $validated['quantity']),
            'adjustment' => $validated['quantity'],
        };

        StockMovement::create([
            'stockable_type' => Lens::class,
            'stockable_id'   => $lens->id,
            'movement_type'  => $validated['movement_type'],
            'quantity'       => $validated['quantity'],
            'stock_before'   => $stockBefore,
            'stock_after'    => $stockAfter,
            'notes'          => $validated['notes'],
            'created_by'     => auth()->id(),
        ]);

        $lens->update(['stock_quantity' => $stockAfter]);

        return back()->with('success', 'Stock verre mis à jour.');
    }
}
