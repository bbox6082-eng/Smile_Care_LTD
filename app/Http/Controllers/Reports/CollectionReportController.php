<?php

namespace App\Http\Controllers\Reports;

use App\Exports\CollectionReportExport;
use App\Http\Controllers\Controller;
use App\Services\Reports\CollectionReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CollectionReportController extends Controller
{
    /**
     * Collection Report Service
     */
    protected CollectionReportService $collectionReportService;

    /**
     * Constructor
     */
    public function __construct(CollectionReportService $collectionReportService)
    {
        $this->collectionReportService = $collectionReportService;
    }

    /**
     * ==========================================================
     * Collection Report Dashboard
     * ==========================================================
     */
    public function index(Request $request)
    {
        $report = $this->collectionReportService->getReport($request);

        return view('admin.reports.collection.index', [
            'summary'        => $report['summary'],
            'collections'    => $report['collections'],
            'trendChart'     => $report['trendChart'],
            'monthlyChart'   => $report['monthlyChart'],
            'methodChart'    => $report['methodChart'],
            'request'        => $request,
        ]);
    }

    /**
     * ==========================================================
     * Export Excel
     * ==========================================================
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new CollectionReportExport(
                $this->collectionReportService->exportReportData($request)
            ),
            'collection-report.xlsx'
        );
    }

    /**
     * ==========================================================
     * Export PDF
     * ==========================================================
     */
    public function exportPdf(Request $request)
    {
        $data = $this->collectionReportService->exportPdfData($request);

        $pdf = Pdf::loadView(
            'admin.reports.collection.pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download('collection-report.pdf');
    }

    /**
     * ==========================================================
     * Collection Details
     * ==========================================================
     */
    public function show(int $id)
    {
        $collection = $this->collectionReportService->getDetails($id);

        return view(
            'admin.reports.collection.show',
            compact('collection')
        );
    }
}