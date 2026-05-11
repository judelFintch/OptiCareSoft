<?php

namespace App\Http\Controllers\Optical;

use App\Http\Controllers\Controller;
use App\Models\OpticalOrder;
use App\Services\OpticalOrderService;
use Illuminate\Http\Request;

class OpticalController extends Controller
{
    public function index()
    {
        $this->authorize('optical_orders.view');
        return view('pages.optical.index');
    }

    public function updateStatus(Request $request, OpticalOrder $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,ordered,in_production,ready,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Statut de la commande mis à jour.');
    }
}
