<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
