<?php

namespace App\Exports;

use App\Services\Reports\DoctorRevenueReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DoctorRevenueReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    /**
     * Report filters.
     */
    protected Request $request;


    /**
     * Report service.
     */
    protected DoctorRevenueReportService $service;


    /**
     * Sequential row number.
     */
    protected int $rowNumber = 0;


    /**
     * Constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->service =
            app(DoctorRevenueReportService::class);
    }


    /**
     * ==========================================================
     * Collection
     * ==========================================================
     */
    public function collection()
    {
        return $this->service
            ->getDoctorRevenue($this->request);
    }


    /**
     * ==========================================================
     * Headings
     * ==========================================================
     */
    public function headings(): array
    {
        return [
            'SL',
            'Doctor Name',
            'Total Revenue',
            'Transactions',
            'Payment Plans',
            'Average / Transaction',
        ];
    }


    /**
     * ==========================================================
     * Mapping
     * ==========================================================
     */
    public function map($doctorRevenue): array
    {
        $revenue = (float) $doctorRevenue['revenue'];

        $transactions =
            (int) $doctorRevenue['transactions'];


        $average = $transactions > 0
            ? $revenue / $transactions
            : 0;


        return [

            ++$this->rowNumber,

            $doctorRevenue['doctor'] ?: 'Unknown',

            number_format(
                $revenue,
                2,
                '.',
                ''
            ),

            $transactions,

            (int) $doctorRevenue['payment_plans'],

            number_format(
                $average,
                2,
                '.',
                ''
            ),

        ];
    }


    /**
     * ==========================================================
     * Styles
     * ==========================================================
     */
    public function styles(
        Worksheet $sheet
    ) {

        return [

            1 => [

                'font' => [
                    'bold' => true,
                ],

                'alignment' => [

                    'horizontal' =>
                        'center',

                    'vertical' =>
                        'center',

                ],

            ],

        ];
    }
}