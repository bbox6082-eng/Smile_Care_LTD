<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DeliveryPendingReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected Collection $payments;

    public function __construct($payments)
    {
        $this->payments = collect($payments);
    }

    /**
     * ==========================================================
     * Collection
     * ==========================================================
     */
    public function collection()
    {
        return $this->payments;
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
            'Total Upper',
            'Delivered Upper',
            'Remaining Upper',
            'Total Lower',
            'Delivered Lower',
            'Remaining Lower',
            'Total Pending',
            'Delivery Progress',
            'Delivery Status',
        ];
    }

    /**
     * ==========================================================
     * Map Rows
     * ==========================================================
     */
    public function map($payment): array
    {
        static $sl = 0;

        return [
            ++$sl,

            $payment->predict3d_id ?: '-',

            $payment->patient?->FullName ?: '-',

            $payment->patient?->DoctorName ?: '-',

            (int) $payment->total_upper,

            (int) $payment->delivered_upper,

            (int) $payment->remaining_upper,

            (int) $payment->total_lower,

            (int) $payment->delivered_lower,

            (int) $payment->remaining_lower,

            (int) $payment->remaining_cases,

            number_format(
                (float) $payment->delivery_percentage,
                2
            ) . '%',

            match ($payment->delivery_status) {

                'both_pending' =>
                    'Both Pending',

                'upper_pending' =>
                    'Upper Pending',

                'lower_pending' =>
                    'Lower Pending',

                'completed' =>
                    'Completed',

                default =>
                    'Pending',

            },
        ];
    }
}