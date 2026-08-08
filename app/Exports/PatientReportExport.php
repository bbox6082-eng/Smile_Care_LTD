<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PatientReportExport implements
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
            'Phone Number',
            'Gender',
            'Date of Birth',
            'Doctor',
            'Chamber',
            'Region',
            'Territory',
            'Scanning For',
            'Case Type',
            'Upper Cases',
            'Lower Cases',
            'Status',
            'Created At',
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

            $patient->Predict3DId,

            $patient->FullName,

            $patient->PhoneNumber ?: '-',

            $patient->Gender ?: '-',

            optional($patient->DateOfBirth)
                ->format('d M Y'),

            $patient->DoctorName ?: '-',

            $patient->ChamberName ?: '-',

            $patient->RegionalName ?: '-',

            $patient->TerritoryName ?: '-',

            $patient->ScanningFor ?: '-',

            $patient->case_type ?: '-',

            $patient->UpperCases ?? 0,

            $patient->LowerCases ?? 0,

            $patient->status
                ? ucfirst($patient->status)
                : '-',

            optional($patient->created_at)
                ->format('d M Y h:i A'),

        ];
    }
}