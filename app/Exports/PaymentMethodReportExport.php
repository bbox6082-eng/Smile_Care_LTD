<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PaymentMethodReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected Collection $transactions;

    public function __construct($transactions)
    {
        $this->transactions = collect($transactions);
    }

    /**
     * ==========================================================
     * Collection
     * ==========================================================
     */
    public function collection()
    {
        return $this->transactions;
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
            'Payment Date',
            'Predict3D ID',
            'Patient',
            'Doctor',
            'Payment Method',
            'Bank / Mobile Provider',
            'Transaction ID',
            'Amount',
        ];
    }

    /**
     * ==========================================================
     * Map Rows
     * ==========================================================
     */
    public function map($transaction): array
    {
        static $sl = 0;

        $paymentPlan = $transaction->paymentPlan;
        $patient = $paymentPlan?->patient;

        $provider = '-';

        if ($transaction->payment_method === 'bank_transfer') {

            $provider = $transaction->bank_name ?: '-';

        } elseif ($transaction->payment_method === 'mobile_banking') {

            $provider = $transaction->mobile_provider ?: '-';

        }

        return [

            ++$sl,

            optional($transaction->payment_date)->format('d M Y'),

            $paymentPlan?->predict3d_id,

            $patient?->FullName,

            $patient?->DoctorName,

            ucwords(str_replace('_', ' ', $transaction->payment_method)),

            $provider,

            $transaction->transaction_id ?: '-',

            number_format($transaction->amount, 2),

        ];
    }
}