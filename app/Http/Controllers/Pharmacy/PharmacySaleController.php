<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PharmacyProduct;
use App\Models\PharmacySale;
use App\Models\PharmacySaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PharmacySaleController extends Controller
{
    public function index()
    {
        $this->authorize('pharmacy.view');

        $sales = PharmacySale::with(['patient', 'servedBy'])
            ->when(request('search'), function ($q, $term) {
                $q->where('sale_number', 'like', "%{$term}%")
                  ->orWhereHas('patient', fn($p) => $p->search($term));
            })
            ->when(request('status'), fn($q, $s) => $q->where('payment_status', $s))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('pages.pharmacy.sales.index', compact('sales'));
    }

    public function create()
    {
        $this->authorize('pharmacy.manage');

        $products = PharmacyProduct::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        $patients = Patient::active()->orderBy('last_name')->get();

        return view('pages.pharmacy.sales.create', compact('products', 'patients'));
    }

    public function store(Request $request)
    {
        $this->authorize('pharmacy.manage');

        $request->validate([
            'patient_id'              => 'nullable|exists:patients,id',
            'items'                   => 'required|array|min:1',
            'items.*.pharmacy_product_id' => 'required|exists:pharmacy_products,id',
            'items.*.quantity'        => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $saleNumber = 'PH-' . now()->format('Ymd') . '-' . str_pad(PharmacySale::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $total = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = PharmacyProduct::findOrFail($item['pharmacy_product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Stock insuffisant pour {$product->name}.");
                }

                $lineTotal = $product->selling_price * $item['quantity'];
                $total += $lineTotal;

                $itemsData[] = [
                    'pharmacy_product_id' => $product->id,
                    'quantity'            => $item['quantity'],
                    'unit_price'          => $product->selling_price,
                    'total'               => $lineTotal,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ];

                $product->decrement('stock_quantity', $item['quantity']);
            }

            $sale = PharmacySale::create([
                'sale_number'              => $saleNumber,
                'patient_id'               => $request->patient_id,
                'medical_prescription_id'  => $request->medical_prescription_id,
                'total_amount'             => $total,
                'payment_status'           => $request->payment_status ?? 'paid',
                'served_by'                => auth()->id(),
            ]);

            foreach ($itemsData as &$d) {
                $d['pharmacy_sale_id'] = $sale->id;
            }

            PharmacySaleItem::insert($itemsData);
        });

        return redirect()->route('pharmacy.sales.index')
            ->with('success', 'Vente enregistrée avec succès.');
    }

    public function show(PharmacySale $sale)
    {
        $this->authorize('pharmacy.view');
        $sale->load(['patient', 'items.product', 'servedBy', 'medicalPrescription']);
        return view('pages.pharmacy.sales.show', compact('sale'));
    }
}
