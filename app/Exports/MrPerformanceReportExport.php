<?php

namespace App\Exports;

use App\Services\Reports\MrPerformanceReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MrPerformanceReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    protected Request $request;

    protected MrPerformanceReportService $service;

    protected int $rowNumber = 0;


    /**
     * Constructor
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->service =
            app(MrPerformanceReportService::class);
    }


    /**
     * ==========================================================
     * Collection
     * ==========================================================
     */
    public function collection()
    {
        return $this->service
            ->exportReportData(
                $this->request
            );
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

            'Marketing Representative',

            'Patients',

            'Active Patients',

            'Inactive Patients',

            'Doctors Covered',

            'Payment Plans',

            'Paid Plans',

            'Planned Revenue',

            'Collected Revenue',

            'Outstanding Due',

            'Collection Rate',

            'Average Revenue / Patient',

        ];
    }


    /**
     * ==========================================================
     * Mapping
     * ==========================================================
     */
    public function map($row): array
    {
        return [

            ++$this->rowNumber,

            $row['mr'] ?? 'Unassigned',

            $row['patients'] ?? 0,

            $row['active'] ?? 0,

            $row['inactive'] ?? 0,

            $row['doctors'] ?? 0,

            $row['payment_plans'] ?? 0,

            $row['paid_plans'] ?? 0,

            number_format(
                (float) ($row['planned_revenue'] ?? 0),
                2,
                '.',
                ''
            ),

            number_format(
                (float) ($row['collected_revenue'] ?? 0),
                2,
                '.',
                ''
            ),

            number_format(
                (float) ($row['outstanding_due'] ?? 0),
                2,
                '.',
                ''
            ),

            number_format(
                (float) ($row['collection_rate'] ?? 0),
                2,
                '.',
                ''
            ) . '%',

            number_format(
                (float) (
                    $row['average_revenue_per_patient']
                    ?? 0
                ),
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