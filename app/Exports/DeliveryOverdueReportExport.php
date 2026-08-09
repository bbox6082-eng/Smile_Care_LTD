<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DeliveryOverdueReportExport implements
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
            'Last Delivery Date',
            'Latest Upper Delivered',
            'Latest Lower Delivered',
            'Cases Per Cycle',
            'Days Per Aligner',
            'Cycle Days',
            'Next Delivery Due',
            'Overdue Days',
            'Latest Delivery Paid',
            'Status',
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

            $payment->latest_delivery_date
                ? \Carbon\Carbon::parse(
                    $payment->latest_delivery_date
                )->format('d M Y')
                : '-',

            (int) $payment->latest_upper_delivered,

            (int) $payment->latest_lower_delivered,

            (int) $payment->max_cases,

            (int) $payment->days_per_aligner,

            (int) $payment->cycle_days,

            $payment->next_delivery_due_date
                ? \Carbon\Carbon::parse(
                    $payment->next_delivery_due_date
                )->format('d M Y')
                : '-',

            (int) $payment->overdue_days,

            number_format(
                (float) $payment->latest_delivery_paid_amount,
                2
            ),

            'Overdue',
        ];
    }
}