<?php

namespace App\Exports;

use App\Services\Reports\DoctorWisePatientsReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DoctorWisePatientsReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    /**
     * Request containing report filters.
     */
    protected Request $request;


    /**
     * Report Service.
     */
    protected DoctorWisePatientsReportService $service;


    /**
     * Constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->service =
            app(DoctorWisePatientsReportService::class);
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
            'Predict3D ID',
            'Patient Name',
            'Doctor Name',
            'Phone Number',
            'Gender',
            'Status',
            'Scanning Type',
            'Chamber',
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
            $patient->id,

            $patient->Predict3DId ?: '-',

            $patient->FullName ?: '-',

            $patient->DoctorName ?: 'Unknown',

            $patient->PhoneNumber ?: '-',

            $patient->Gender ?: '-',

            $patient->status
                ? ucfirst($patient->status)
                : '-',

            $patient->ScanningFor ?: '-',

            $patient->ChamberName ?: '-',

            $patient->created_at
                ? $patient->created_at->format('d M Y')
                : '-',
        ];
    }


    /**
     * ==========================================================
     * Styles
     * ==========================================================
     */
    public function styles(Worksheet $sheet)
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Header
            |--------------------------------------------------------------------------
            */
            1 => [
                'font' => [
                    'bold' => true,
                ],

                'alignment' => [
                    'horizontal' => 'center',
                    'vertical'   => 'center',
                ],
            ],

        ];
    }
}