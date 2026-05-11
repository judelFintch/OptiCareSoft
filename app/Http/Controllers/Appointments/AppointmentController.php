<?php

namespace App\Http\Controllers\Appointments;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(private AppointmentService $appointmentService) {}

    public function index()
    {
        $this->authorize('viewAny', Appointment::class);
        $appointments = Appointment::with(['patient', 'doctor'])
            ->when(request('date'), fn ($query, $date) => $query->whereDate('appointment_date', $date))
            ->latest('appointment_date')
            ->paginate(20)
            ->withQueryString();

        return view('pages.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $this->authorize('create', Appointment::class);
        $doctors  = User::role('Ophthalmologist')->where('is_active', true)->get();
        $patients = Patient::active()->orderBy('last_name')->get();
        return view('pages.appointments.create', compact('doctors', 'patients'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Appointment::class);

        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'duration_minutes' => 'nullable|integer|min:5|max:120',
            'reason'           => 'required|string|max:255',
            'notes'            => 'nullable|string|max:500',
        ]);

        $appointment = $this->appointmentService->create($validated, $request->user());

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Rendez-vous créé avec succès.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        $appointment->load(['patient', 'doctor', 'visit']);
        return view('pages.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $doctors  = User::role('Ophthalmologist')->where('is_active', true)->get();
        $patients = Patient::active()->orderBy('last_name')->get();
        return view('pages.appointments.edit', compact('appointment', 'doctors', 'patients'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'duration_minutes' => 'nullable|integer|min:5|max:120',
            'reason'           => 'required|string|max:255',
            'notes'            => 'nullable|string|max:500',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)->with('success', 'Rendez-vous modifié.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $this->appointmentService->cancel($appointment, 'Supprimé par ' . auth()->user()->name);
        return redirect()->route('appointments.index')->with('success', 'Rendez-vous annulé.');
    }

    public function confirm(Appointment $appointment)
    {
        $this->authorize('appointments.confirm');
        $this->appointmentService->confirm($appointment);
        return back()->with('success', 'Rendez-vous confirmé.');
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        $this->authorize('appointments.cancel');
        $this->appointmentService->cancel($appointment, $request->input('reason'));
        return back()->with('success', 'Rendez-vous annulé.');
    }
}
