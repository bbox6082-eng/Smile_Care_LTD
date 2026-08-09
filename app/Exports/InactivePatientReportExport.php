<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InactivePatientReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected Collection $patients;

    public function __construct($patients)
    {
        $this->patients = collect($patients);
    }


    /**
     * ==========================================================
     * Collection
     * ==========================================================
     */
    public function collection()
    {
        return $this->patients;
    }


    /**
     * ==========================================================
     * Excel Headings
     * ==========================================================
     */
    public function headings(): array
    {
        return [
            'SL',
            'Predict3D ID',
            'Patient Name',
            'Phone',
            'Gender',
            'Scanning For',
            'Doctor',
            'Chamber',
            'Region',
            'Territory',
            'Status',
            'Registration Date',
        ];
    }


    /**
     * ==========================================================
     * Map Rows
     * ==========================================================
     */
    public function map($patient): array
    {
        static $sl = 0;

        return [

            ++$sl,

            $patient->Predict3DId ?: '-',

            $patient->FullName ?: '-',

            $patient->PhoneNumber ?: '-',

            $patient->Gender ?: '-',

            $patient->ScanningFor ?: '-',

            $patient->DoctorName ?: '-',

            $patient->ChamberName ?: '-',

            $patient->RegionalName ?: '-',

            $patient->TerritoryName ?: '-',

            ucfirst($patient->status ?: 'inactive'),

            optional($patient->created_at)
                ->format('d M Y'),

        ];
    }
}