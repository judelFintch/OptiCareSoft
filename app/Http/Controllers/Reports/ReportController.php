<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
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
        $date  = $request->date ? Carbon::parse($request->date) : today();
        $report = $this->reportService->getDailyReport($date);
        return view('pages.reports.daily', compact('report', 'date'));
    }

    public function financial(Request $request)
    {
        $from   = $request->from ? Carbon::parse($request->from) : now()->startOfMonth();
        $to     = $request->to   ? Carbon::parse($request->to)   : now()->endOfMonth();
        $report = $this->reportService->getFinancialReport($from, $to);
        return view('pages.reports.financial', compact('report', 'from', 'to'));
    }

    public function patients(Request $request)
    {
        $from   = $request->from ? Carbon::parse($request->from) : now()->startOfMonth();
        $to     = $request->to   ? Carbon::parse($request->to)   : now()->endOfMonth();
        $report = $this->reportService->getPatientReport($from, $to);
        return view('pages.reports.patients', compact('report', 'from', 'to'));
    }
}
