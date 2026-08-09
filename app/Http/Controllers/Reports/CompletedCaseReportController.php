<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Exports\CompletedCaseReportExport;
use App\Services\Reports\CompletedCaseReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CompletedCaseReportController extends Controller
{
    protected CompletedCaseReportService $completedCaseReportService;

    public function __construct(
        CompletedCaseReportService $completedCaseReportService
    ) {
        $this->completedCaseReportService = $completedCaseReportService;
    }


    /**
     * ==========================================================
     * Completed Case Report
     * ==========================================================
     */
    public function index(Request $request)
    {
        $report = $this->completedCaseReportService
            ->getReport($request);

        return view(
            'admin.reports.completed-cases.index',
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
            new CompletedCaseReportExport(
                $this->completedCaseReportService
                    ->exportReportData($request)
            ),
            'completed-case-report.xlsx'
        );
    }


    /**
     * ==========================================================
     * PDF Export
     * ==========================================================
     */
    public function exportPdf(Request $request)
    {
        $data = $this->completedCaseReportService
            ->exportPdfData($request);

        $pdf = Pdf::loadView(
            'admin.reports.completed-cases.pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'completed-case-report.pdf'
        );
    }


    /**
     * ==========================================================
     * Completed Case Details
     * ==========================================================
     */
    public function show(int $id)
    {
        $case = $this->completedCaseReportService
            ->getDetails($id);

        return view(
            'admin.reports.completed-cases.show',
            compact('case')
        );
    }
}