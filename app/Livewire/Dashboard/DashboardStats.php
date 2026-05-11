<?php

namespace App\Livewire\Dashboard;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Visit;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardStats extends Component
{
    public array $stats = [];
    public array $todayAppointments = [];
    public array $waitingQueue = [];
    public array $monthlyRevenue = [];

    public function mount(ReportService $reportService): void
    {
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $today = today();

        $this->stats = [
            'patients_today'      => Visit::whereDate('opened_at', $today)->count(),
            'consultations_today' => Consultation::whereDate('created_at', $today)->count(),
            'revenue_today'       => Payment::whereDate('paid_at', $today)->sum('amount'),
            'appointments_today'  => Appointment::whereDate('appointment_date', $today)->count(),
            'total_patients'      => Patient::active()->count(),
            'pending_orders'      => \App\Models\OpticalOrder::whereIn('status', ['pending', 'in_production'])->count(),
            'total_unpaid'        => Invoice::unpaid()->sum('remaining_amount'),
            'waiting_count'       => Visit::open()->today()->count(),
        ];

        $this->todayAppointments = Appointment::today()
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_time')
            ->take(8)
            ->get()
            ->map(fn($a) => [
                'id'      => $a->id,
                'patient' => $a->patient->full_name,
                'time'    => $a->appointment_time,
                'doctor'  => $a->doctor->name,
                'reason'  => $a->reason,
                'status'  => $a->status->label(),
                'color'   => $a->status->color(),
            ])
            ->toArray();

        $this->waitingQueue = Visit::open()
            ->today()
            ->with(['patient', 'opener'])
            ->orderBy('opened_at')
            ->take(5)
            ->get()
            ->map(fn($v) => [
                'id'      => $v->id,
                'patient' => $v->patient->full_name,
                'code'    => $v->patient->patient_code,
                'since'   => $v->opened_at->diffForHumans(),
                'status'  => $v->status->label(),
                'color'   => $v->status->color(),
            ])
            ->toArray();

        $driver = DB::connection()->getDriverName();
        $monthExpr = $driver === 'sqlite'
            ? "strftime('%Y-%m', paid_at)"
            : "DATE_FORMAT(paid_at, '%Y-%m')";

        $trend = Payment::where('paid_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->select(DB::raw("$monthExpr as month"), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $this->monthlyRevenue = [
            'labels' => $trend->keys()->values()->toArray(),
            'data'   => $trend->values()->map(fn($v) => round((float) $v, 2))->toArray(),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-stats');
    }
}
