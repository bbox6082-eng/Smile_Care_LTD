<?php

namespace App\Exports;

use App\Services\Reports\PaymentReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class PaymentReportExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected Request $request;
    protected PaymentReportService $service;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->service = new PaymentReportService();
    }

    public function collection()
    {
        $payments = $this->service
        ->exportReportData($this->request);

    return $payments->map(function ($payment) {

        $paid = $payment->payments->sum('amount');

        if ($payment->remaining_amount <= 0) {

            $status = 'Paid';

        } elseif ($paid > 0) {

            $status = 'Partial';

        } else {

            $status = 'Due';

        }

        return [

            'Predict3D ID'   => $payment->predict3d_id,

            'Patient Name'   => optional($payment->patient)->FullName,

            'Doctor Name'    => optional($payment->patient)->DoctorName,

            'Total Amount'   => $payment->total_amount,

            'Paid Amount'    => $paid,

            'Remaining Due'  => $payment->remaining_amount,

            'Payment Method' => ucwords(str_replace('_', ' ', $payment->payment_method)),

            'Status'         => $status,

            'Created Date'   => optional($payment->created_at)->format('d M Y'),

        ];

    });
}
    public function headings(): array
    {
        return [

            'Predict3D ID',

            'Patient Name',

            'Doctor Name',

            'Total Amount',

            'Paid Amount',

            'Remaining Due',

            'Payment Method',

            'Status',

            'Created Date',

        ];
    }
}