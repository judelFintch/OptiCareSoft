<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct(private PatientService $patientService) {}

    public function index()
    {
        $this->authorize('viewAny', Patient::class);
        return view('pages.patients.index');
    }

    public function create()
    {
        $this->authorize('create', Patient::class);
        return view('pages.patients.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Patient::class);

        $validated = $request->validate([
            'first_name'              => 'required|string|max:100',
            'last_name'               => 'required|string|max:100',
            'gender'                  => 'required|in:male,female,other',
            'birth_date'              => 'nullable|date|before:today',
            'phone'                   => 'nullable|string|max:20',
            'email'                   => 'nullable|email|max:100',
            'address'                 => 'nullable|string|max:500',
            'city'                    => 'nullable|string|max:100',
            'profession'              => 'nullable|string|max:100',
            'nationality'             => 'nullable|string|max:100',
            'emergency_contact_name'  => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'medical_history'         => 'nullable|string',
            'ophthalmic_history'      => 'nullable|string',
            'allergies'               => 'nullable|string',
        ]);

        $patient = $this->patientService->createPatient($validated, $request->user());

        return redirect()->route('patients.show', $patient)
            ->with('success', "Patient {$patient->full_name} créé avec le code {$patient->patient_code}.");
    }

    public function show(Patient $patient)
    {
        $this->authorize('view', $patient);
        $patient->load(['consultations.doctor', 'opticalPrescriptions.doctor', 'invoices', 'documents']);
        return view('pages.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $this->authorize('update', $patient);
        return view('pages.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'first_name'              => 'required|string|max:100',
            'last_name'               => 'required|string|max:100',
            'gender'                  => 'required|in:male,female,other',
            'birth_date'              => 'nullable|date|before:today',
            'phone'                   => 'nullable|string|max:20',
            'email'                   => 'nullable|email|max:100',
            'address'                 => 'nullable|string|max:500',
            'city'                    => 'nullable|string|max:100',
            'profession'              => 'nullable|string|max:100',
            'emergency_contact_name'  => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'medical_history'         => 'nullable|string',
            'ophthalmic_history'      => 'nullable|string',
            'allergies'               => 'nullable|string',
        ]);

        $this->patientService->updatePatient($patient, $validated);

        return redirect()->route('patients.show', $patient)->with('success', 'Dossier patient mis à jour.');
    }

    public function destroy(Patient $patient)
    {
        $this->authorize('delete', $patient);
        $this->patientService->deactivatePatient($patient);
        return redirect()->route('patients.index')->with('success', 'Patient désactivé.');
    }
}
