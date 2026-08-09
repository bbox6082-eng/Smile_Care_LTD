<?php

namespace App\Exports;

use App\Services\Reports\MrWisePatientsReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MrWisePatientsExport implements
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
    protected MrWisePatientsReportService $service;


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
            app(MrWisePatientsReportService::class);
    }


    /**
     * ==========================================================
     * Collection
     * ==========================================================
     */
    public function collection()
    {
        return $this->service
            ->exportReportData($this->request);
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

            'MR Name',

            'Predict3D ID',

            'Patient Name',

            'Phone',

            'Gender',

            'Date of Birth',

            'Doctor',

            'Chamber',

            'Territory',

            'Regional',

            'Scanning For',

            'Case Type',

            'Status',

            'Created Date',

        ];
    }


    /**
     * ==========================================================
     * Mapping
     * ==========================================================
     */
    public function map($patient): array
    {
        return [

            ++$this->rowNumber,

            $patient->MRName ?: 'Unassigned',

            $patient->Predict3DId ?: '-',

            $patient->FullName ?: '-',

            $patient->PhoneNumber ?: '-',

            $patient->Gender ?: '-',

            $patient->DateOfBirth
                ? \Carbon\Carbon::parse(
                    $patient->DateOfBirth
                )->format('d M Y')
                : '-',

            $patient->DoctorName ?: '-',

            $patient->ChamberName ?: '-',

            $patient->TerritoryName ?: '-',

            $patient->RegionalName ?: '-',

            $patient->ScanningFor ?: '-',

            $patient->case_type ?: '-',

            $patient->status
                ? ucfirst($patient->status)
                : '-',

            $patient->created_at
                ? \Carbon\Carbon::parse(
                    $patient->created_at
                )->format('d M Y')
                : '-',

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