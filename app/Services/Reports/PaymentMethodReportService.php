<?php

namespace App\Services\Reports;

use App\Models\PaymentPlanPayment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentMethodReportService
{
    /**
     * ==========================================================
     * Base Query
     * ==========================================================
     */
    public function getQuery(Request $request): Builder
    {
        $query = PaymentPlanPayment::with([
            'paymentPlan.patient',
            'creator',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->whereHas('paymentPlan', function ($plan) use ($search) {

                    $plan->where('predict3d_id', 'like', "%{$search}%");

                })

                ->orWhereHas('paymentPlan.patient', function ($patient) use ($search) {

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
        | Bank Name
        |--------------------------------------------------------------------------
        */

        if ($request->filled('bank_name')) {

            $query->where(
                'bank_name',
                $request->bank_name
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Mobile Provider
        |--------------------------------------------------------------------------
        */

        if ($request->filled('mobile_provider')) {

            $query->where(
                'mobile_provider',
                $request->mobile_provider
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        if ($request->filled('start_date')) {

            $query->whereDate(
                'payment_date',
                '>=',
                $request->start_date
            );

        }

        if ($request->filled('end_date')) {

            $query->whereDate(
                'payment_date',
                '<=',
                $request->end_date
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Quick Period Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('period')) {

            switch ($request->period) {

                case 'today':

                    $query->whereDate(
                        'payment_date',
                        today()
                    );

                    break;

                case 'weekly':

                    $query->whereBetween(
                        'payment_date',
                        [
                            now()->startOfWeek(),
                            now()->endOfWeek()
                        ]
                    );

                    break;

                case 'monthly':

                    $query->whereMonth(
                        'payment_date',
                        now()->month
                    )->whereYear(
                        'payment_date',
                        now()->year
                    );

                    break;

                case 'yearly':

                    $query->whereYear(
                        'payment_date',
                        now()->year
                    );

                    break;
            }

        }

        return $query;
    }

    /**
     * ==========================================================
     * Summary Cards
     * ==========================================================
     */
    public function getSummary(Builder $query): array
    {
        $summary = (clone $query)->selectRaw("
                SUM(CASE WHEN payment_method='cash'
                    THEN amount ELSE 0 END) AS cash_total,

                SUM(CASE WHEN payment_method='card'
                    THEN amount ELSE 0 END) AS card_total,

                SUM(CASE WHEN payment_method='bank_transfer'
                    THEN amount ELSE 0 END) AS bank_total,

                SUM(CASE WHEN payment_method='mobile_banking'
                    THEN amount ELSE 0 END) AS mobile_total,

                COUNT(*) AS total_transactions
            ")
            ->first();

        return [

            'cash_total' => $summary->cash_total ?? 0,

            'card_total' => $summary->card_total ?? 0,

            'bank_total' => $summary->bank_total ?? 0,

            'mobile_total' => $summary->mobile_total ?? 0,

            'total_transactions' => $summary->total_transactions ?? 0,

        ];
    }
        /**
     * ==========================================================
     * Payment Method Distribution Chart
     * ==========================================================
     */
    public function getPaymentMethodChart(Builder $query): array
    {
        return (clone $query)
            ->select(
                'payment_method',
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) {

                return [

                    'label' => ucwords(
                        str_replace('_', ' ', $row->payment_method)
                    ),

                    'value' => (float) $row->total,

                ];

            })
            ->toArray();
    }

    /**
     * ==========================================================
     * Monthly Payment Method Trend
     * ==========================================================
     */
    public function getMonthlyMethodChart(Builder $query): array
    {
        return (clone $query)
            ->selectRaw("
                DATE_FORMAT(payment_date,'%b') as month,
                SUM(amount) as total
            ")
            ->groupBy('month')
            ->orderByRaw('MIN(payment_date)')
            ->get()
            ->map(function ($row) {

                return [

                    'label' => $row->month,

                    'value' => (float) $row->total,

                ];

            })
            ->toArray();
    }

    /**
     * ==========================================================
     * Bank-wise Collection Chart
     * ==========================================================
     */
    public function getBankChart(Builder $query): array
    {
        return (clone $query)
            ->where('payment_method', 'bank_transfer')
            ->whereNotNull('bank_name')
            ->select(
                'bank_name',
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('bank_name')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) {

                return [

                    'label' => $row->bank_name,

                    'value' => (float) $row->total,

                ];

            })
            ->toArray();
    }

    /**
     * ==========================================================
     * Paginated Transactions
     * ==========================================================
     */
    public function getTransactions(Builder $query)
    {
        return (clone $query)
            ->latest('payment_date')
            ->paginate(15)
            ->withQueryString();
    }
        /**
     * ==========================================================
     * Export Report Data (Excel)
     * ==========================================================
     */
    public function exportReportData(Request $request)
    {
        return $this->getQuery($request)->get();
    }

    /**
     * ==========================================================
     * Complete Report
     * ==========================================================
     */
    public function getReport(Request $request): array
    {
        $query = $this->getQuery($request);

        return [

            'summary' => $this->getSummary(clone $query),

            'methodChart' => $this->getPaymentMethodChart(clone $query),

            'monthlyChart' => $this->getMonthlyMethodChart(clone $query),

            'bankChart' => $this->getBankChart(clone $query),

            'transactions' => $this->getTransactions(clone $query),

        ];
    }

    /**
     * ==========================================================
     * Export PDF Data
     * ==========================================================
     */
    public function exportPdfData(Request $request): array
    {
        $query = $this->getQuery($request);

        return [

            'summary' => $this->getSummary(clone $query),

            'transactions' => (clone $query)->get(),

            'generatedAt' => now(),

        ];
    }

    /**
     * ==========================================================
     * Transaction Details
     * ==========================================================
     */
    public function getDetails(int $id): PaymentPlanPayment
    {
        return PaymentPlanPayment::with([
            'paymentPlan.patient',
            'creator',
        ])->findOrFail($id);
    }
}