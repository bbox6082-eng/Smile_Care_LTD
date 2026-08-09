<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\InactivePatientReportService;
use App\Exports\InactivePatientReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InactivePatientReportController extends Controller
{
    protected InactivePatientReportService $inactivePatientReportService;

    public function __construct(
        InactivePatientReportService $inactivePatientReportService
    ) {
        $this->inactivePatientReportService = $inactivePatientReportService;
    }


    /**
     * ==========================================================
     * Inactive Patient Report Dashboard
     * ==========================================================
     */
    public function index(Request $request)
    {
        $report = $this->inactivePatientReportService
            ->getReport($request);

        return view(
            'admin.reports.inactive-patients.index',
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
            new InactivePatientReportExport(
                $this->inactivePatientReportService
                    ->exportReportData($request)
            ),
            'inactive-patient-report.xlsx'
        );
    }


    /**
     * ==========================================================
     * Export PDF
     * ==========================================================
     */
    public function exportPdf(Request $request)
    {
        $data = $this->inactivePatientReportService
            ->exportPdfData($request);

        $pdf = Pdf::loadView(
            'admin.reports.inactive-patients.pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'inactive-patient-report.pdf'
        );
    }


    /**
     * ==========================================================
     * Patient Details
     * ==========================================================
     */
    public function show(string $predict3dId)
    {
        $patient = $this->inactivePatientReportService
            ->getDetails($predict3dId);

        return view(
            'admin.reports.inactive-patients.show',
            compact('patient')
        );
    }
}