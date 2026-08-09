<?php

namespace App\Http\Controllers\Reports;

use App\Exports\DeliveryPendingReportExport;
use App\Http\Controllers\Controller;
use App\Services\Reports\DeliveryPendingReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DeliveryPendingReportController extends Controller
{
    protected DeliveryPendingReportService $deliveryPendingReportService;

    public function __construct(
        DeliveryPendingReportService $deliveryPendingReportService
    ) {
        $this->deliveryPendingReportService =
            $deliveryPendingReportService;
    }


    /**
     * ==========================================================
     * Delivery Pending Report
     * ==========================================================
     */
    public function index(Request $request)
    {
        $report = $this->deliveryPendingReportService
            ->getReport($request);

        return view(
            'admin.reports.delivery-pending.index',
            $report
        );
    }


    /**
     * ==========================================================
     * Excel Export
     * ==========================================================
     */
    public function exportExcel(Request $request)
    {
        $payments = $this->deliveryPendingReportService
            ->getReportData($request);

        return Excel::download(
            new DeliveryPendingReportExport($payments),
            'delivery-pending-report.xlsx'
        );
    }


    /**
     * ==========================================================
     * PDF Export
     * ==========================================================
     */
    public function exportPdf(Request $request)
    {
        $payments = $this->deliveryPendingReportService
            ->getReportData($request);

        $summary = $this->deliveryPendingReportService
            ->getSummary($payments);

        $data = [

            'payments' => $payments,

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

                'delivery_status' =>
                    $request->input('delivery_status'),

                'date_from' =>
                    $request->input('date_from'),

                'date_to' =>
                    $request->input('date_to'),

            ],

            'generatedAt' => now(),

        ];

        $pdf = Pdf::loadView(
            'admin.reports.delivery-pending.pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'delivery-pending-report.pdf'
        );
    }


    /**
     * ==========================================================
     * Delivery Pending Details
     * ==========================================================
     */
    public function show(int $id)
    {
        $payment = \App\Models\PaymentPlan::query()
            ->with([
                'patient',
                'payments',
                'deliveries',
            ])
            ->where('is_closed', false)
            ->findOrFail($id);

        $stats = $this->deliveryPendingReportService
            ->calculateDeliveryStats($payment);

        foreach ($stats as $key => $value) {

            $payment->{$key} = $value;

        }

        return view(
            'admin.reports.delivery-pending.show',
            compact('payment')
        );
    }
}