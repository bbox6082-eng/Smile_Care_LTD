<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PatientByDoctorReportExport implements
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
            'Doctor',
            'Patient',
            'Predict3D ID',
            'Phone',
            'Gender',
            'Scanning For',
            'Chamber',
            'Region',
            'Territory',
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

            $patient->DoctorName ?: '-',

            $patient->FullName ?: '-',

            $patient->Predict3DId ?: '-',

            $patient->PhoneNumber ?: '-',

            $patient->Gender ?: '-',

            $patient->ScanningFor ?: '-',

            $patient->ChamberName ?: '-',

            $patient->RegionalName ?: '-',

            $patient->TerritoryName ?: '-',

            optional($patient->created_at)
                ->format('d M Y'),

        ];
    }
}