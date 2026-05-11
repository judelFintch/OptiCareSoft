<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Visit;
use App\Services\VisitService;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{
    public function __construct(private VisitService $visitService) {}

    public function index()
    {
        $this->authorize('visits.view');
        return view('pages.reception.index');
    }

    public function openVisit(Request $request)
    {
        $this->authorize('visits.create');

        $validated = $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        $patient     = Patient::findOrFail($validated['patient_id']);
        $appointment = isset($validated['appointment_id'])
            ? \App\Models\Appointment::find($validated['appointment_id'])
            : null;

        $visit = $this->visitService->openVisit($patient, $request->user(), $appointment);

        return back()->with('success', "Visite ouverte — {$visit->visit_code}");
    }

    public function closeVisit(Visit $visit)
    {
        $this->authorize('visits.manage');
        $this->visitService->closeVisit($visit, auth()->user());
        return back()->with('success', 'Visite clôturée.');
    }

    public function updateStatus(Request $request, Visit $visit)
    {
        $this->authorize('visits.manage');
        $validated = $request->validate(['status' => 'required|in:open,in_progress,pending,closed,cancelled']);
        $this->visitService->updateStatus($visit, \App\Enums\VisitStatus::from($validated['status']));
        return back()->with('success', 'Statut mis à jour.');
    }
}
