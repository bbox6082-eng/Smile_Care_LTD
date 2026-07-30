<?php

namespace App\Services\Reports;

use App\Models\PaymentPlan;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class DueReportService
{
    /**
     * ===========================================
     * Base Query
     * ===========================================
     */
    public function getQuery(Request $request): Builder
    {
        $query = PaymentPlan::with([
            'patient',
            'payments',
            'deliveries',
        ])
        ->where('remaining_amount', '>', 0);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('predict3d_id', 'like', "%{$search}%")

                  ->orWhereHas('patient', function ($patient) use ($search) {

                      $patient->where('FullName', 'like', "%{$search}%")
                              ->orWhere('DoctorName', 'like', "%{$search}%");

                  });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Method
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_method')) {

            $query->where(
                'payment_method',
                $request->payment_method
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Installment
        |--------------------------------------------------------------------------
        */

        if ($request->filled('is_installment')) {

            $query->where(
                'is_installment',
                $request->is_installment
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        if ($request->filled('start_date')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->start_date
            );

        }

        if ($request->filled('end_date')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->end_date
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Due Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            switch ($request->status) {

                case 'overdue':

                    $query->whereDate(
                        'next_payment_date',
                        '<',
                        Carbon::today()
                    );

                    break;

                case 'upcoming':

                    $query->whereDate(
                        'next_payment_date',
                        '>=',
                        Carbon::today()
                    );

                    break;

            }

        }

        return $query->latest();

    }

    /**
     * ===========================================
     * Summary Cards
     * ===========================================
     */
    public function getSummary(Builder $query): array
    {
        $payments = $query->get();

        $totalOutstanding = $payments->sum('remaining_amount');

        $overdueAmount = $payments
            ->filter(function ($payment) {

                return $payment->next_payment_date &&
                       Carbon::parse($payment->next_payment_date)
                             ->lt(Carbon::today());

            })
            ->sum('remaining_amount');

        $patientsWithDue = $payments->count();

        $averageDue = $patientsWithDue > 0
            ? $totalOutstanding / $patientsWithDue
            : 0;

        return [

            'total_outstanding' => $totalOutstanding,

            'overdue_amount' => $overdueAmount,

            'patients_with_due' => $patientsWithDue,

            'average_due' => $averageDue,

        ];

    }
    
        /**
     * ===========================================
     * Due Trend Chart
     * ===========================================
     */
    public function getDueTrendChart(Builder $query): array
    {
        $rows = $query
            ->selectRaw('DATE(created_at) as report_date')
            ->selectRaw('SUM(remaining_amount) as total_due')
            ->groupBy('report_date')
            ->orderBy('report_date')
            ->get();

        return [
            'labels' => $rows->map(function ($row) {
                return Carbon::parse($row->report_date)->format('d M');
            })->values()->toArray(),

            'values' => $rows->pluck('total_due')
                ->map(fn ($value) => (float) $value)
                ->values()
                ->toArray(),
        ];
    }

    /**
     * ===========================================
     * Doctor-wise Due Chart
     * ===========================================
     */
    public function getDoctorDueChart(Builder $query): array
    {
        $doctorDue = $query
            ->get()
            ->groupBy(function ($payment) {
                return optional($payment->patient)->DoctorName ?? 'Unknown';
            })
            ->map(function ($plans) {
                return $plans->sum('remaining_amount');
            });

        return [
            'labels' => $doctorDue->keys()->values()->toArray(),

            'values' => $doctorDue->values()
                ->map(fn ($value) => (float) $value)
                ->toArray(),
        ];
    }
    /**
     * ===========================================
     * Paginated Payments
     * ===========================================
     */
    public function getPayments(Builder $query)
    {
        return $query->paginate(15);
    }

    /**
     * ===========================================
     * Excel Export Data
     * ===========================================
     */
    public function exportReportData(Request $request)
    {
        return $this->getQuery($request)->get();
    }

    /**
     * ===========================================
     * Main Report
     * ===========================================
     */
    public function getReport(Request $request): array
    {
        $query = $this->getQuery($request);

        return [

            'summary' => $this->getSummary(clone $query),

            'payments' => $this->getPayments(clone $query),

            'dueChart' => $this->getDueTrendChart(clone $query),

            'doctorChart' => $this->getDoctorDueChart(clone $query),

        ];
    }

        /**
     * ===========================================
     * PDF Export Data
     * ===========================================
     */
    public function exportPdfData(Request $request): array
    {
        $query = $this->getQuery($request);

        return [

            'payments' => $query->get(),

            'summary' => $this->getSummary(clone $query),

            'generatedAt' => now(),

        ];
    }

    /**
     * ===========================================
     * View Details
     * ===========================================
     */
    public function getDetails(int $id): PaymentPlan
    {
        $payment = PaymentPlan::with([
            'patient',
            'payments',
            'deliveries',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Calculated Fields
        |--------------------------------------------------------------------------
        */

        $payment->total_paid = $payment->payments->sum('amount');

        $payment->completion = $payment->total_amount > 0
            ? round(
                ($payment->total_paid / $payment->total_amount) * 100,
                2
            )
            : 0;

        return $payment;
    }

}

