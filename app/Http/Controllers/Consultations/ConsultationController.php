<?php

namespace App\Http\Controllers\Consultations;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\Visit;
use App\Services\BillingService;
use App\Services\ConsultationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function __construct(
        private ConsultationService $consultationService,
        private BillingService $billingService,
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Consultation::class);
        $consultations = Consultation::with(['patient', 'doctor', 'visit'])
            ->latest()
            ->paginate(20);

        return view('pages.consultations.index', compact('consultations'));
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
            'invoices.currency',
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

    public function invoice(Consultation $consultation)
    {
        $this->authorize('create', Invoice::class);

        $consultation->load(['patient', 'visit']);

        $invoice = $this->billingService->createConsultationInvoice($consultation, auth()->user());

        return redirect()
            ->route('cashier.invoices.show', $invoice)
            ->with('success', 'Facture de consultation prête.');
    }

    public function pdf(Consultation $consultation)
    {
        $this->authorize('view', $consultation);

        $consultation->load([
            'patient', 'doctor', 'visit',
            'visualAcuity', 'refraction', 'eyePressure',
            'medicalPrescriptions.items', 'opticalPrescriptions',
        ]);

        $settings = [
            'clinic_name' => Setting::get('clinic_name', 'OptiCare Soft'),
            'clinic_slogan' => Setting::get('clinic_slogan', 'La solution intelligente pour gérer votre cabinet ophtalmologique.'),
            'clinic_address' => Setting::get('clinic_address', ''),
            'clinic_phone' => Setting::get('clinic_phone', ''),
            'clinic_email' => Setting::get('clinic_email', ''),
        ];

        return Pdf::loadView('pdf.consultation', compact('consultation', 'settings'))
            ->setPaper('a4')
            ->stream($consultation->consultation_code . '.pdf');
    }
}
