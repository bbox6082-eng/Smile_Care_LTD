<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ActiveCaseReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected Collection $cases;

    public function __construct($cases)
    {
        $this->cases = collect($cases);
    }

    /**
     * ==========================================================
     * Collection
     * ==========================================================
     */
    public function collection()
    {
        return $this->cases;
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
            'Patient',
            'Doctor',
            'Payment Method',
            'Total Amount',
            'Paid Amount',
            'Remaining Amount',
            'Installment',
            'Next Payment Date',
            'Case Opened',
        ];
    }

    /**
     * ==========================================================
     * Map Rows
     * ==========================================================
     */
    public function map($case): array
    {
        static $sl = 0;

        $paidAmount = max(
            0,
            (float) $case->total_amount -
            (float) $case->remaining_amount
        );

        return [

            ++$sl,

            $case->predict3d_id ?: '-',

            $case->patient?->FullName ?: '-',

            $case->patient?->DoctorName ?: '-',

            $case->payment_method
                ? ucwords(str_replace('_', ' ', $case->payment_method))
                : '-',

            number_format(
                (float) $case->total_amount,
                2
            ),

            number_format(
                $paidAmount,
                2
            ),

            number_format(
                (float) $case->remaining_amount,
                2
            ),

            $case->is_installment
                ? 'Yes'
                : 'No',

            optional($case->next_payment_date)
                ->format('d M Y'),

            optional($case->created_at)
                ->format('d M Y'),

        ];
    }
}