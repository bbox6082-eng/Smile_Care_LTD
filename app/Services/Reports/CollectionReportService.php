<?php

namespace App\Services\Reports;

use App\Models\PaymentPlanPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CollectionReportService
{
    /**
     * ==========================================================
     * Base Query
     * ==========================================================
     */
    public function getQuery(Request $request): Builder
    {
        $query = PaymentPlanPayment::query()
            ->with([
                'paymentPlan.patient',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->whereHas('paymentPlan', function ($plan) use ($search) {

                $plan->where('predict3d_id', 'like', "%{$search}%")
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
        | Collection Period
        |--------------------------------------------------------------------------
        */

        if ($request->filled('period')) {

            switch ($request->period) {

                case 'today':

                    $query->whereDate(
                        'payment_date',
                        Carbon::today()
                    );

                    break;

                case 'weekly':

                    $query->whereBetween(
                        'payment_date',
                        [
                            Carbon::now()->startOfWeek(),
                            Carbon::now()->endOfWeek()
                        ]
                    );

                    break;

                case 'monthly':

                    $query->whereMonth(
                        'payment_date',
                        Carbon::now()->month
                    )->whereYear(
                        'payment_date',
                        Carbon::now()->year
                    );

                    break;

                case 'yearly':

                    $query->whereYear(
                        'payment_date',
                        Carbon::now()->year
                    );

                    break;

            }

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

        return $query->latest('payment_date');
    }

    /**
     * ==========================================================
     * Summary Cards
     * ==========================================================
     */
    public function getSummary(Builder $query): array
    {
        $payments = (clone $query)->get();

        $today = Carbon::today();

        return [

            'total_collection' => $payments->sum('amount'),

            'today_collection' => $payments
                ->filter(function ($payment) use ($today) {

                    return optional($payment->payment_date)
                        ->isSameDay($today);

                })
                ->sum('amount'),

            'monthly_collection' => $payments
                ->filter(function ($payment) {

                    return optional($payment->payment_date)
                        ->isCurrentMonth();

                })
                ->sum('amount'),

            'total_transactions' => $payments->count(),

        ];
    }

    /**
     * ==========================================================
     * Collection Trend Chart
     * ==========================================================
     */
    public function getCollectionTrendChart(Builder $query): array
    {
        return (clone $query)
            ->reorder()
            ->selectRaw('DATE(payment_date) as report_date')
            ->selectRaw('SUM(amount) as total_collection')
            ->groupBy('report_date')
            ->orderBy('report_date')
            ->get()
            ->map(function ($row) {

                return [

                    'label' => Carbon::parse(
                        $row->report_date
                    )->format('d M'),

                    'value' => (float) $row->total_collection,

                ];

            })
            ->toArray();
    }

    /**
     * ==========================================================
     * Monthly Collection Chart
     * ==========================================================
     */
    public function getMonthlyCollectionChart(Builder $query): array
    {
        return (clone $query)
            ->reorder()
            ->selectRaw('YEAR(payment_date) as year')
            ->selectRaw('MONTH(payment_date) as month')
            ->selectRaw('SUM(amount) as total_collection')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($row) {

                return [

                    'label' => Carbon::create()
                        ->month($row->month)
                        ->format('M'),

                    'value' => (float) $row->total_collection,

                ];

            })
            ->toArray();
    }

    /**
     * ==========================================================
     * Payment Method Distribution
     * ==========================================================
     */
    public function getPaymentMethodChart(Builder $query): array
    {
        return (clone $query)
            ->reorder()
            ->select(
                'payment_method',
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('payment_method')
            ->get()
            ->mapWithKeys(function ($row) {

                return [

                    ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $row->payment_method
                        )
                    ) => (float) $row->total_amount

                ];

            })
            ->toArray();
    }
        /**
     * ==========================================================
     * Paginated Collections
     * ==========================================================
     */
    public function getCollections(Builder $query)
    {
        return $query->paginate(15);
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
     * Main Report
     * ==========================================================
     */
    public function getReport(Request $request): array
    {
        $query = $this->getQuery($request);

        return [

            'summary' => $this->getSummary(clone $query),

            'collections' => $this->getCollections(clone $query),

            'trendChart' => $this->getCollectionTrendChart(clone $query),

            'monthlyChart' => $this->getMonthlyCollectionChart(clone $query),

            'methodChart' => $this->getPaymentMethodChart(clone $query),

        ];
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

            'collections' => $query->get(),

            'summary' => $this->getSummary(clone $query),

            'generatedAt' => now(),

        ];
    }

    /**
     * ==========================================================
     * View Details
     * ==========================================================
     */
    public function getDetails(int $id): PaymentPlanPayment
    {
        return PaymentPlanPayment::with([
            'paymentPlan.patient',
        ])->findOrFail($id);
    }
}
    