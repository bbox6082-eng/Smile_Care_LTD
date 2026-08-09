<?php

namespace App\Http\Controllers\Reports;

use App\Exports\PaymentDueReportExport;
use App\Http\Controllers\Controller;
use App\Services\Reports\PaymentDueReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PaymentDueReportController extends Controller
{
    protected PaymentDueReportService $paymentDueReportService;

    public function __construct(
        PaymentDueReportService $paymentDueReportService
    ) {
        $this->paymentDueReportService = $paymentDueReportService;
    }


    /**
     * ==========================================================
     * Payment Due Report
     * ==========================================================
     */
    public function index(Request $request)
    {
        $report = $this->paymentDueReportService
            ->getReport($request);

        return view(
            'admin.reports.payment-due.index',
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
        return Excel::download(
            new PaymentDueReportExport(
                $this->paymentDueReportService
                    ->exportReportData($request)
            ),
            'payment-due-report.xlsx'
        );
    }


    /**
     * ==========================================================
     * PDF Export
     * ==========================================================
     */
    public function exportPdf(Request $request)
    {
        $data = $this->paymentDueReportService
            ->exportPdfData($request);

        $pdf = Pdf::loadView(
            'admin.reports.payment-due.pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'payment-due-report.pdf'
        );
    }


    /**
     * ==========================================================
     * Payment Due Details
     * ==========================================================
     */
    public function show(int $id)
    {
        $payment = $this->paymentDueReportService
            ->getDetails($id);

        return view(
            'admin.reports.payment-due.show',
            compact('payment')
        );
    }
}