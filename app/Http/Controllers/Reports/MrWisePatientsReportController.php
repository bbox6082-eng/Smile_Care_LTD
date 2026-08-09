<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\MrWisePatientsReportService;
use App\Exports\MrWisePatientsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MrWisePatientsReportController extends Controller
{
    protected MrWisePatientsReportService $service;

    public function __construct(
        MrWisePatientsReportService $service
    ) {
        $this->service = $service;
    }


    /**
     * ==========================================================
     * MR-wise Patients Report
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
            'from' => [
                'nullable',
                'date',
            ],

            'to' => [
                'nullable',
                'date',
                'after_or_equal:from',
            ],

            'mr' => [
                'nullable',
                'string',
                'max:255',
            ],

            'doctor' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:50',
            ],

            'scanning_for' => [
                'nullable',
                'string',
                'max:100',
            ],

            'search' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Report Summary
        |--------------------------------------------------------------------------
        */

        $summary = $this->service->getSummary(
            $request
        );


        /*
        |--------------------------------------------------------------------------
        | MR-wise Patient Groups
        |--------------------------------------------------------------------------
        */

        $mrPatients = $this->service
            ->getMrWisePatientsPaginated(
                $request,
                10
            );


        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $marketingRepresentatives =
            $this->service
                ->getMarketingRepresentatives();


        $doctors =
            $this->service
                ->getDoctors();


        $filterOptions =
            $this->service
                ->getFilterOptions();


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.reports.mr-wise-patients.index',
            [
                'summary' =>
                    $summary,

                'mrPatients' =>
                    $mrPatients,

                'marketingRepresentatives' =>
                    $marketingRepresentatives,

                'doctors' =>
                    $doctors,

                'statuses' =>
                    $filterOptions['statuses'],

                'genders' =>
                    $filterOptions['genders'],

                'scanningTypes' =>
                    $filterOptions['scanning_types'],
            ]
        );
    }


    /**
     * ==========================================================
     * View Individual MR Details
     * ==========================================================
     */
    public function show(
        Request $request,
        string $mr
    ) {
        $request->validate([
            'from' => [
                'nullable',
                'date',
            ],

            'to' => [
                'nullable',
                'date',
                'after_or_equal:from',
            ],

            'doctor' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'nullable',
                'string',
                'max:100',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:50',
            ],

            'scanning_for' => [
                'nullable',
                'string',
                'max:100',
            ],

            'search' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);


        $data = $this->service->getMrDetails(
            $request,
            $mr
        );


        return view(
            'admin.reports.mr-wise-patients.show',
            $data
        );
    }


    /**
     * ==========================================================
     * Export MR-wise Patients to Excel
     * ==========================================================
     */
    public function exportExcel(
        Request $request
    ) {
        $fileName =
            'MR_Wise_Patients_' .
            now()->format('Y_m_d_H_i_s') .
            '.xlsx';


        return Excel::download(
            new MrWisePatientsExport($request),
            $fileName
        );
    }


    /**
     * ==========================================================
     * Export MR-wise Patients to PDF
     * ==========================================================
     */
    public function exportPdf(
        Request $request
    ) {
        $data =
            $this->service
                ->exportPdfData($request);


        $pdf = Pdf::loadView(
            'admin.reports.mr-wise-patients.pdf',
            $data
        )->setPaper(
            'a4',
            'landscape'
        );


        $fileName =
            'MR_Wise_Patients_' .
            now()->format('Y_m_d_H_i_s') .
            '.pdf';


        return $pdf->download(
            $fileName
        );
    }
}