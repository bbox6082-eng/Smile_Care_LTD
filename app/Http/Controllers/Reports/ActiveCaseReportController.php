<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\ActiveCaseReportService;
use App\Exports\ActiveCaseReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ActiveCaseReportController extends Controller
{
    protected ActiveCaseReportService $activeCaseReportService;

    public function __construct(
        ActiveCaseReportService $activeCaseReportService
    ) {
        $this->activeCaseReportService = $activeCaseReportService;
    }


    /**
     * ==========================================================
     * Active Case Report Dashboard
     * ==========================================================
     */
    public function index(Request $request)
    {
        $report = $this->activeCaseReportService
            ->getReport($request);

        return view(
            'admin.reports.active-cases.index',
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
            new ActiveCaseReportExport(
                $this->activeCaseReportService
                    ->exportReportData($request)
            ),
            'active-case-report.xlsx'
        );
    }


    /**
     * ==========================================================
     * Export PDF
     * ==========================================================
     */
    public function exportPdf(Request $request)
    {
        $data = $this->activeCaseReportService
            ->exportPdfData($request);

        $pdf = Pdf::loadView(
            'admin.reports.active-cases.pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'active-case-report.pdf'
        );
    }


    /**
     * ==========================================================
     * Case Details
     * ==========================================================
     */
    public function show(int $id)
    {
        $case = $this->activeCaseReportService
            ->getDetails($id);

        return view(
            'admin.reports.active-cases.show',
            compact('case')
        );
    }
}