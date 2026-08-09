<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Exports\DoctorWisePatientsReportExport;
use App\Services\Reports\DoctorWisePatientsReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DoctorWisePatientsReportController extends Controller
{
    /**
     * Report Service
     */
    protected DoctorWisePatientsReportService $service;


    /**
     * Constructor
     */
    public function __construct(
        DoctorWisePatientsReportService $service
    ) {
        $this->service = $service;
    }


    /**
     * ==========================================================
     * Doctor-wise Patients Report
     * ==========================================================
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Filters
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'search'       => ['nullable', 'string', 'max:255'],
            'doctor'       => ['nullable', 'string', 'max:255'],
            'status'       => ['nullable', 'in:active,inactive'],
            'gender'       => ['nullable', 'string', 'max:50'],
            'scanning_for' => ['nullable', 'string', 'max:100'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Report Data
        |--------------------------------------------------------------------------
        */

        $summary = $this->service->getSummary($request);

        $patients = $this->service->getPatients($request);

        $doctors = $this->service->getDoctors();

        $filterOptions = $this->service->getFilterOptions();


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.reports.doctor-wise-patients.index',
            [
                'summary'       => $summary,
                'patients'      => $patients,
                'doctors'       => $doctors,
                'filterOptions' => $filterOptions,
            ]
        );
    }


    /**
     * ==========================================================
     * Doctor Details
     * ==========================================================
     */
    public function show(
        Request $request,
        string $doctor
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validate Filters
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'search'       => ['nullable', 'string', 'max:255'],
            'status'       => ['nullable', 'in:active,inactive'],
            'gender'       => ['nullable', 'string', 'max:50'],
            'scanning_for' => ['nullable', 'string', 'max:100'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Doctor Patients
        |--------------------------------------------------------------------------
        */

        $patients = $this->service->getDoctorPatients(
            $request,
            $doctor
        );


        /*
        |--------------------------------------------------------------------------
        | Doctor Summary
        |--------------------------------------------------------------------------
        */

        $totalPatients = $patients->count();

        $activePatients = $patients
            ->where('status', 'active')
            ->count();

        $inactivePatients = $patients
            ->where('status', 'inactive')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.reports.doctor-wise-patients.show',
            [
                'doctor'          => $doctor,
                'patients'        => $patients,
                'totalPatients'   => $totalPatients,
                'activePatients'  => $activePatients,
                'inactivePatients'=> $inactivePatients,
            ]
        );
    }


    /**
     * ==========================================================
     * Excel Export
     * ==========================================================
     */
    public function exportExcel(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Filters
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'search'       => ['nullable', 'string', 'max:255'],
            'doctor'       => ['nullable', 'string', 'max:255'],
            'status'       => ['nullable', 'in:active,inactive'],
            'gender'       => ['nullable', 'string', 'max:50'],
            'scanning_for' => ['nullable', 'string', 'max:100'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | File Name
        |--------------------------------------------------------------------------
        */

        $fileName =
            'Doctor_Wise_Patients_Report_' .
            now()->format('Y_m_d_H_i_s') .
            '.xlsx';


        /*
        |--------------------------------------------------------------------------
        | Download
        |--------------------------------------------------------------------------
        */

        return Excel::download(
            new DoctorWisePatientsReportExport($request),
            $fileName
        );
    }


    /**
     * ==========================================================
     * PDF Export
     * ==========================================================
     */
    public function exportPdf(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Filters
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'search'       => ['nullable', 'string', 'max:255'],
            'doctor'       => ['nullable', 'string', 'max:255'],
            'status'       => ['nullable', 'in:active,inactive'],
            'gender'       => ['nullable', 'string', 'max:50'],
            'scanning_for' => ['nullable', 'string', 'max:100'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Report Data
        |--------------------------------------------------------------------------
        */

        $data = $this->service->exportPdfData($request);


        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'admin.reports.doctor-wise-patients.pdf',
            $data
        )->setPaper('a4', 'landscape');


        /*
        |--------------------------------------------------------------------------
        | File Name
        |--------------------------------------------------------------------------
        */

        $fileName =
            'Doctor_Wise_Patients_Report_' .
            now()->format('Y_m_d_H_i_s') .
            '.pdf';


        /*
        |--------------------------------------------------------------------------
        | Download
        |--------------------------------------------------------------------------
        */

        return $pdf->download($fileName);
    }
}