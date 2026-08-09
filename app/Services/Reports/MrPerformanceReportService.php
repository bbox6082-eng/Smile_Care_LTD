<?php

namespace App\Services\Reports;

use App\Models\MarketingRepresentative;
use App\Models\Patient;
use App\Models\PaymentPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MrPerformanceReportService
{
    /**
     * ==========================================================
     * Base Patient Query
     * ==========================================================
     *
     * Relationship:
     *
     * Patient.DoctorName
     *      ↓
     * Doctor.name
     *      ↓
     * Doctor.marketing_representative_name
     */
    public function getPatientQuery(
        Request $request
    ): Builder {

        $query = Patient::query()
            ->leftJoin(
                'doctors',
                'doctors.name',
                '=',
                'patients.DoctorName'
            )
            ->where(
                'patients.is_patient_page_deleted',
                false
            );


        $query->select([
            'patients.Predict3DId',
            'patients.FullName',
            'patients.DoctorName',
            'patients.PhoneNumber',
            'patients.status',
            'patients.ScanningFor',
            'patients.created_at',

            'doctors.marketing_representative_name as MRName',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from')) {

            $query->whereDate(
                'patients.created_at',
                '>=',
                $request->from
            );
        }


        if ($request->filled('to')) {

            $query->whereDate(
                'patients.created_at',
                '<=',
                $request->to
            );
        }


        /*
        |--------------------------------------------------------------------------
        | MR Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('mr')) {

            if ($request->mr === 'Unassigned') {

                $query->where(function ($q) {

                    $q->whereNull(
                        'doctors.marketing_representative_name'
                    )

                    ->orWhere(
                        'doctors.marketing_representative_name',
                        ''
                    );

                });

            } else {

                $query->where(
                    'doctors.marketing_representative_name',
                    $request->mr
                );

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Doctor Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('doctor')) {

            $query->where(
                'patients.DoctorName',
                $request->doctor
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'patients.status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $keyword = trim(
                $request->search
            );


            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'patients.Predict3DId',
                    'LIKE',
                    "%{$keyword}%"
                )

                ->orWhere(
                    'patients.FullName',
                    'LIKE',
                    "%{$keyword}%"
                )

                ->orWhere(
                    'patients.PhoneNumber',
                    'LIKE',
                    "%{$keyword}%"
                )

                ->orWhere(
                    'patients.DoctorName',
                    'LIKE',
                    "%{$keyword}%"
                )

                ->orWhere(
                    'doctors.marketing_representative_name',
                    'LIKE',
                    "%{$keyword}%"
                );

            });
        }


        return $query;
    }


    /**
     * ==========================================================
     * Get Marketing Representatives
     * ==========================================================
     */
    public function getMarketingRepresentatives()
    {
        return MarketingRepresentative::query()
            ->orderBy('name')
            ->pluck('name');
    }


    /**
     * ==========================================================
     * Get Doctors
     * ==========================================================
     */
    public function getDoctors()
    {
        return Patient::query()
            ->where(
                'is_patient_page_deleted',
                false
            )
            ->whereNotNull('DoctorName')
            ->where(
                'DoctorName',
                '!=',
                ''
            )
            ->distinct()
            ->orderBy('DoctorName')
            ->pluck('DoctorName');
    }


    /**
     * ==========================================================
     * Get Statuses
     * ==========================================================
     */
    public function getStatuses()
    {
        return Patient::query()
            ->whereNotNull('status')
            ->where(
                'status',
                '!=',
                ''
            )
            ->distinct()
            ->orderBy('status')
            ->pluck('status');
    }


    /**
     * ==========================================================
     * Get Filtered Patient IDs
     * ==========================================================
     */
    protected function getFilteredPatientIds(
        Request $request
    ) {

        return (clone $this->getPatientQuery($request))
            ->pluck('patients.Predict3DId');
    }


    /**
     * ==========================================================
     * Get Payment Plans for Filtered Patients
     * ==========================================================
     */
    protected function getPaymentPlans(
        Request $request
    ) {

        $patientIds =
            $this->getFilteredPatientIds($request);


        if ($patientIds->isEmpty()) {
            return collect();
        }


        return PaymentPlan::query()
            ->with([
                'patient',
                'payments',
            ])
            ->whereIn(
                'predict3d_id',
                $patientIds
            )
            ->get();
    }


    /**
     * ==========================================================
     * Build MR Performance Collection
     * ==========================================================
     */
    public function getPerformance(
        Request $request
    ) {

        $patients = $this->getPatientQuery($request)
            ->get();


        $plans = $this->getPaymentPlans($request);


        /*
        |--------------------------------------------------------------------------
        | Group Patients by MR
        |--------------------------------------------------------------------------
        */

        $patientGroups = $patients
            ->groupBy(function ($patient) {

                return $patient->MRName
                    ?: 'Unassigned';

            });


        /*
        |--------------------------------------------------------------------------
        | Group Payment Plans by MR
        |--------------------------------------------------------------------------
        */

        $plansByMr = $plans
            ->groupBy(function ($plan) {

                return optional(
                    $plan->patient
                )->MRName
                    ?: $this->resolvePatientMr(
                        $plan->patient
                    );

            });


        /*
        |--------------------------------------------------------------------------
        | Build Performance Rows
        |--------------------------------------------------------------------------
        */

        $rows = collect();


        foreach (
            $patientGroups as $mr => $mrPatients
        ) {

            $mrPlans =
                $plansByMr->get(
                    $mr,
                    collect()
                );


            /*
            |--------------------------------------------------------------------------
            | Patient Metrics
            |--------------------------------------------------------------------------
            */

            $totalPatients =
                $mrPatients->count();


            $activePatients =
                $mrPatients
                    ->where(
                        'status',
                        'active'
                    )
                    ->count();


            $inactivePatients =
                $mrPatients
                    ->where(
                        'status',
                        'inactive'
                    )
                    ->count();


            $doctorCount =
                $mrPatients
                    ->pluck('DoctorName')
                    ->filter()
                    ->unique()
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | Financial Metrics
            |--------------------------------------------------------------------------
            */

            $plannedRevenue =
                $mrPlans->sum(function ($plan) {

                    return (float)
                        $plan->total_amount;

                });


            $collectedRevenue =
                $mrPlans->sum(function ($plan) {

                    return $plan->payments
                        ->sum('amount');

                });


            $outstandingDue =
                $mrPlans->sum(function ($plan) {

                    return (float)
                        $plan->remaining_amount;

                });


            $paymentPlans =
                $mrPlans->count();


            $paidPlans =
                $mrPlans
                    ->filter(function ($plan) {

                        return
                            (float) $plan->remaining_amount
                            <= 0;

                    })
                    ->count();


            /*
            |--------------------------------------------------------------------------
            | Collection Rate
            |--------------------------------------------------------------------------
            */

            $collectionRate =
                $plannedRevenue > 0
                    ? (
                        $collectedRevenue /
                        $plannedRevenue
                    ) * 100
                    : 0;


            /*
            |--------------------------------------------------------------------------
            | Average Revenue / Patient
            |--------------------------------------------------------------------------
            */

            $averageRevenuePerPatient =
                $totalPatients > 0
                    ? (
                        $collectedRevenue /
                        $totalPatients
                    )
                    : 0;


            $rows->push([

                'mr' =>
                    $mr,

                'patients' =>
                    $totalPatients,

                'active' =>
                    $activePatients,

                'inactive' =>
                    $inactivePatients,

                'doctors' =>
                    $doctorCount,

                'payment_plans' =>
                    $paymentPlans,

                'paid_plans' =>
                    $paidPlans,

                'planned_revenue' =>
                    $plannedRevenue,

                'collected_revenue' =>
                    $collectedRevenue,

                'outstanding_due' =>
                    $outstandingDue,

                'collection_rate' =>
                    $collectionRate,

                'average_revenue_per_patient' =>
                    $averageRevenuePerPatient,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Rank by Collected Revenue
        |--------------------------------------------------------------------------
        */

        return $rows
            ->sortByDesc(
                'collected_revenue'
            )
            ->values();
    }


    /**
     * ==========================================================
     * Resolve MR for Patient
     * ==========================================================
     *
     * Used for payment-plan grouping.
     */
    protected function resolvePatientMr(
        ?Patient $patient
    ): string {

        if (!$patient) {
            return 'Unassigned';
        }


        $doctor = \App\Models\Doctor::query()
            ->where(
                'name',
                $patient->DoctorName
            )
            ->first();


        return $doctor?->marketing_representative_name
            ?: 'Unassigned';
    }


    /**
     * ==========================================================
     * Paginated Performance
     * ==========================================================
     */
    public function getPaginatedPerformance(
        Request $request,
        int $perPage = 10
    ): LengthAwarePaginator {

        $rows =
            $this->getPerformance($request);


        $currentPage =
            max(
                1,
                (int) $request->input(
                    'page',
                    1
                )
            );


        $items =
            $rows->forPage(
                $currentPage,
                $perPage
            );


        return new LengthAwarePaginator(

            $items,

            $rows->count(),

            $perPage,

            $currentPage,

            [

                'path' =>
                    $request->url(),

                'query' =>
                    $request->query(),

            ]

        );
    }


    /**
     * ==========================================================
     * Overall Summary
     * ==========================================================
     */
    public function getSummary(
        Request $request
    ): array {

        $rows =
            $this->getPerformance($request);


        $totalMrs =
            $rows->count();


        $totalPatients =
            $rows->sum('patients');


        $totalActive =
            $rows->sum('active');


        $totalDoctors =
            $rows->sum('doctors');


        $totalPlans =
            $rows->sum('payment_plans');


        $totalPaidPlans =
            $rows->sum('paid_plans');


        $plannedRevenue =
            $rows->sum(
                'planned_revenue'
            );


        $collectedRevenue =
            $rows->sum(
                'collected_revenue'
            );


        $outstandingDue =
            $rows->sum(
                'outstanding_due'
            );


        $collectionRate =
            $plannedRevenue > 0
                ? (
                    $collectedRevenue /
                    $plannedRevenue
                ) * 100
                : 0;


        $averageRevenuePerPatient =
            $totalPatients > 0
                ? (
                    $collectedRevenue /
                    $totalPatients
                )
                : 0;


        return [

            'total_mrs' =>
                $totalMrs,

            'total_patients' =>
                $totalPatients,

            'active_patients' =>
                $totalActive,

            'total_doctors' =>
                $totalDoctors,

            'payment_plans' =>
                $totalPlans,

            'paid_plans' =>
                $totalPaidPlans,

            'planned_revenue' =>
                $plannedRevenue,

            'collected_revenue' =>
                $collectedRevenue,

            'outstanding_due' =>
                $outstandingDue,

            'collection_rate' =>
                $collectionRate,

            'average_revenue_per_patient' =>
                $averageRevenuePerPatient,

        ];
    }


    /**
     * ==========================================================
     * Individual MR Performance
     * ==========================================================
     */
    public function getMrDetails(
        Request $request,
        string $mr
    ): array {

        $rows =
            $this->getPerformance($request);


        $row =
            $rows->firstWhere(
                'mr',
                $mr
            );


        if (!$row) {

            $row = [

                'mr' =>
                    $mr,

                'patients' =>
                    0,

                'active' =>
                    0,

                'inactive' =>
                    0,

                'doctors' =>
                    0,

                'payment_plans' =>
                    0,

                'paid_plans' =>
                    0,

                'planned_revenue' =>
                    0,

                'collected_revenue' =>
                    0,

                'outstanding_due' =>
                    0,

                'collection_rate' =>
                    0,

                'average_revenue_per_patient' =>
                    0,

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | Patients
        |--------------------------------------------------------------------------
        */

        $patientQuery =
            $this->getPatientQuery($request);


        if ($mr === 'Unassigned') {

            $patientQuery->where(function ($q) {

                $q->whereNull(
                    'doctors.marketing_representative_name'
                )

                ->orWhere(
                    'doctors.marketing_representative_name',
                    ''
                );

            });

        } else {

            $patientQuery->where(
                'doctors.marketing_representative_name',
                $mr
            );

        }


        $patients =
            $patientQuery
                ->orderBy(
                    'patients.FullName'
                )
                ->get();


        return [

            'performance' =>
                $row,

            'patients' =>
                $patients,

            'mr' =>
                $mr,

        ];
    }


    /**
     * ==========================================================
     * Export Data
     * ==========================================================
     */
    public function exportReportData(
        Request $request
    ) {

        return $this->getPerformance(
            $request
        );
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

            'summary' =>
                $this->getSummary(
                    $request
                ),

            'performance' =>
                $this->getPerformance(
                    $request
                ),

            'filters' => [

                'search' =>
                    $request->input(
                        'search'
                    ),

                'mr' =>
                    $request->input(
                        'mr'
                    ),

                'doctor' =>
                    $request->input(
                        'doctor'
                    ),

                'status' =>
                    $request->input(
                        'status'
                    ),

                'from' =>
                    $request->input(
                        'from'
                    ),

                'to' =>
                    $request->input(
                        'to'
                    ),

            ],

            'generatedAt' =>
                now(),

        ];
    }
}