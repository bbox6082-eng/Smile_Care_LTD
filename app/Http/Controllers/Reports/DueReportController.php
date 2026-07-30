<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Reports\DueReportService;
use App\Exports\DueReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class DueReportController extends Controller
{
    /**
     * Service Instance
     */
    protected DueReportService $service;

    /**
     * Constructor
     */
    public function __construct(DueReportService $service)
    {
        $this->service = $service;
    }

    /**
     * ===========================================
     * Export Excel
     * ===========================================
     */
    public function exportExcel(Request $request)
    {
        $fileName = 'Due_Report_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

        return Excel::download(
            new DueReportExport($request),
            $fileName
        );
    }

    /**
     * ===========================================
     * Export PDF
     * ===========================================
     */
    public function exportPdf(Request $request)
    {
        $data = $this->service->exportPdfData($request);

        $pdf = Pdf::loadView(
            'admin.reports.due.pdf',
            $data
        )->setPaper('a4', 'landscape');

        $fileName = 'Due_Report_' . now()->format('Y_m_d_H_i_s') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * ===========================================
     * View Details
     * ===========================================
     */
    public function show($id)
    {
        $payment = $this->service->getDetails($id);

        return view(
            'admin.reports.due.show',
            compact('payment')
        );
    }

        /**
     * ===========================================
     * Display Due Report
     * ===========================================
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Filters
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'start_date'      => ['nullable', 'date'],
            'end_date'        => ['nullable', 'date', 'after_or_equal:start_date'],
            'payment_method'  => ['nullable', 'string'],
            'is_installment'  => ['nullable', 'in:0,1'],
            'status'          => ['nullable', 'in:overdue,upcoming'],
            'search'          => ['nullable', 'string'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Report Data
        |--------------------------------------------------------------------------
        */

        $report = $this->service->getReport($request);

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view('admin.reports.due.index', [
            'summary'      => $report['summary'],
            'payments'     => $report['payments'],
            'dueChart'     => $report['dueChart'],
            'doctorChart'  => $report['doctorChart'],
        ]);
    }
}