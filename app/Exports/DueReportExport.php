<?php

namespace App\Exports;

use App\Services\Reports\DueReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DueReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    /**
     * Request Instance
     */
    protected Request $request;

    /**
     * Service Instance
     */
    protected DueReportService $service;

    /**
     * Constructor
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->service = app(DueReportService::class);
    }

    /**
     * Export Collection
     */
    public function collection()
    {
        return $this->service->exportReportData($this->request);
    }

    /**
     * Excel Headings
     */
    public function headings(): array
    {
        return [
            'Predict3D ID',
            'Patient Name',
            'Doctor Name',
            'Payment Method',
            'Installment',
            'Total Amount',
            'Paid Amount',
            'Remaining Amount',
            'Next Payment Date',
            'Status',
            'Created At',
        ];
    }

    /**
     * Map Each Row
     */
    public function map($payment): array
    {
        $paidAmount = $payment->payments->sum('amount');

        $status = 'Current';

        if (
            $payment->next_payment_date &&
            \Carbon\Carbon::parse($payment->next_payment_date)->isPast()
        ) {
            $status = 'Overdue';
        }

        return [
            $payment->predict3d_id,

            optional($payment->patient)->FullName,

            optional($payment->patient)->DoctorName,

            ucfirst(str_replace('_', ' ', $payment->payment_method)),

            $payment->is_installment ? 'Yes' : 'No',

            number_format($payment->total_amount, 2),

            number_format($paidAmount, 2),

            number_format($payment->remaining_amount, 2),

            optional($payment->next_payment_date)
                ? \Carbon\Carbon::parse($payment->next_payment_date)
                    ->format('d M Y')
                : '-',

            $status,

            $payment->created_at
                ? $payment->created_at->format('d M Y')
                : '-',
        ];
    }
}