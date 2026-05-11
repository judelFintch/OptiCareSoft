<?php

namespace App\Http\Controllers\Consultations;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\OpticalPrescription;
use App\Models\Setting;
use App\Services\OpticalPrescriptionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OpticalPrescriptionController extends Controller
{
    public function __construct(private OpticalPrescriptionService $prescriptionService) {}

    public function create(Consultation $consultation)
    {
        $this->authorize('optical_prescriptions.create');

        $consultation->load(['patient', 'doctor']);

        return view('pages.consultations.optical-prescriptions.create', compact('consultation'));
    }

    public function store(Request $request, Consultation $consultation)
    {
        $this->authorize('optical_prescriptions.create');

        $validated = $this->validatePrescription($request);
        $prescription = $this->prescriptionService->create($consultation, $request->user(), $validated);

        return redirect()
            ->route('consultations.show', $consultation)
            ->with('success', "Prescription optique {$prescription->prescription_number} créée.");
    }

    public function show(OpticalPrescription $prescription)
    {
        $this->authorize('optical_prescriptions.view');

        $prescription->load(['patient', 'doctor', 'consultation']);

        return view('pages.consultations.optical-prescriptions.show', compact('prescription'));
    }

    public function edit(OpticalPrescription $prescription)
    {
        $this->authorize('optical_prescriptions.create');

        $prescription->load(['patient', 'doctor', 'consultation']);

        return view('pages.consultations.optical-prescriptions.edit', compact('prescription'));
    }

    public function update(Request $request, OpticalPrescription $prescription)
    {
        $this->authorize('optical_prescriptions.create');

        $this->prescriptionService->update($prescription, $this->validatePrescription($request));

        return redirect()
            ->route('optical-prescriptions.show', $prescription)
            ->with('success', 'Prescription optique mise à jour.');
    }

    public function pdf(OpticalPrescription $prescription)
    {
        $this->authorize('optical_prescriptions.view');

        $prescription->load(['patient', 'doctor', 'consultation']);
        $settings = [
            'clinic_name' => Setting::get('clinic_name', 'OptiCare Soft'),
            'clinic_slogan' => Setting::get('clinic_slogan', 'La solution intelligente pour gérer votre cabinet ophtalmologique.'),
            'clinic_address' => Setting::get('clinic_address', ''),
            'clinic_phone' => Setting::get('clinic_phone', ''),
            'clinic_email' => Setting::get('clinic_email', ''),
            'prescription_note' => Setting::get('prescription_note', 'Ordonnance valable selon indication du médecin.'),
        ];

        return Pdf::loadView('pdf.optical-prescription', compact('prescription', 'settings'))
            ->setPaper('a4')
            ->stream($prescription->prescription_number . '.pdf');
    }

    private function validatePrescription(Request $request): array
    {
        return $request->validate([
            'right_sphere' => 'nullable|numeric|min:-30|max:30',
            'right_cylinder' => 'nullable|numeric|min:-15|max:15',
            'right_axis' => 'nullable|integer|min:0|max:180',
            'right_addition' => 'nullable|numeric|min:0|max:5',
            'left_sphere' => 'nullable|numeric|min:-30|max:30',
            'left_cylinder' => 'nullable|numeric|min:-15|max:15',
            'left_axis' => 'nullable|integer|min:0|max:180',
            'left_addition' => 'nullable|numeric|min:0|max:5',
            'pd_right' => 'nullable|numeric|min:20|max:45',
            'pd_left' => 'nullable|numeric|min:20|max:45',
            'lens_type' => 'nullable|in:unifocal,bifocal,progressive,degressive,contact_lens',
            'usage' => 'nullable|in:distance,near,mixed',
            'valid_until' => 'nullable|date|after:today',
            'remarks' => 'nullable|string|max:1000',
        ]);
    }
}
