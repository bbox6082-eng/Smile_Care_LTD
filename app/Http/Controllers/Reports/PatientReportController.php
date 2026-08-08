<?php

namespace App\Http\Controllers\Reports;

use App\Exports\PatientReportExport;
use App\Http\Controllers\Controller;
use App\Services\Reports\PatientReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PatientReportController extends Controller
{
    protected PatientReportService $patientReportService;

    public function __construct(
        PatientReportService $patientReportService
    ) {
        $this->patientReportService = $patientReportService;
    }


    /**
     * ==========================================================
     * Patient Report Dashboard
     * ==========================================================
     */
    public function index(Request $request)
    {
        $report = $this->patientReportService
            ->getReport($request);

        return view(
            'admin.reports.patient.index',
            $report
        );
    }


    /**
     * ==========================================================
     * Patient Details
     * ==========================================================
     */
    public function show(string $predict3dId)
    {
        $patient = $this->patientReportService
            ->getDetails($predict3dId);

        return view(
            'admin.reports.patient.show',
            compact('patient')
        );
    }


    /**
     * ==========================================================
     * Export Excel
     * ==========================================================
     */
    public function exportExcel(Request $request)
    {
        $patients = $this->patientReportService
            ->exportReportData($request);

        return Excel::download(
            new PatientReportExport($patients),
            'patient-report.xlsx'
        );
    }


    /**
     * ==========================================================
     * Export PDF
     * ==========================================================
     */
    public function exportPdf(Request $request)
    {
        $data = $this->patientReportService
            ->exportPdfData($request);

        $pdf = Pdf::loadView(
            'admin.reports.patient.pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'patient-report.pdf'
        );
    }
}