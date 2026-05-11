<?php

namespace App\Http\Controllers\Optical;

use App\Http\Controllers\Controller;
use App\Models\Frame;
use App\Models\Lens;
use App\Models\OpticalOrder;
use App\Models\Patient;
use Illuminate\Http\Request;

class OpticalOrderController extends Controller
{
    public function index()
    {
        $orders = OpticalOrder::with(['patient', 'frame'])->latest()->paginate(20);
        return view('pages.optical.orders.index', compact('orders'));
    }

    public function create()
    {
        $patients = Patient::active()->orderBy('last_name')->get();
        $frames   = Frame::where('is_active', true)->where('stock_quantity', '>', 0)->orderBy('brand')->get();
        $lenses   = Lens::where('is_active', true)->where('stock_quantity', '>', 0)->orderBy('brand')->get();
        return view('pages.optical.orders.create', compact('patients', 'frames', 'lenses'));
    }

    public function store(Request $request) { return redirect()->route('optical.orders.index'); }
    public function show(OpticalOrder $order) { return view('pages.optical.orders.show', compact('order')); }
    public function edit(OpticalOrder $order) { return view('pages.optical.orders.edit', compact('order')); }
    public function update(Request $request, OpticalOrder $order) { return back(); }
    public function destroy(OpticalOrder $order) { return back(); }
}
