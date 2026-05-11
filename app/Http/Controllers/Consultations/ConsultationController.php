<?php

namespace App\Http\Controllers\Consultations;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Visit;
use App\Services\ConsultationService;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function __construct(private ConsultationService $consultationService) {}

    public function index()
    {
        $this->authorize('viewAny', Consultation::class);
        return view('pages.consultations.index');
    }

    public function create(Request $request)
    {
        $this->authorize('create', Consultation::class);
        $visit = Visit::with('patient')->findOrFail($request->query('visit_id'));
        return view('pages.consultations.create', compact('visit'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Consultation::class);

        $validated = $request->validate([
            'visit_id'       => 'required|exists:visits,id',
            'chief_complaint'=> 'required|string|max:500',
            'medical_history'=> 'nullable|string',
            'ophthalmic_history' => 'nullable|string',
            'current_medications' => 'nullable|string',
        ]);

        $visit        = Visit::findOrFail($validated['visit_id']);
        $consultation = $this->consultationService->createConsultation($visit, $request->user(), $validated);

        return redirect()->route('consultations.show', $consultation)
            ->with('success', 'Consultation ouverte.');
    }

    public function show(Consultation $consultation)
    {
        $this->authorize('view', $consultation);
        $consultation->load([
            'patient', 'doctor', 'visit',
            'visualAcuity', 'refraction', 'eyePressure',
            'medicalPrescriptions.items', 'opticalPrescriptions',
        ]);
        return view('pages.consultations.show', compact('consultation'));
    }

    public function edit(Consultation $consultation)
    {
        $this->authorize('update', $consultation);
        $consultation->load(['patient', 'visualAcuity', 'refraction', 'eyePressure']);
        return view('pages.consultations.edit', compact('consultation'));
    }

    public function update(Request $request, Consultation $consultation)
    {
        $this->authorize('update', $consultation);

        $validated = $request->validate([
            'chief_complaint'       => 'required|string|max:500',
            'history_of_present_illness' => 'nullable|string',
            'clinical_findings'     => 'nullable|string',
            'primary_diagnosis'     => 'nullable|string',
            'secondary_diagnoses'   => 'nullable|array',
            'icd_code'              => 'nullable|string|max:20',
            'treatment_plan'        => 'nullable|string',
            'recommendations'       => 'nullable|string',
            'next_appointment_date' => 'nullable|date|after:today',
        ]);

        $this->consultationService->updateConsultation($consultation, $validated);

        return redirect()->route('consultations.show', $consultation)->with('success', 'Consultation mise à jour.');
    }

    public function destroy(Consultation $consultation)
    {
        $this->authorize('delete', $consultation);
        $consultation->delete();
        return redirect()->route('consultations.index')->with('success', 'Consultation supprimée.');
    }

    public function sign(Consultation $consultation)
    {
        $this->authorize('sign', $consultation);
        $this->consultationService->signConsultation($consultation, auth()->user());
        return back()->with('success', 'Consultation signée.');
    }

    public function complete(Consultation $consultation)
    {
        $this->authorize('update', $consultation);
        $this->consultationService->completeConsultation($consultation);
        return back()->with('success', 'Consultation marquée comme terminée.');
    }
}
