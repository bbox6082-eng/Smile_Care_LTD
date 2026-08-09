<?php

namespace App\Services\Reports;

use App\Models\PaymentPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CompletedCaseReportService
{
    /**
     * ==========================================================
     * Base Query
     * ==========================================================
     *
     * A completed case is a payment plan that has been closed.
     */
    public function getQuery(Request $request): Builder
    {
        $query = PaymentPlan::query()
            ->with([
                'patient',
                'payments',
                'deliveries',
            ])
            ->where('is_closed', true);

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
                (bool) $request->is_installment
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

        /*
        |--------------------------------------------------------------------------
        | Default Sorting
        |--------------------------------------------------------------------------
        */

        return $query
            ->orderByDesc('created_at');
    }

        /**
     * ==========================================================
     * Summary
     * ==========================================================
     */
    public function getSummary(Request $request): array
    {
        $query = $this->getQuery($request);

        $cases = $query->get();

        $totalCases = $cases->count();

        $totalDoctors = $cases
            ->map(function ($case) {
                return $case->patient?->DoctorName;
            })
            ->filter()
            ->unique()
            ->count();

        $totalAmount = $cases->sum(function ($case) {
            return (float) $case->total_amount;
        });

        $remainingAmount = $cases->sum(function ($case) {
            return (float) $case->remaining_amount;
        });

        $paidAmount = max(
            0,
            $totalAmount - $remainingAmount
        );

        $installmentCases = $cases
            ->where('is_installment', true)
            ->count();

        $fullPaymentCases = $cases
            ->where('is_installment', false)
            ->count();

        $totalPayments = $cases->sum(function ($case) {
            return $case->payments->count();
        });

        $totalDeliveries = $cases->sum(function ($case) {
            return $case->deliveries->count();
        });

        return [
            'total_cases'       => $totalCases,
            'total_doctors'     => $totalDoctors,
            'total_amount'      => $totalAmount,
            'paid_amount'       => $paidAmount,
            'remaining_amount'  => $remainingAmount,
            'installment_cases' => $installmentCases,
            'full_payment_cases'=> $fullPaymentCases,
            'total_payments'    => $totalPayments,
            'total_deliveries'  => $totalDeliveries,
        ];
    }


    /**
     * ==========================================================
     * Filter Options
     * ==========================================================
     */
    public function getFilters(): array
    {
        $doctors = PaymentPlan::query()
            ->where('is_closed', true)
            ->with('patient')
            ->get()
            ->map(function ($case) {
                return $case->patient?->DoctorName;
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $paymentMethods = PaymentPlan::query()
            ->where('is_closed', true)
            ->whereNotNull('payment_method')
            ->distinct()
            ->orderBy('payment_method')
            ->pluck('payment_method');

        return [
            'doctors' => $doctors,

            'payment_methods' => $paymentMethods,

            'installment_options' => [
                '1' => 'Installment',
                '0' => 'Full Payment',
            ],
        ];
    }


    /**
     * ==========================================================
     * Pagination
     * ==========================================================
     */
    public function getPaginatedCases(
        Request $request,
        int $perPage = 15
    ) {
        return $this->getQuery($request)
            ->paginate($perPage)
            ->withQueryString();
    }

        /**
     * ==========================================================
     * Complete Report Data
     * ==========================================================
     */
    public function getReport(Request $request): array
    {
        return [
            'cases' => $this->getPaginatedCases($request),

            'summary' => $this->getSummary($request),

            'filters' => $this->getFilters(),
        ];
    }


    /**
     * ==========================================================
     * Case Details
     * ==========================================================
     *
     * Returns one completed case with its related
     * patient, payments and deliveries.
     */
    public function getDetails(int $id): PaymentPlan
    {
        return PaymentPlan::query()
            ->with([
                'patient',
                'payments',
                'deliveries',
            ])
            ->where('is_closed', true)
            ->where('id', $id)
            ->firstOrFail();
    }


    /**
     * ==========================================================
     * Export Data
     * ==========================================================
     *
     * Returns all filtered completed cases for Excel.
     */
    public function exportReportData(Request $request)
    {
        return $this->getQuery($request)
            ->get();
    }


    /**
     * ==========================================================
     * PDF Data
     * ==========================================================
     *
     * Prepares all information required by the PDF view.
     */
    public function exportPdfData(Request $request): array
    {
        return [
            'cases' => $this->getQuery($request)->get(),

            'summary' => $this->getSummary($request),

            'filters' => [
                'search' => $request->input('search'),

                'doctor' => $request->input('doctor'),

                'payment_method' => $request->input('payment_method'),

                'is_installment' => $request->input('is_installment'),

                'date_from' => $request->input('date_from'),

                'date_to' => $request->input('date_to'),
            ],

            'generated_at' => now(),
        ];
    }
}