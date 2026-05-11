<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $this->authorize('settings.manage');
        $services = Service::with('currency')->latest()->paginate(20);
        return view('pages.admin.services.index', compact('services'));
    }

    public function create()
    {
        $this->authorize('settings.manage');
        $currencies = Currency::where('is_active', true)->get();
        return view('pages.admin.services.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $this->authorize('settings.manage');

        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'code'          => 'required|string|max:50|unique:services,code',
            'description'   => 'nullable|string',
            'category'      => 'required|in:consultation,exam,optical,pharmacy,general',
            'default_price' => 'required|numeric|min:0',
            'currency_id'   => 'required|exists:currencies,id',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service créé avec succès.');
    }

    public function edit(Service $service)
    {
        $this->authorize('settings.manage');
        $currencies = Currency::where('is_active', true)->get();
        return view('pages.admin.services.edit', compact('service', 'currencies'));
    }

    public function update(Request $request, Service $service)
    {
        $this->authorize('settings.manage');

        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'code'          => 'required|string|max:50|unique:services,code,' . $service->id,
            'description'   => 'nullable|string',
            'category'      => 'required|in:consultation,exam,optical,pharmacy,general',
            'default_price' => 'required|numeric|min:0',
            'currency_id'   => 'required|exists:currencies,id',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service mis à jour.');
    }

    public function destroy(Service $service)
    {
        $this->authorize('settings.manage');
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service supprimé.');
    }
}
