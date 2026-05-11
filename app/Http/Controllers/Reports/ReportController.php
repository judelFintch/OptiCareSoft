<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService) {}

    public function index()
    {
        $this->authorize('reports.view');
        $daily = $this->reportService->getDailyReport(today());
        return view('pages.reports.index', compact('daily'));
    }

    public function daily(Request $request)
    {
        $this->authorize('reports.view');

        $date  = $request->date ? Carbon::parse($request->date) : today();
        $report = $this->reportService->getDailyReport($date);
        return view('pages.reports.daily', compact('report', 'date'));
    }

    public function dailyPdf(Request $request)
    {
        $this->authorize('reports.export');

        $date = $request->date ? Carbon::parse($request->date) : today();
        $report = $this->reportService->getDailyReport($date);
        $settings = [
            'clinic_name' => Setting::get('clinic_name', 'OptiCare Soft'),
            'clinic_slogan' => Setting::get('clinic_slogan', 'La solution intelligente pour gérer votre cabinet ophtalmologique.'),
            'clinic_address' => Setting::get('clinic_address', ''),
            'clinic_phone' => Setting::get('clinic_phone', ''),
            'clinic_email' => Setting::get('clinic_email', ''),
        ];

        return Pdf::loadView('pdf.daily-report', compact('report', 'date', 'settings'))
            ->setPaper('a4')
            ->stream('rapport-journalier-' . $date->format('Y-m-d') . '.pdf');
    }

    public function financial(Request $request)
    {
        $this->authorize('reports.view');

        $from   = $request->from ? Carbon::parse($request->from) : now()->startOfMonth();
        $to     = $request->to   ? Carbon::parse($request->to)   : now()->endOfMonth();
        $report = $this->reportService->getFinancialReport($from, $to);
        return view('pages.reports.financial', compact('report', 'from', 'to'));
    }

    public function patients(Request $request)
    {
        $this->authorize('reports.view');

        $from   = $request->from ? Carbon::parse($request->from) : now()->startOfMonth();
        $to     = $request->to   ? Carbon::parse($request->to)   : now()->endOfMonth();
        $report = $this->reportService->getPatientReport($from, $to);
        return view('pages.reports.patients', compact('report', 'from', 'to'));
    }

    public function exportFinancialExcel(Request $request)
    {
        $this->authorize('reports.export');

        $from = $request->from ? Carbon::parse($request->from) : now()->startOfMonth();
        $to   = $request->to   ? Carbon::parse($request->to)   : now()->endOfMonth();

        $payments = Payment::with(['patient', 'invoice', 'receiver'])
            ->whereBetween('paid_at', [$from->startOfDay(), $to->endOfDay()])
            ->get()
            ->map(fn ($p) => [
                'Date'         => $p->paid_at?->format('d/m/Y H:i'),
                'Patient'      => $p->patient?->full_name ?? '—',
                'Montant'      => number_format($p->amount, 2),
                'Méthode'      => $p->payment_method->value,
                'Référence'    => $p->reference ?? '—',
                'Reçu par'     => $p->receiver?->name ?? '—',
            ]);

        return (new FastExcel($payments))
            ->download('rapport-financier-' . $from->format('Y-m-d') . '-' . $to->format('Y-m-d') . '.xlsx');
    }

    public function exportPatientsExcel(Request $request)
    {
        $this->authorize('reports.export');

        $from = $request->from ? Carbon::parse($request->from) : now()->startOfMonth();
        $to   = $request->to   ? Carbon::parse($request->to)   : now()->endOfMonth();

        $patients = Patient::whereBetween('created_at', [$from, $to])
            ->withCount(['consultations', 'invoices'])
            ->get()
            ->map(fn ($p) => [
                'Code'          => $p->patient_code,
                'Prénom'        => $p->first_name,
                'Nom'           => $p->last_name,
                'Sexe'          => $p->gender?->value ?? '—',
                'Téléphone'     => $p->phone ?? '—',
                'Consultations' => $p->consultations_count,
                'Factures'      => $p->invoices_count,
                'Date création' => $p->created_at->format('d/m/Y'),
            ]);

        return (new FastExcel($patients))
            ->download('rapport-patients-' . $from->format('Y-m-d') . '-' . $to->format('Y-m-d') . '.xlsx');
    }
}
