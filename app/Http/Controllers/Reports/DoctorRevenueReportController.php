<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Exports\DoctorRevenueReportExport;
use App\Services\Reports\DoctorRevenueReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DoctorRevenueReportController extends Controller
{
    protected DoctorRevenueReportService $service;

    public function __construct(
        DoctorRevenueReportService $service
    ) {
        $this->service = $service;
    }


    /**
     * ==========================================================
     * Doctor Revenue Report
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

            'doctor' => [
                'nullable',
                'string',
                'max:255',
            ],

            'payment_method' => [
                'nullable',
                'string',
            ],

            'status' => [
                'nullable',
                'in:Paid,Partial,Due',
            ],

            'search' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Report Data
        |--------------------------------------------------------------------------
        */

        $summary = $this->service
            ->getSummary($request);


        $doctorRevenue = $this->service
            ->getDoctorRevenuePaginated($request);


        $doctors = $this->service
            ->getDoctors();


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.reports.doctor-revenue.index',
            compact(
                'summary',
                'doctorRevenue',
                'doctors'
            )
        );
    }


    /**
     * ==========================================================
     * Doctor Revenue Details
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
            'from' => [
                'nullable',
                'date',
            ],

            'to' => [
                'nullable',
                'date',
                'after_or_equal:from',
            ],

            'payment_method' => [
                'nullable',
                'string',
            ],

            'status' => [
                'nullable',
                'in:Paid,Partial,Due',
            ],

            'search' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Doctor Details
        |--------------------------------------------------------------------------
        */

        $data = $this->service
            ->getDoctorDetails(
                $request,
                $doctor
            );


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.reports.doctor-revenue.show',
            $data
        );
    }


    /**
     * ==========================================================
     * Excel Export
     * ==========================================================
     */
    public function exportExcel(
        Request $request
    ) {

        $fileName =
            'Doctor_Revenue_Report_' .
            now()->format('Y_m_d_H_i_s') .
            '.xlsx';


        return Excel::download(
            new DoctorRevenueReportExport($request),
            $fileName
        );
    }


    /**
     * ==========================================================
     * PDF Export
     * ==========================================================
     */
    public function exportPdf(
        Request $request
    ) {

        $data = $this->service
            ->exportPdfData($request);


        $pdf = Pdf::loadView(
            'admin.reports.doctor-revenue.pdf',
            $data
        )->setPaper(
            'a4',
            'landscape'
        );


        $fileName =
            'Doctor_Revenue_Report_' .
            now()->format('Y_m_d_H_i_s') .
            '.pdf';


        return $pdf->download(
            $fileName
        );
    }
}