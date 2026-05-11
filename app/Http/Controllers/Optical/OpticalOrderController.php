<?php

namespace App\Http\Controllers\Optical;

use App\Http\Controllers\Controller;
use App\Enums\OpticalOrderStatus;
use App\Models\Frame;
use App\Models\Lens;
use App\Models\OpticalOrder;
use App\Models\Setting;
use App\Models\OpticalPrescription;
use App\Models\Patient;
use App\Models\Supplier;
use App\Models\User;
use App\Services\OpticalOrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OpticalOrderController extends Controller
{
    public function __construct(private OpticalOrderService $service) {}

    public function index()
    {
        $this->authorize('optical_orders.view');

        $orders = OpticalOrder::with(['patient', 'frame', 'creator'])
            ->when(request('search'), fn ($q, $s) =>
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhereHas('patient', fn ($p) => $p->search($s))
            )
            ->when(request('status'), fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('pages.optical.orders.index', compact('orders'));
    }

    public function create()
    {
        $this->authorize('optical_orders.manage');

        $patients      = Patient::active()->orderBy('last_name')->get();
        $frames        = Frame::where('is_active', true)->orderBy('brand')->get();
        $lenses        = Lens::where('is_active', true)->orderBy('brand')->get();
        $suppliers     = Supplier::orderBy('name')->get();
        $doctors       = User::role('Ophthalmologist')->orderBy('name')->get();
        $prescriptions = collect();

        return view('pages.optical.orders.create', compact(
            'patients', 'frames', 'lenses', 'suppliers', 'doctors', 'prescriptions'
        ));
    }

    public function store(Request $request)
    {
        $this->authorize('optical_orders.manage');

        $validated = $request->validate([
            'patient_id'               => 'required|exists:patients,id',
            'optical_prescription_id'  => 'nullable|exists:optical_prescriptions,id',
            'frame_id'                 => 'nullable|exists:frames,id',
            'right_lens_id'            => 'nullable|exists:lenses,id',
            'left_lens_id'             => 'nullable|exists:lenses,id',
            'right_sphere'             => 'nullable|numeric',
            'right_cylinder'           => 'nullable|numeric',
            'right_axis'               => 'nullable|integer|min:0|max:180',
            'right_addition'           => 'nullable|numeric',
            'left_sphere'              => 'nullable|numeric',
            'left_cylinder'            => 'nullable|numeric',
            'left_axis'                => 'nullable|integer|min:0|max:180',
            'left_addition'            => 'nullable|numeric',
            'pupillary_distance'       => 'nullable|numeric|min:40|max:80',
            'special_instructions'     => 'nullable|string',
            'price_frame'              => 'required|numeric|min:0',
            'price_lenses'             => 'required|numeric|min:0',
            'deposit_paid'             => 'required|numeric|min:0',
            'supplier_id'              => 'nullable|exists:suppliers,id',
            'assigned_to'              => 'nullable|exists:users,id',
            'expected_date'            => 'nullable|date|after:today',
            'notes'                    => 'nullable|string',
        ]);

        $order = $this->service->createOrder($validated, $request->user());

        return redirect()->route('optical.orders.show', $order)
            ->with('success', "Commande {$order->order_number} créée.");
    }

    public function show(OpticalOrder $order)
    {
        $this->authorize('optical_orders.view');
        $order->load(['patient', 'frame', 'rightLens', 'leftLens', 'supplier', 'assignedTo', 'creator', 'prescription']);
        return view('pages.optical.orders.show', compact('order'));
    }

    public function edit(OpticalOrder $order)
    {
        $this->authorize('optical_orders.manage');

        $patients  = Patient::active()->orderBy('last_name')->get();
        $frames    = Frame::where('is_active', true)->orderBy('brand')->get();
        $lenses    = Lens::where('is_active', true)->orderBy('brand')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $doctors   = User::role('Ophthalmologist')->orderBy('name')->get();

        return view('pages.optical.orders.edit', compact('order', 'patients', 'frames', 'lenses', 'suppliers', 'doctors'));
    }

    public function update(Request $request, OpticalOrder $order)
    {
        $this->authorize('optical_orders.manage');

        if (in_array($order->status, [OpticalOrderStatus::Delivered, OpticalOrderStatus::Cancelled])) {
            return back()->with('error', 'Impossible de modifier une commande livrée ou annulée.');
        }

        $validated = $request->validate([
            'frame_id'             => 'nullable|exists:frames,id',
            'right_lens_id'        => 'nullable|exists:lenses,id',
            'left_lens_id'         => 'nullable|exists:lenses,id',
            'right_sphere'         => 'nullable|numeric',
            'right_cylinder'       => 'nullable|numeric',
            'right_axis'           => 'nullable|integer|min:0|max:180',
            'right_addition'       => 'nullable|numeric',
            'left_sphere'          => 'nullable|numeric',
            'left_cylinder'        => 'nullable|numeric',
            'left_axis'            => 'nullable|integer|min:0|max:180',
            'left_addition'        => 'nullable|numeric',
            'pupillary_distance'   => 'nullable|numeric|min:40|max:80',
            'special_instructions' => 'nullable|string',
            'price_frame'          => 'required|numeric|min:0',
            'price_lenses'         => 'required|numeric|min:0',
            'supplier_id'          => 'nullable|exists:suppliers,id',
            'assigned_to'          => 'nullable|exists:users,id',
            'expected_date'        => 'nullable|date',
            'notes'                => 'nullable|string',
        ]);

        $total     = ($validated['price_frame'] ?? 0) + ($validated['price_lenses'] ?? 0);
        $remaining = max(0, $total - $order->deposit_paid);

        $order->update(array_merge($validated, [
            'total_price'      => $total,
            'remaining_amount' => $remaining,
        ]));

        return redirect()->route('optical.orders.show', $order)
            ->with('success', 'Commande mise à jour.');
    }

    public function destroy(OpticalOrder $order)
    {
        $this->authorize('optical_orders.manage');

        if ($order->status === OpticalOrderStatus::Delivered) {
            return back()->with('error', 'Impossible de supprimer une commande livrée.');
        }

        if ($order->frame_id && $order->status !== OpticalOrderStatus::Cancelled) {
            Frame::where('id', $order->frame_id)->increment('stock_quantity');
        }

        $order->delete();

        return redirect()->route('optical.orders.index')
            ->with('success', 'Commande supprimée.');
    }

    public function addDeposit(Request $request, OpticalOrder $order)
    {
        $this->authorize('optical_orders.manage');

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $order->remaining_amount,
        ]);

        $this->service->addDeposit($order, $validated['amount']);

        return back()->with('success', 'Acompte de ' . number_format($validated['amount'], 2) . ' enregistré.');
    }

    public function pdf(OpticalOrder $order)
    {
        $this->authorize('optical_orders.view');

        $order->load(['patient', 'frame', 'rightLens', 'leftLens', 'supplier', 'creator']);
        $settings = [
            'clinic_name'   => Setting::get('clinic_name', 'OptiCare Soft'),
            'clinic_slogan' => Setting::get('clinic_slogan', ''),
            'clinic_address'=> Setting::get('clinic_address', ''),
            'clinic_phone'  => Setting::get('clinic_phone', ''),
            'clinic_email'  => Setting::get('clinic_email', ''),
        ];

        return Pdf::loadView('pdf.optical-order', compact('order', 'settings'))
            ->setPaper('a4')
            ->stream('commande-' . $order->order_number . '.pdf');
    }

    public function deliver(OpticalOrder $order)
    {
        $this->authorize('optical_orders.manage');

        if ($order->status === OpticalOrderStatus::Delivered) {
            return back()->with('error', 'Commande déjà livrée.');
        }

        $this->service->deliver($order);

        return back()->with('success', 'Commande marquée comme livrée.');
    }
}
