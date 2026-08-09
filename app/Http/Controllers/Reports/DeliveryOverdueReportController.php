<?php

namespace App\Http\Controllers\Reports;

use App\Exports\DeliveryOverdueReportExport;
use App\Http\Controllers\Controller;
use App\Models\PaymentPlan;
use App\Services\Reports\DeliveryOverdueReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DeliveryOverdueReportController extends Controller
{
    protected DeliveryOverdueReportService $deliveryOverdueReportService;

    public function __construct(
        DeliveryOverdueReportService $deliveryOverdueReportService
    ) {
        $this->deliveryOverdueReportService =
            $deliveryOverdueReportService;
    }


    /**
     * ==========================================================
     * Delivery Overdue Report
     * ==========================================================
     */
    public function index(Request $request)
    {
        $cases = $this->deliveryOverdueReportService
            ->getOverdueCases($request);

        $summary = $this->deliveryOverdueReportService
            ->getSummary($request);

        $doctors = $this->deliveryOverdueReportService
            ->getDoctorOptions();

        /*
        |--------------------------------------------------------------------------
        | Manual Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = 15;

        $page = max(
            1,
            (int) $request->input('page', 1)
        );

        $total = $cases->count();

        $items = $cases
            ->slice(
                ($page - 1) * $perPage,
                $perPage
            )
            ->values();

        $payments = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view(
            'admin.reports.delivery-overdue.index',
            compact(
                'payments',
                'summary',
                'doctors'
            )
        );
    }


    /**
     * ==========================================================
     * Excel Export
     * ==========================================================
     */
    public function exportExcel(Request $request)
    {
        $cases = $this->deliveryOverdueReportService
            ->getOverdueCases($request);

        return Excel::download(
            new DeliveryOverdueReportExport($cases),
            'delivery-overdue-report.xlsx'
        );
    }


    /**
     * ==========================================================
     * PDF Export
     * ==========================================================
     */
    public function exportPdf(Request $request)
    {
        $cases = $this->deliveryOverdueReportService
            ->getOverdueCases($request);

        $summary = $this->deliveryOverdueReportService
            ->getSummary($request);

        $data = [

            'payments' => $cases,

            'summary' => $summary,

            'filters' => [

                'search' =>
                    $request->input('search'),

                'doctor' =>
                    $request->input('doctor'),

                'payment_method' =>
                    $request->input('payment_method'),

                'is_installment' =>
                    $request->input('is_installment'),

                'date_from' =>
                    $request->input('date_from'),

                'date_to' =>
                    $request->input('date_to'),

            ],

            'generatedAt' => now(),

        ];

        $pdf = Pdf::loadView(
            'admin.reports.delivery-overdue.pdf',
            $data
        )->setPaper(
            'a4',
            'landscape'
        );

        return $pdf->download(
            'delivery-overdue-report.pdf'
        );
    }


    /**
     * ==========================================================
     * Overdue Case Details
     * ==========================================================
     */
    public function show(int $id)
    {
        $payment = PaymentPlan::query()
            ->with([
                'patient',
                'payments',
                'deliveries',
            ])
            ->where('is_closed', false)
            ->findOrFail($id);

        $stats = $this->deliveryOverdueReportService
            ->calculateOverdueStats($payment);

        foreach ($stats as $key => $value) {

            $payment->{$key} = $value;

        }

        /*
        |--------------------------------------------------------------------------
        | Make sure this record is actually overdue
        |--------------------------------------------------------------------------
        */

        if (!$payment->is_overdue) {

            abort(404);

        }

        return view(
            'admin.reports.delivery-overdue.show',
            compact('payment')
        );
    }
}