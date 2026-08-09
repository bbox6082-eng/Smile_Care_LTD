<?php

namespace App\Services\Reports;

use App\Models\PaymentPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ActiveCaseReportService
{
    /**
     * ==========================================================
     * Base Query
     * ==========================================================
     *
     * An active case is a payment plan that has not been closed.
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
     * Active Case Summary
     * ==========================================================
     *
     * Provides summary statistics for the filtered active cases.
     */
    public function getSummary(Request $request): array
    {
        $query = $this->getQuery($request);

        $totalCases = (clone $query)->count();

        $totalDoctors = (clone $query)
            ->whereHas('patient', function ($q) {
                $q->whereNotNull('DoctorName')
                  ->where('DoctorName', '!=', '');
            })
            ->with('patient')
            ->get()
            ->pluck('patient.DoctorName')
            ->filter()
            ->unique()
            ->count();

        $totalAmount = (clone $query)->sum('total_amount');

        $remainingAmount = (clone $query)->sum('remaining_amount');

        $paidAmount = $totalAmount - $remainingAmount;

        $installmentCases = (clone $query)
            ->where('is_installment', true)
            ->count();

        $fullPaymentCases = (clone $query)
            ->where('is_installment', false)
            ->count();

        $overdueCases = (clone $query)
            ->whereNotNull('next_payment_date')
            ->whereDate('next_payment_date', '<', now()->toDateString())
            ->where('remaining_amount', '>', 0)
            ->count();

        return [
            'total_cases' => $totalCases,

            'total_doctors' => $totalDoctors,

            'total_amount' => $totalAmount,

            'paid_amount' => $paidAmount,

            'remaining_amount' => $remainingAmount,

            'installment_cases' => $installmentCases,

            'full_payment_cases' => $fullPaymentCases,

            'overdue_cases' => $overdueCases,
        ];
    }


    /**
     * ==========================================================
     * Active Case Records
     * ==========================================================
     *
     * Returns paginated active case records.
     */
    public function getCases(Request $request)
    {
        return $this->getQuery($request)
            ->paginate(15)
            ->withQueryString();
    }

        /**
     * ==========================================================
     * Filter Options
     * ==========================================================
     *
     * Provides dropdown values for the Active Case report.
     */
    public function getFilterOptions(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Doctors
        |--------------------------------------------------------------------------
        */

        $doctors = PaymentPlan::query()
            ->where('is_closed', false)
            ->whereHas('patient', function ($q) {
                $q->whereNotNull('DoctorName')
                    ->where('DoctorName', '!=', '');
            })
            ->with('patient')
            ->get()
            ->pluck('patient.DoctorName')
            ->filter()
            ->unique()
            ->sort()
            ->values();


        return [

            /*
            |--------------------------------------------------------------------------
            | Doctors
            |--------------------------------------------------------------------------
            */

            'doctors' => $doctors,


            /*
            |--------------------------------------------------------------------------
            | Payment Methods
            |--------------------------------------------------------------------------
            */

            'payment_methods' => [
                'cash',
                'card',
                'bank_transfer',
                'mobile_banking',
            ],


            /*
            |--------------------------------------------------------------------------
            | Installment Options
            |--------------------------------------------------------------------------
            */

            'installment_options' => [
                '1' => 'Installment',
                '0' => 'Full Payment',
            ],

        ];
    }


    /**
     * ==========================================================
     * Complete Report
     * ==========================================================
     *
     * Prepares all data required by the report index page.
     */
    public function getReport(Request $request): array
    {
        return [

            'cases' => $this->getCases($request),

            'summary' => $this->getSummary($request),

            'filters' => $this->getFilterOptions(),

        ];
    }


    /**
     * ==========================================================
     * Case Details
     * ==========================================================
     *
     * Returns one active case with related patient,
     * payments and delivery information.
     */
    public function getDetails(int $id): PaymentPlan
    {
        return PaymentPlan::query()
            ->with([
                'patient',
                'payments',
                'deliveries',
            ])
            ->where('is_closed', false)
            ->where('id', $id)
            ->firstOrFail();
    }


    /**
     * ==========================================================
     * Export Report Data
     * ==========================================================
     *
     * Returns all filtered active cases for Excel export.
     */
    public function exportReportData(Request $request)
    {
        return $this->getQuery($request)
            ->get();
    }


    /**
     * ==========================================================
     * PDF Report Data
     * ==========================================================
     *
     * Prepares all data required by the PDF view.
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