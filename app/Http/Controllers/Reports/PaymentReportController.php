<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\PaymentPlan;
use App\Models\PaymentPlanPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\PaymentReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Reports\PaymentReportService;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentReportController extends Controller
{
        protected PaymentReportService $service;

        public function __construct(PaymentReportService $service)
            {
                $this->service = $service;
            }

    /**
     * Export Payment Report to Excel
     */
    public function exportExcel(Request $request)
    {
        $fileName = 'Payment_Report_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

        return Excel::download(
            new PaymentReportExport($request),
            $fileName
        );
    }

    /**
     * Export Payment Report to PDF
     */

    public function exportPdf(Request $request)
    {
        $data = $this->service->exportPdfData($request);

        $pdf = Pdf::loadView(
            'admin.reports.payment.pdf',
            $data
        )->setPaper('a4', 'landscape');

        $fileName = 'Payment_Report_' . now()->format('Y_m_d_H_i_s') . '.pdf';

        return $pdf->download($fileName);
    }
        /**
     * Display Payment Report
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Filters
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'from'           => ['nullable', 'date'],
            'to'             => ['nullable', 'date', 'after_or_equal:from'],
            'payment_method' => ['nullable', 'string'],
            'status'         => ['nullable', 'in:Paid,Partial,Due'],
            'search'         => ['nullable', 'string'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Report Data From Service
        |--------------------------------------------------------------------------
        */

        $report = $this->service->getReport($request);

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view('admin.reports.payment.index', [
            'summary'      => $report['summary'],
            'payments'     => $report['payments'],
            'revenueChart' => $report['revenueChart'],
            'methodChart'  => $report['methodChart'],
        ]);
    }
}