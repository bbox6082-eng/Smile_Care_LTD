<?php

namespace App\Services\Reports;

use App\Models\PaymentPlan;
use App\Models\PaymentPlanPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorRevenueReportService
{
    /**
     * ==========================================================
     * Base Payment Plan Query
     * ==========================================================
     *
     * Used for doctor filtering and case/payment information.
     */
    public function getQuery(Request $request): Builder
    {
        $query = PaymentPlan::with([
            'patient:Predict3DId,FullName,DoctorName',
            'payments',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        |
        | Uses the same created_at date filtering as the existing
        | Payment Report.
        |
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
        | Doctor Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('doctor')) {

            $query->whereHas('patient', function ($patient) use ($request) {

                $patient->where(
                    'DoctorName',
                    $request->doctor
                );

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
        | Search
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
     * ==========================================================
     * Doctor List
     * ==========================================================
     */
    public function getDoctors()
    {
        return PaymentPlan::query()
            ->whereHas('patient', function ($patient) {

                $patient
                    ->whereNotNull('DoctorName')
                    ->where('DoctorName', '!=', '');

            })
            ->with('patient:Predict3DId,DoctorName')
            ->get()
            ->pluck('patient.DoctorName')
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }


    /**
     * ==========================================================
     * Revenue Summary
     * ==========================================================
     *
     * Revenue = actual recorded payment amounts.
     */
    public function getSummary(Request $request): array
    {
        $paymentPlanIds = $this->getQuery($request)
            ->pluck('id');


        $paymentQuery = PaymentPlanPayment::query()
            ->whereIn(
                'payment_plan_id',
                $paymentPlanIds
            );


        $totalRevenue = (clone $paymentQuery)
            ->sum('amount');


        $totalTransactions = (clone $paymentQuery)
            ->count();


        $totalPlans = $paymentPlanIds->count();


        $pendingDue = $this->getQuery($request)
            ->sum('remaining_amount');


        $averageTransaction = $totalTransactions > 0
            ? $totalRevenue / $totalTransactions
            : 0;


        return [

            'total_revenue' => $totalRevenue,

            'total_transactions' => $totalTransactions,

            'total_plans' => $totalPlans,

            'pending_due' => $pendingDue,

            'average_transaction' => $averageTransaction,

        ];
    }


    /**
     * ==========================================================
     * Doctor Revenue Summary
     * ==========================================================
     */
    public function getDoctorRevenue(Request $request)
    {
        $paymentPlanIds = $this->getQuery($request)
            ->pluck('id');


        if ($paymentPlanIds->isEmpty()) {

            return collect();

        }


        return PaymentPlanPayment::query()

            ->select([
                'payment_plan_payments.payment_plan_id',
                DB::raw(
                    'SUM(payment_plan_payments.amount) as revenue'
                ),
                DB::raw(
                    'COUNT(payment_plan_payments.id) as transactions'
                ),
            ])

            ->whereIn(
                'payment_plan_payments.payment_plan_id',
                $paymentPlanIds
            )

            ->with([
                'paymentPlan.patient:Predict3DId,FullName,DoctorName'
            ])

            ->groupBy(
                'payment_plan_payments.payment_plan_id'
            )

            ->get()

            ->groupBy(function ($payment) {

                return optional(
                    $payment->paymentPlan?->patient
                )->DoctorName ?: 'Unknown';

            })

            ->map(function ($payments, $doctor) {

                return [

                    'doctor' => $doctor,

                    'revenue' => $payments->sum(
                        fn ($payment) =>
                            (float) $payment->revenue
                    ),

                    'transactions' => $payments->sum(
                        fn ($payment) =>
                            (int) $payment->transactions
                    ),

                    'payment_plans' => $payments->count(),

                ];

            })

            ->sortByDesc('revenue')
            ->values();
    }


    /**
     * ==========================================================
     * Paginated Doctor Revenue
     * ==========================================================
     */
    public function getDoctorRevenuePaginated(
        Request $request,
        int $perPage = 10
    ) {
        $collection = $this->getDoctorRevenue($request);

        $currentPage = (int) (
            $request->input('page', 1)
        );

        $items = $collection->forPage(
            $currentPage,
            $perPage
        );

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }


    /**
     * ==========================================================
     * Individual Doctor Revenue
     * ==========================================================
     */
    public function getDoctorDetails(
        Request $request,
        string $doctor
    ): array {

        $query = $this->getQuery($request);

        $paymentPlans = $query
            ->whereHas('patient', function ($patient) use ($doctor) {

                $patient->where(
                    'DoctorName',
                    $doctor
                );

            })
            ->latest()
            ->get();


        $totalRevenue = $paymentPlans
            ->sum(function ($paymentPlan) {

                return $paymentPlan->payments
                    ->sum('amount');

            });


        $totalTransactions = $paymentPlans
            ->sum(function ($paymentPlan) {

                return $paymentPlan->payments->count();

            });


        $totalPlans = $paymentPlans->count();


        $pendingDue = $paymentPlans
            ->sum('remaining_amount');


        return [

            'doctor' => $doctor,

            'paymentPlans' => $paymentPlans,

            'totalRevenue' => $totalRevenue,

            'totalTransactions' => $totalTransactions,

            'totalPlans' => $totalPlans,

            'pendingDue' => $pendingDue,

        ];
    }


    /**
     * ==========================================================
     * Payment Records
     * ==========================================================
     */
    public function getPayments(
        Request $request,
        int $perPage = 10
    ) {

        return $this->getQuery($request)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

    }


    /**
     * ==========================================================
     * Export Data
     * ==========================================================
     */
    public function exportReportData(Request $request)
    {
        return $this->getQuery($request)
            ->latest()
            ->get();
    }


    /**
     * ==========================================================
     * PDF Data
     * ==========================================================
     */
    public function exportPdfData(
        Request $request
    ): array {

        return [

            'summary' => $this->getSummary($request),

            'doctorRevenue' =>
                $this->getDoctorRevenue($request),

            'payments' =>
                $this->exportReportData($request),

            'filters' => [

                'from' =>
                    $request->input('from'),

                'to' =>
                    $request->input('to'),

                'doctor' =>
                    $request->input('doctor'),

                'payment_method' =>
                    $request->input('payment_method'),

                'status' =>
                    $request->input('status'),

                'search' =>
                    $request->input('search'),

            ],

            'generatedAt' => now(),

        ];
    }
}