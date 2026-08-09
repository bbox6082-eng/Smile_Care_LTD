<?php

namespace App\Services\Reports;

use App\Models\PaymentPlan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DeliveryOverdueReportService
{
    /**
     * ==========================================================
     * Base Query
     * ==========================================================
     */
    public function getQuery(Request $request): Builder
    {
        $query = PaymentPlan::query()
            ->with([
                'patient',
                'payments',
                'deliveries',
            ])
            ->where('is_closed', false);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'predict3d_id',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('patient', function ($patientQuery) use ($search) {

                    $patientQuery
                        ->where('FullName', 'like', "%{$search}%")
                        ->orWhere('DoctorName', 'like', "%{$search}%")
                        ->orWhere('PhoneNumber', 'like', "%{$search}%");

                });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Doctor
        |--------------------------------------------------------------------------
        */

        if ($request->filled('doctor')) {

            $query->whereHas('patient', function ($q) use ($request) {

                $q->where(
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
        | Date From
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_to')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        return $query->orderByDesc('created_at');
    }


    /**
     * ==========================================================
     * Calculate Overdue Delivery Information
     * ==========================================================
     *
     * Uses the existing DentLab-OS delivery reminder logic:
     *
     * Last Delivery Date
     * +
     * (max latest upper/lower delivered × days per aligner)
     * =
     * Next Delivery Due Date
     */
    public function calculateOverdueStats($paymentPlan): array
    {
        $latestDelivery = $paymentPlan->deliveries
            ->sortByDesc(function ($delivery) {

                return [
                    $delivery->delivery_date,
                    $delivery->id,
                ];

            })
            ->first();

        /*
        |--------------------------------------------------------------------------
        | No Delivery
        |--------------------------------------------------------------------------
        */

        if (!$latestDelivery || !$latestDelivery->delivery_date) {

            return [

                'latest_delivery_date' => null,

                'latest_upper_delivered' => 0,

                'latest_lower_delivered' => 0,

                'max_cases' => 0,

                'days_per_aligner' => (int) \Cache::get(
                    'days_per_aligner',
                    15
                ),

                'cycle_days' => 0,

                'next_delivery_due_date' => null,

                'next_delivery_days_left' => null,

                'overdue_days' => 0,

                'is_overdue' => false,

                'latest_delivery_paid_amount' => 0,

                'overdue_status' => 'no_delivery',

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Latest Delivery
        |--------------------------------------------------------------------------
        */

        $lastDeliveryDate = Carbon::parse(
            $latestDelivery->delivery_date
        )->startOfDay();

        $upper = (int) (
            $latestDelivery->upper_delivered ?? 0
        );

        $lower = (int) (
            $latestDelivery->lower_delivered ?? 0
        );

        $maxCases = max(
            $upper,
            $lower
        );

        /*
        |--------------------------------------------------------------------------
        | Delivery Cycle
        |--------------------------------------------------------------------------
        */

        $daysPerAligner = (int) \Cache::get(
            'days_per_aligner',
            15
        );

        $cycleDays = $maxCases * $daysPerAligner;

        $nextDue = $lastDeliveryDate
            ->copy()
            ->addDays($cycleDays);

        $daysLeft = Carbon::today()
            ->diffInDays(
                $nextDue,
                false
            );

        /*
        |--------------------------------------------------------------------------
        | Overdue
        |--------------------------------------------------------------------------
        */

        $isOverdue = $daysLeft < 0;

        $overdueDays = $isOverdue
            ? abs($daysLeft)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Latest Delivery Payment
        |--------------------------------------------------------------------------
        */

        $latestDeliveryPaidAmount = (float) (
            $latestDelivery->paid_amount ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($isOverdue) {

            $overdueStatus = 'overdue';

        } elseif (
            $latestDeliveryPaidAmount <= 0 &&
            $maxCases > 0
        ) {

            $overdueStatus = 'unpaid';

        } else {

            $overdueStatus = 'current';

        }

        return [

            'latest_delivery_date' =>
                $lastDeliveryDate->toDateString(),

            'latest_upper_delivered' =>
                $upper,

            'latest_lower_delivered' =>
                $lower,

            'max_cases' =>
                $maxCases,

            'days_per_aligner' =>
                $daysPerAligner,

            'cycle_days' =>
                $cycleDays,

            'next_delivery_due_date' =>
                $nextDue->toDateString(),

            'next_delivery_days_left' =>
                $daysLeft,

            'overdue_days' =>
                $overdueDays,

            'is_overdue' =>
                $isOverdue,

            'latest_delivery_paid_amount' =>
                $latestDeliveryPaidAmount,

            'overdue_status' =>
                $overdueStatus,

        ];
    }
        /**
     * ==========================================================
     * Get Overdue Cases
     * ==========================================================
     */
    public function getOverdueCases(Request $request)
    {
        $paymentPlans = $this->getQuery($request)
            ->get();

        return $paymentPlans
            ->map(function ($paymentPlan) {

                $stats = $this->calculateOverdueStats(
                    $paymentPlan
                );

                $paymentPlan->latest_delivery_date =
                    $stats['latest_delivery_date'];

                $paymentPlan->latest_upper_delivered =
                    $stats['latest_upper_delivered'];

                $paymentPlan->latest_lower_delivered =
                    $stats['latest_lower_delivered'];

                $paymentPlan->max_cases =
                    $stats['max_cases'];

                $paymentPlan->days_per_aligner =
                    $stats['days_per_aligner'];

                $paymentPlan->cycle_days =
                    $stats['cycle_days'];

                $paymentPlan->next_delivery_due_date =
                    $stats['next_delivery_due_date'];

                $paymentPlan->next_delivery_days_left =
                    $stats['next_delivery_days_left'];

                $paymentPlan->overdue_days =
                    $stats['overdue_days'];

                $paymentPlan->is_overdue =
                    $stats['is_overdue'];

                $paymentPlan->latest_delivery_paid_amount =
                    $stats['latest_delivery_paid_amount'];

                $paymentPlan->overdue_status =
                    $stats['overdue_status'];

                return $paymentPlan;
            })
            ->filter(function ($paymentPlan) {

                return $paymentPlan->is_overdue === true;

            })
            ->sortByDesc(function ($paymentPlan) {

                return $paymentPlan->overdue_days;

            })
            ->values();
    }


    /**
     * ==========================================================
     * Summary
     * ==========================================================
     */
    public function getSummary(Request $request): array
    {
        $cases = $this->getOverdueCases($request);

        $totalCases = $cases->count();

        $totalOverdueDays = $cases->sum(
            'overdue_days'
        );

        $averageOverdueDays = $totalCases > 0
            ? round(
                $totalOverdueDays / $totalCases,
                1
            )
            : 0;

        $maximumOverdueDays = $cases->max(
            'overdue_days'
        ) ?? 0;

        $totalUpperPending = $cases->sum(
            function ($case) {

                return max(
                    0,
                    (int) (
                        $case->max_cases ?? 0
                    )
                );

            }
        );

        return [

            'total_cases' =>
                $totalCases,

            'total_overdue_days' =>
                $totalOverdueDays,

            'average_overdue_days' =>
                $averageOverdueDays,

            'maximum_overdue_days' =>
                $maximumOverdueDays,

            'total_cases_pending' =>
                $totalUpperPending,

        ];
    }


    /**
     * ==========================================================
     * Doctor Options
     * ==========================================================
     */
    public function getDoctorOptions()
    {
        return PaymentPlan::query()
            ->where('is_closed', false)
            ->whereHas('patient')
            ->with('patient')
            ->get()
            ->map(function ($paymentPlan) {

                return optional(
                    $paymentPlan->patient
                )->DoctorName;

            })
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }
}