<?php

namespace App\Services\Reports;

use App\Models\PaymentPlan;
use Illuminate\Http\Request;

class PaymentReportService
{
    /**
     * Build the filtered payment report query.
     */
    public function getQuery(Request $request)
    {
        $query = PaymentPlan::with([
            'patient:Predict3DId,FullName,DoctorName',
            'payments',
            'deliveries',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->from
            );

        }

        if ($request->filled('to')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->to
            );

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
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            switch ($request->status) {

                case 'Paid':

                    $query->where(
                        'remaining_amount',
                        '<=',
                        0
                    );

                    break;

                case 'Partial':

                    $query->whereColumn(
                        'remaining_amount',
                        '<',
                        'total_amount'
                    )->where(
                        'remaining_amount',
                        '>',
                        0
                    );

                    break;

                case 'Due':

                    $query->whereColumn(
                        'remaining_amount',
                        '=',
                        'total_amount'
                    );

                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Patient Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $keyword = trim($request->search);

            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'predict3d_id',
                    'LIKE',
                    "%{$keyword}%"
                )

                ->orWhereHas('patient', function ($patient) use ($keyword) {

                    $patient->where(
                        'FullName',
                        'LIKE',
                        "%{$keyword}%"
                    )

                    ->orWhere(
                        'DoctorName',
                        'LIKE',
                        "%{$keyword}%"
                    );

                });

            });

        }

        return $query;
    }
    /**
     * Summary Cards
     */
    public function getSummary(): array
    {
        return [

            'total_revenue' => \App\Models\PaymentPlanPayment::sum('amount'),

            'today_collection' => \App\Models\PaymentPlanPayment::whereDate(
                'payment_date',
                now()->toDateString()
            )->sum('amount'),

            'pending_due' => PaymentPlan::sum('remaining_amount'),

            'completed_plans' => PaymentPlan::where(
                'remaining_amount',
                '<=',
                0
            )->count(),

        ];
    }

    /**
     * Monthly Revenue Chart
     */
    public function getRevenueChart(): array
    {
        $monthlyRevenue = \App\Models\PaymentPlanPayment::selectRaw(
                'MONTH(payment_date) as month, SUM(amount) as total'
            )
            ->whereYear(
                'payment_date',
                now()->year
            )
            ->groupByRaw('MONTH(payment_date)')
            ->pluck(
                'total',
                'month'
            );

        $chart = [];

        foreach (range(1, 12) as $month) {

            $chart[] = $monthlyRevenue[$month] ?? 0;

        }

        return $chart;
    }

    /**
     * Payment Method Chart
     */
    public function getMethodChart(): array
    {
        return [

            'cash' => PaymentPlan::where(
                'payment_method',
                'cash'
            )->count(),

            'card' => PaymentPlan::where(
                'payment_method',
                'card'
            )->count(),

            'bank_transfer' => PaymentPlan::where(
                'payment_method',
                'bank_transfer'
            )->count(),

            'mobile_banking' => PaymentPlan::where(
                'payment_method',
                'mobile_banking'
            )->count(),

        ];
    }

    /**
     * Payment Report List
     */
    public function getPayments(Request $request, int $perPage = 10)
    {
        return $this->getQuery($request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

        /**
     * Report data for Excel / PDF exports.
     */
    public function exportReportData(Request $request)
    {
        return $this->getQuery($request)
            ->latest()
            ->get();
    }

    /**
     * Complete report payload for the report page.
     */
    public function getReport(Request $request): array
    {
        return [

            'summary' => $this->getSummary(),

            'payments' => $this->getPayments($request),

            'revenueChart' => $this->getRevenueChart(),

            'methodChart' => $this->getMethodChart(),

        ];
    }

    /**
     * Complete payload for PDF export.
     */
    public function exportPdfData(Request $request): array
    {
        return [

            'summary' => $this->getSummary(),

            'payments' => $this->exportReportData($request),

            'revenueChart' => $this->getRevenueChart(),

            'methodChart' => $this->getMethodChart(),

            'generatedAt' => now(),

            'filters' => [
                'from'           => $request->from,
                'to'             => $request->to,
                'payment_method' => $request->payment_method,
                'status'         => $request->status,
                'search'         => $request->search,
            ],

        ];
    }

    /**
     * Get Payment Report Details
     */
    public function getDetails($id)
    {
        $payment = PaymentPlan::with([
            'patient:Predict3DId,FullName,DoctorName',
            'payments',
            'deliveries',
        ])->findOrFail($id);

        $totalPaid = $payment->payments->sum('amount');

        $completion = 0;

        if ($payment->total_amount > 0) {
            $completion = round(
                ($totalPaid / $payment->total_amount) * 100,
                2
            );
        }

        $payment->total_paid = $totalPaid;
        $payment->completion = $completion;

        return $payment;
    }
}