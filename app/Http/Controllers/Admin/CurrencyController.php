<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $this->authorize('settings.manage');
        $currencies = Currency::latest()->paginate(20);
        return view('pages.admin.currencies.index', compact('currencies'));
    }

    public function create()
    {
        $this->authorize('settings.manage');
        return view('pages.admin.currencies.create');
    }

    public function store(Request $request)
    {
        $this->authorize('settings.manage');

        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'code'          => 'required|string|max:10|unique:currencies,code',
            'symbol'        => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0.000001',
        ]);

        if ($request->boolean('is_default')) {
            Currency::where('is_default', true)->update(['is_default' => false]);
        }

        $validated['is_default'] = $request->boolean('is_default');
        $validated['is_active']  = $request->boolean('is_active', true);
        Currency::create($validated);

        return redirect()->route('admin.currencies.index')
            ->with('success', 'Devise créée.');
    }

    public function edit(Currency $currency)
    {
        $this->authorize('settings.manage');
        return view('pages.admin.currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $this->authorize('settings.manage');

        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'code'          => 'required|string|max:10|unique:currencies,code,' . $currency->id,
            'symbol'        => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0.000001',
        ]);

        if ($request->boolean('is_default')) {
            Currency::where('id', '!=', $currency->id)->update(['is_default' => false]);
        }

        $validated['is_default'] = $request->boolean('is_default');
        $validated['is_active']  = $request->boolean('is_active', true);
        $currency->update($validated);

        return redirect()->route('admin.currencies.index')
            ->with('success', 'Devise mise à jour.');
    }
}
