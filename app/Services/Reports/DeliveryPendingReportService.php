<?php

namespace App\Services\Reports;

use App\Models\PaymentPlan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DeliveryPendingReportService
{
    /**
     * ==========================================================
     * Base Query
     * ==========================================================
     *
     * Delivery is pending when at least one upper or lower case
     * remains undelivered.
     */
    public function getQuery(Request $request): Builder
    {
        $query = PaymentPlan::query()
            ->with([
                'patient',
                'payments',
                'deliveries',
            ])
            ->where('is_closed', false)
            ->whereHas('patient', function ($q) {

                $q->where(function ($query) {

                    $query
                        ->where('UpperCases', '>', 0)
                        ->orWhere('LowerCases', '>', 0);

                });

            });

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
        | Delivery Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('delivery_status')) {

            if ($request->delivery_status === 'upper_pending') {

                $query->whereHas('patient', function ($q) {

                    $q->where('UpperCases', '>', 0);

                });

            } elseif ($request->delivery_status === 'lower_pending') {

                $query->whereHas('patient', function ($q) {

                    $q->where('LowerCases', '>', 0);

                });

            } elseif ($request->delivery_status === 'both_pending') {

                $query->whereHas('patient', function ($q) {

                    $q->where('UpperCases', '>', 0)
                        ->where('LowerCases', '>', 0);

                });

            }

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
     * Calculate Delivery Statistics
     * ==========================================================
     */
    public function calculateDeliveryStats($paymentPlan): array
    {
        $patient = $paymentPlan->patient;

        $totalUpper = (int) ($patient?->UpperCases ?? 0);
        $totalLower = (int) ($patient?->LowerCases ?? 0);

        $deliveredUpper = (int) $paymentPlan->deliveries->sum(
            'upper_delivered'
        );

        $deliveredLower = (int) $paymentPlan->deliveries->sum(
            'lower_delivered'
        );

        $remainingUpper = max(
            0,
            $totalUpper - $deliveredUpper
        );

        $remainingLower = max(
            0,
            $totalLower - $deliveredLower
        );

        $totalCases = $totalUpper + $totalLower;

        $deliveredCases = $deliveredUpper + $deliveredLower;

        $remainingCases = $remainingUpper + $remainingLower;

        $deliveryPercentage = $totalCases > 0
            ? round(
                ($deliveredCases / $totalCases) * 100,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Delivery Status
        |--------------------------------------------------------------------------
        */

        if ($remainingUpper > 0 && $remainingLower > 0) {

            $deliveryStatus = 'both_pending';

        } elseif ($remainingUpper > 0) {

            $deliveryStatus = 'upper_pending';

        } elseif ($remainingLower > 0) {

            $deliveryStatus = 'lower_pending';

        } else {

            $deliveryStatus = 'completed';

        }

        return [

            'total_upper' => $totalUpper,

            'total_lower' => $totalLower,

            'delivered_upper' => $deliveredUpper,

            'delivered_lower' => $deliveredLower,

            'remaining_upper' => $remainingUpper,

            'remaining_lower' => $remainingLower,

            'total_cases' => $totalCases,

            'delivered_cases' => $deliveredCases,

            'remaining_cases' => $remainingCases,

            'delivery_percentage' => $deliveryPercentage,

            'delivery_status' => $deliveryStatus,

        ];
    }


    /**
     * ==========================================================
     * Add Delivery Statistics To Collection
     * ==========================================================
     */
    public function getReportData(Request $request)
    {
        $payments = $this->getQuery($request)->get();

        return $payments->map(function ($payment) {

            $stats = $this->calculateDeliveryStats(
                $payment
            );

            foreach ($stats as $key => $value) {

                $payment->{$key} = $value;

            }

            return $payment;

        });
    }


    /**
     * ==========================================================
     * Summary
     * ==========================================================
     */
    public function getSummary($payments): array
    {
        return [

            'total_cases' => $payments->count(),

            'total_upper' => (int) $payments->sum(
                'total_upper'
            ),

            'total_lower' => (int) $payments->sum(
                'total_lower'
            ),

            'delivered_upper' => (int) $payments->sum(
                'delivered_upper'
            ),

            'delivered_lower' => (int) $payments->sum(
                'delivered_lower'
            ),

            'remaining_upper' => (int) $payments->sum(
                'remaining_upper'
            ),

            'remaining_lower' => (int) $payments->sum(
                'remaining_lower'
            ),

            'total_pending' => (int) $payments->sum(
                'remaining_cases'
            ),

            'average_delivery_percentage' => $payments->count()
                ? round(
                    $payments->avg('delivery_percentage'),
                    2
                )
                : 0,

        ];
    }

        /**
     * ==========================================================
     * Doctor Options
     * ==========================================================
     */
    public function getDoctors(): array
    {
        return PaymentPlan::query()
            ->with('patient')
            ->where('is_closed', false)
            ->get()
            ->map(function ($payment) {

                return optional($payment->patient)->DoctorName;

            })
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }


    /**
     * ==========================================================
     * Paginated Delivery Pending Records
     * ==========================================================
     */
    public function getPayments(Request $request)
    {
        $payments = $this->getReportData($request);

        $page = (int) $request->input('page', 1);

        $perPage = 15;

        $total = $payments->count();

        $items = $payments
            ->slice(
                ($page - 1) * $perPage,
                $perPage
            )
            ->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }


    /**
     * ==========================================================
     * Main Report
     * ==========================================================
     */
    public function getReport(Request $request): array
    {
        $allPayments = $this->getReportData($request);

        return [

            'summary' => $this->getSummary(
                $allPayments
            ),

            'payments' => $this->getPayments(
                $request
            ),

            'doctors' => $this->getDoctors(),

        ];
    }
}