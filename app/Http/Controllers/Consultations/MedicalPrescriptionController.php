<?php

namespace App\Http\Controllers\Consultations;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\MedicalPrescription;
use App\Models\Setting;
use App\Services\MedicalPrescriptionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MedicalPrescriptionController extends Controller
{
    public function __construct(private MedicalPrescriptionService $prescriptionService) {}

    public function create(Consultation $consultation)
    {
        $this->authorize('medical_prescriptions.create');

        $consultation->load(['patient', 'doctor']);

        return view('pages.consultations.medical-prescriptions.create', compact('consultation'));
    }

    public function store(Request $request, Consultation $consultation)
    {
        $this->authorize('medical_prescriptions.create');

        $prescription = $this->prescriptionService->create($consultation, $request->user(), $this->validatePrescription($request));

        return redirect()
            ->route('consultations.show', $consultation)
            ->with('success', "Ordonnance médicale {$prescription->prescription_number} créée.");
    }

    public function show(MedicalPrescription $prescription)
    {
        $this->authorize('medical_prescriptions.view');

        $prescription->load(['patient', 'doctor', 'consultation', 'items']);

        return view('pages.consultations.medical-prescriptions.show', compact('prescription'));
    }

    public function edit(MedicalPrescription $prescription)
    {
        $this->authorize('medical_prescriptions.create');

        $prescription->load(['patient', 'doctor', 'consultation', 'items']);

        return view('pages.consultations.medical-prescriptions.edit', compact('prescription'));
    }

    public function update(Request $request, MedicalPrescription $prescription)
    {
        $this->authorize('medical_prescriptions.create');

        $this->prescriptionService->update($prescription, $this->validatePrescription($request));

        return redirect()
            ->route('medical-prescriptions.show', $prescription)
            ->with('success', 'Ordonnance médicale mise à jour.');
    }

    public function pdf(MedicalPrescription $prescription)
    {
        $this->authorize('medical_prescriptions.view');

        $prescription->load(['patient', 'doctor', 'consultation', 'items']);
        $settings = [
            'clinic_name' => Setting::get('clinic_name', 'OptiCare Soft'),
            'clinic_slogan' => Setting::get('clinic_slogan', 'La solution intelligente pour gérer votre cabinet ophtalmologique.'),
            'clinic_address' => Setting::get('clinic_address', ''),
            'clinic_phone' => Setting::get('clinic_phone', ''),
            'clinic_email' => Setting::get('clinic_email', ''),
            'prescription_note' => Setting::get('prescription_note', 'Ordonnance valable selon indication du médecin.'),
        ];

        return Pdf::loadView('pdf.medical-prescription', compact('prescription', 'settings'))
            ->setPaper('a4')
            ->stream($prescription->prescription_number . '.pdf');
    }

    private function validatePrescription(Request $request): array
    {
        return $request->validate([
            'instructions' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'valid_until' => 'nullable|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.drug_name' => 'required|string|max:255',
            'items.*.generic_name' => 'nullable|string|max:255',
            'items.*.dosage' => 'nullable|string|max:100',
            'items.*.form' => 'nullable|string|max:100',
            'items.*.frequency' => 'nullable|string|max:100',
            'items.*.duration' => 'nullable|string|max:100',
            'items.*.route' => 'nullable|string|max:100',
            'items.*.instructions' => 'nullable|string|max:500',
        ]);
    }
}
