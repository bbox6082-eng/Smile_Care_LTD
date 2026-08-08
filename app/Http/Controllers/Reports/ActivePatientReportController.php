<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\ActivePatientReportService;
use App\Exports\ActivePatientReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ActivePatientReportController extends Controller
{
    protected ActivePatientReportService $activePatientReportService;

    public function __construct(
        ActivePatientReportService $activePatientReportService
    ) {
        $this->activePatientReportService = $activePatientReportService;
    }


    /**
     * ==========================================================
     * Active Patient Report Dashboard
     * ==========================================================
     */
    public function index(Request $request)
    {
        $report = $this->activePatientReportService
            ->getReport($request);

        return view(
            'admin.reports.active-patients.index',
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
            new ActivePatientReportExport(
                $this->activePatientReportService
                    ->exportReportData($request)
            ),
            'active-patient-report.xlsx'
        );
    }


    /**
     * ==========================================================
     * Export PDF
     * ==========================================================
     */
    public function exportPdf(Request $request)
    {
        $data = $this->activePatientReportService
            ->exportPdfData($request);

        $pdf = Pdf::loadView(
            'admin.reports.active-patients.pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'active-patient-report.pdf'
        );
    }


    /**
     * ==========================================================
     * Patient Details
     * ==========================================================
     */
    public function show(string $predict3dId)
    {
        $patient = $this->activePatientReportService
            ->getDetails($predict3dId);

        return view(
            'admin.reports.active-patients.show',
            compact('patient')
        );
    }
}