<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CollectionReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles
{
    protected Collection $collections;

    /**
     * Constructor
     */
    public function __construct(Collection $collections)
    {
        $this->collections = $collections;
    }

    /**
     * Collection
     */
    public function collection(): Collection
    {
        return $this->collections;
    }

    /**
     * Excel Headings
     */
    public function headings(): array
    {
        return [
            'SL',
            'Payment Date',
            'Predict3D ID',
            'Patient Name',
            'Doctor Name',
            'Amount',
            'Payment Method',
            'Bank / Mobile',
        ];
    }

    /**
     * Map Data
     */
    public function map($payment): array
    {
        static $serial = 0;

        $serial++;

        $patient = optional(optional($payment->paymentPlan)->patient);

        return [

            $serial,

            optional($payment->payment_date)->format('d M Y'),

            optional($payment->paymentPlan)->predict3d_id,

            $patient->FullName,

            $patient->DoctorName,

            number_format($payment->amount, 2),

            ucwords(str_replace('_', ' ', $payment->payment_method)),

            $payment->bank_name
                ?: $payment->mobile_provider
                ?: '-',

        ];
    }

    /**
     * Excel Styles
     */
    public function styles(Worksheet $sheet)
    {
        return [

            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],

        ];
    }
}