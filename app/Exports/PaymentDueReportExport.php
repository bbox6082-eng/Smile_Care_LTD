<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentDueReportExport implements
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
            'Payment Method',
            'Total Amount',
            'Paid Amount',
            'Remaining Amount',
            'Installment',
            'Next Payment Date',
            'Due Status',
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

        $paidAmount = max(
            0,
            (float) $payment->total_amount -
            (float) $payment->remaining_amount
        );

        if (
            $payment->next_payment_date &&
            \Carbon\Carbon::parse($payment->next_payment_date)
                ->lt(\Carbon\Carbon::today())
        ) {

            $dueStatus = 'Overdue';

        } elseif ($payment->next_payment_date) {

            $dueStatus = 'Upcoming';

        } else {

            $dueStatus = 'Pending';

        }

        return [
            ++$sl,

            $payment->predict3d_id ?: '-',

            $payment->patient?->FullName ?: '-',

            $payment->patient?->DoctorName ?: '-',

            $payment->payment_method
                ? ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $payment->payment_method
                    )
                )
                : '-',

            number_format(
                (float) $payment->total_amount,
                2
            ),

            number_format(
                $paidAmount,
                2
            ),

            number_format(
                (float) $payment->remaining_amount,
                2
            ),

            $payment->is_installment
                ? 'Yes'
                : 'No',

            $payment->next_payment_date
                ? \Carbon\Carbon::parse(
                    $payment->next_payment_date
                )->format('d M Y')
                : '-',

            $dueStatus,
        ];
    }
}