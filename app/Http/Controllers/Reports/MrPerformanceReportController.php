<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\MrPerformanceReportService;
use Illuminate\Http\Request;

class MrPerformanceReportController extends Controller
{
    protected MrPerformanceReportService $service;

    public function __construct(
        MrPerformanceReportService $service
    ) {
        $this->service = $service;
    }

    /**
     * ==========================================================
     * MR Performance Report
     * ==========================================================
     */
    public function index(Request $request)
    {
        $performance =
            $this->service->getPaginatedPerformance(
                $request,
                10
            );

        $summary =
            $this->service->getSummary(
                $request
            );

        $marketingRepresentatives =
            $this->service->getMarketingRepresentatives();

        $doctors =
            $this->service->getDoctors();

        $statuses =
            $this->service->getStatuses();

        return view(
            'admin.reports.mr-performance.index',
            compact(
                'performance',
                'summary',
                'marketingRepresentatives',
                'doctors',
                'statuses'
            )
        );
    }


    /**
     * ==========================================================
     * MR Details
     * ==========================================================
     */
    public function show(
        Request $request,
        string $mr
    ) {

        $data =
            $this->service->getMrDetails(
                $request,
                $mr
            );

        return view(
            'admin.reports.mr-performance.show',
            $data
        );
    }


    /**
     * ==========================================================
     * Export Excel
     * ==========================================================
     */
    public function exportExcel(
        Request $request
    ) {

        $data =
            $this->service->exportReportData(
                $request
            );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    /**
     * ==========================================================
     * Export PDF
     * ==========================================================
     */
    public function exportPdf(
        Request $request
    ) {

        $data =
            $this->service->exportPdfData(
                $request
            );

        return view(
            'admin.reports.mr-performance.pdf',
            $data
        );
    }
}