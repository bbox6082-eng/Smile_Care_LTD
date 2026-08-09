<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\PatientByDoctorReportService;
use App\Exports\PatientByDoctorReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PatientByDoctorReportController extends Controller
{
    protected PatientByDoctorReportService $patientByDoctorReportService;

    public function __construct(
        PatientByDoctorReportService $patientByDoctorReportService
    ) {
        $this->patientByDoctorReportService = $patientByDoctorReportService;
    }


    /**
     * ==========================================================
     * Patient by Doctor Report Dashboard
     * ==========================================================
     */
    public function index(Request $request)
    {
        $report = $this->patientByDoctorReportService
            ->getReport($request);

        return view(
            'admin.reports.patient-by-doctor.index',
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
            new PatientByDoctorReportExport(
                $this->patientByDoctorReportService
                    ->exportReportData($request)
            ),
            'patient-by-doctor-report.xlsx'
        );
    }


    /**
     * ==========================================================
     * Export PDF
     * ==========================================================
     */
    public function exportPdf(Request $request)
    {
        $data = $this->patientByDoctorReportService
            ->exportPdfData($request);

        $pdf = Pdf::loadView(
            'admin.reports.patient-by-doctor.pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'patient-by-doctor-report.pdf'
        );
    }


    /**
     * ==========================================================
     * Patient Details
     * ==========================================================
     */
    public function show(string $predict3dId)
    {
        $patient = $this->patientByDoctorReportService
            ->getDetails($predict3dId);

        return view(
            'admin.reports.patient-by-doctor.show',
            compact('patient')
        );
    }
}