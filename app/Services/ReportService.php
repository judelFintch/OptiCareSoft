<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getDailyReport(Carbon $date): array
    {
        return [
            'date'              => $date->format('d/m/Y'),
            'visits_opened'     => Visit::whereDate('opened_at', $date)->count(),
            'visits_closed'     => Visit::whereDate('closed_at', $date)->count(),
            'consultations'     => Consultation::whereDate('created_at', $date)->count(),
            'new_patients'      => Patient::whereDate('created_at', $date)->count(),
            'appointments_total'=> Appointment::whereDate('appointment_date', $date)->count(),
            'appointments_done' => Appointment::whereDate('appointment_date', $date)->where('status', 'completed')->count(),
            'appointments_missed'=> Appointment::whereDate('appointment_date', $date)->where('status', 'missed')->count(),
            'revenue'           => [
                'total'     => Payment::whereDate('paid_at', $date)->sum('amount'),
                'cash'      => Payment::whereDate('paid_at', $date)->where('payment_method', 'cash')->sum('amount'),
                'mobile'    => Payment::whereDate('paid_at', $date)->where('payment_method', 'mobile_money')->sum('amount'),
                'bank'      => Payment::whereDate('paid_at', $date)->where('payment_method', 'bank')->sum('amount'),
                'card'      => Payment::whereDate('paid_at', $date)->where('payment_method', 'card')->sum('amount'),
            ],
            'invoices_issued'   => Invoice::whereDate('issued_at', $date)->count(),
            'invoices_paid'     => Invoice::whereDate('paid_at', $date)->count(),
        ];
    }

    public function getFinancialReport(Carbon $from, Carbon $to): array
    {
        $payments = Payment::whereBetween('paid_at', [$from->startOfDay(), $to->endOfDay()])
            ->with(['invoice', 'currency'])
            ->get();

        $invoices = Invoice::whereBetween('issued_at', [$from->startOfDay(), $to->endOfDay()])
            ->get();

        return [
            'period'          => ['from' => $from->format('d/m/Y'), 'to' => $to->format('d/m/Y')],
            'total_invoiced'  => $invoices->sum('total_amount'),
            'total_collected' => $payments->sum('amount'),
            'total_debt'      => $invoices->whereIn('status', [InvoiceStatus::Unpaid->value, InvoiceStatus::PartiallyPaid->value])->sum('remaining_amount'),
            'by_method'       => $payments->groupBy('payment_method')->map->sum('amount'),
            'by_type'         => $invoices->groupBy('invoice_type')->map->sum('total_amount'),
            'invoices_by_status' => $invoices->groupBy('status')->map->count(),
            'monthly_trend'   => $this->getMonthlyTrend($from, $to),
        ];
    }

    public function getPatientReport(Carbon $from, Carbon $to): array
    {
        return [
            'period'       => ['from' => $from->format('d/m/Y'), 'to' => $to->format('d/m/Y')],
            'new_patients' => Patient::whereBetween('created_at', [$from, $to])->count(),
            'total_visits' => Visit::whereBetween('opened_at', [$from, $to])->count(),
            'by_gender'    => Patient::whereBetween('created_at', [$from, $to])->select('gender', DB::raw('count(*) as count'))->groupBy('gender')->pluck('count', 'gender'),
            'top_patients' => Patient::withCount('consultations')->having('consultations_count', '>', 0)->orderByDesc('consultations_count')->take(10)->get(['id', 'first_name', 'last_name', 'patient_code']),
        ];
    }

    public function getConsultationReport(Carbon $from, Carbon $to): array
    {
        $consultations = Consultation::whereBetween('created_at', [$from, $to])
            ->whereNotNull('primary_diagnosis')
            ->get();

        return [
            'period'          => ['from' => $from->format('d/m/Y'), 'to' => $to->format('d/m/Y')],
            'total'           => $consultations->count(),
            'by_diagnosis'    => $consultations->groupBy('primary_diagnosis')->map->count()->sortDesc()->take(10),
            'by_doctor'       => Consultation::whereBetween('created_at', [$from, $to])
                ->select('doctor_id', DB::raw('count(*) as count'))
                ->groupBy('doctor_id')
                ->with('doctor:id,name')
                ->get(),
            'signed'          => $consultations->where('status', 'signed')->count(),
        ];
    }

    private function getMonthlyTrend(Carbon $from, Carbon $to): array
    {
        return Payment::whereBetween('paid_at', [$from, $to])
            ->select(DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }
}
