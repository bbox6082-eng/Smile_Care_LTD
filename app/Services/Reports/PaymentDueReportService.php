<?php

namespace App\Services\Reports;

use App\Models\PaymentPlan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaymentDueReportService
{
    /**
     * ==========================================================
     * Base Query
     * ==========================================================
     *
     * A payment-due case has an outstanding balance.
     */
    public function getQuery(Request $request): Builder
    {
        $query = PaymentPlan::query()
            ->with([
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

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        return $query
            ->orderByDesc('created_at');
    }

        /**
     * ==========================================================
     * Summary Cards
     * ==========================================================
     */
    public function getSummary(Builder $query): array
    {
        $payments = $query->get();

        $totalOutstanding = $payments->sum('remaining_amount');

        $overduePayments = $payments->filter(function ($payment) {

            return $payment->next_payment_date
                && Carbon::parse($payment->next_payment_date)
                    ->lt(Carbon::today());

        });

        $upcomingPayments = $payments->filter(function ($payment) {

            return $payment->next_payment_date
                && Carbon::parse($payment->next_payment_date)
                    ->gte(Carbon::today());

        });

        $overdueAmount = $overduePayments
            ->sum('remaining_amount');

        $upcomingAmount = $upcomingPayments
            ->sum('remaining_amount');

        $patientsWithDue = $payments->count();

        $averageDue = $patientsWithDue > 0
            ? $totalOutstanding / $patientsWithDue
            : 0;

        return [

            'total_outstanding' => $totalOutstanding,

            'overdue_amount' => $overdueAmount,

            'upcoming_amount' => $upcomingAmount,

            'patients_with_due' => $patientsWithDue,

            'average_due' => $averageDue,

            'overdue_count' => $overduePayments->count(),

            'upcoming_count' => $upcomingPayments->count(),

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
            ->where('remaining_amount', '>', 0)
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
     * Paginated Due Payments
     * ==========================================================
     */
    public function getPayments(Builder $query)
    {
        return $query->paginate(15)->withQueryString();
    }


    /**
     * ==========================================================
     * Main Report
     * ==========================================================
     */
    public function getReport(Request $request): array
    {
        $query = $this->getQuery($request);

        return [

            'summary' => $this->getSummary(
                clone $query
            ),

            'payments' => $this->getPayments(
                clone $query
            ),

            'doctors' => $this->getDoctors(),

        ];
    }

        /**
     * ==========================================================
     * Excel Export Data
     * ==========================================================
     */
    public function exportReportData(Request $request)
    {
        return $this->getQuery($request)->get();
    }


    /**
     * ==========================================================
     * PDF Export Data
     * ==========================================================
     */
    public function exportPdfData(Request $request): array
    {
        $query = $this->getQuery($request);

        return [

            'payments' => $query->get(),

            'summary' => $this->getSummary(
                clone $query
            ),

            'filters' => [

                'search' => $request->input('search'),

                'doctor' => $request->input('doctor'),

                'payment_method' =>
                    $request->input('payment_method'),

                'is_installment' =>
                    $request->input('is_installment'),

                'status' =>
                    $request->input('status'),

                'date_from' =>
                    $request->input('date_from'),

                'date_to' =>
                    $request->input('date_to'),

            ],

            'generatedAt' => now(),

        ];
    }


    /**
     * ==========================================================
     * View Payment Due Details
     * ==========================================================
     */
    public function getDetails(int $id): PaymentPlan
    {
        $payment = PaymentPlan::query()
            ->with([
                'patient',
                'payments',
                'deliveries',
            ])
            ->where('remaining_amount', '>', 0)
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Calculated Fields
        |--------------------------------------------------------------------------
        */

        $payment->total_paid = $payment->payments
            ->sum('amount');

        $payment->completion = $payment->total_amount > 0
            ? round(
                (
                    $payment->total_paid /
                    $payment->total_amount
                ) * 100,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Due Status
        |--------------------------------------------------------------------------
        */

        if (
            $payment->next_payment_date &&
            Carbon::parse($payment->next_payment_date)
                ->lt(Carbon::today())
        ) {

            $payment->due_status = 'overdue';

        } elseif ($payment->next_payment_date) {

            $payment->due_status = 'upcoming';

        } else {

            $payment->due_status = 'pending';

        }

        return $payment;
    }
}