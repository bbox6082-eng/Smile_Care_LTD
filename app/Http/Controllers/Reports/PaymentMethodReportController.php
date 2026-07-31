<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\PaymentMethodReportService;
use App\Exports\PaymentMethodReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PaymentMethodReportController extends Controller
{
    protected PaymentMethodReportService $paymentMethodReportService;

    public function __construct(
        PaymentMethodReportService $paymentMethodReportService
    ) {
        $this->paymentMethodReportService = $paymentMethodReportService;
    }

    /**
     * ==========================================================
     * Report Dashboard
     * ==========================================================
     */
    public function index(Request $request)
    {
        $report = $this->paymentMethodReportService->getReport($request);

        return view(
            'admin.reports.payment-method.index',
            $report
        );
    }

    /**
     * ==========================================================
     * Export Excel
     * ==========================================================
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new PaymentMethodReportExport(
                $this->paymentMethodReportService
                    ->exportReportData($request)
            ),
            'payment-method-report.xlsx'
        );
    }

    /**
     * ==========================================================
     * Export PDF
     * ==========================================================
     */
    public function exportPdf(Request $request)
    {
        $data = $this->paymentMethodReportService
            ->exportPdfData($request);

        $pdf = Pdf::loadView(
            'admin.reports.payment-method.pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'payment-method-report.pdf'
        );
    }

    /**
     * ==========================================================
     * Details
     * ==========================================================
     */
    public function show(int $id)
    {
        $transaction = $this->paymentMethodReportService
            ->getDetails($id);

        return view(
            'admin.reports.payment-method.show',
            compact('transaction')
        );
    }
}