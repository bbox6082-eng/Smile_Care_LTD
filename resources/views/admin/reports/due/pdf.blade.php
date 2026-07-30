<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Due Report</title>

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:11px;
            color:#333;
        }

        h2{
            margin:0;
            padding:0;
            text-align:center;
        }

        .subtitle{
            text-align:center;
            margin-top:4px;
            margin-bottom:20px;
            color:#666;
        }

        .report-info{
            width:100%;
            margin-bottom:15px;
        }

        .report-info td{
            padding:4px 0;
        }

        .summary{
            width:100%;
            border-collapse:collapse;
            margin-bottom:18px;
        }

        .summary th,
        .summary td{
            border:1px solid #cccccc;
            padding:8px;
            text-align:center;
        }

        .summary th{
            background:#f5f5f5;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th{
            background:#e9ecef;
            border:1px solid #bdbdbd;
            padding:7px;
            font-size:10px;
        }

        table td{
            border:1px solid #cccccc;
            padding:6px;
            font-size:10px;
        }

        .text-right{
            text-align:right;
        }

        .text-center{
            text-align:center;
        }

        .badge-overdue{
            color:#d32f2f;
            font-weight:bold;
        }

        .badge-current{
            color:#2e7d32;
            font-weight:bold;
        }

        .footer{
            margin-top:20px;
            text-align:right;
            font-size:10px;
            color:#666;
        }

    </style>

</head>

<body>

<h2>Due Report</h2>

<div class="subtitle">
    Outstanding Payment Summary
</div>

<table class="report-info">

    <tr>

        <td>
            <strong>Generated:</strong>
            {{ $generatedAt->format('d M Y h:i A') }}
        </td>

        <td class="text-right">
            <strong>Total Records:</strong>
            {{ $payments->count() }}
        </td>

    </tr>

</table>

<table class="summary">

    <thead>

    <tr>

        <th>Total Outstanding</th>

        <th>Overdue Amount</th>

        <th>Patients With Due</th>

        <th>Average Due</th>

    </tr>

    </thead>

    <tbody>

    <tr>

        <td>
            ৳ {{ number_format($summary['total_outstanding'],2) }}
        </td>

        <td>
            ৳ {{ number_format($summary['overdue_amount'],2) }}
        </td>

        <td>
            {{ $summary['patients_with_due'] }}
        </td>

        <td>
            ৳ {{ number_format($summary['average_due'],2) }}
        </td>

    </tr>

    </tbody>

</table>

<table>

    <thead>

    <tr>

        <th>#</th>

        <th>Predict3D ID</th>

        <th>Patient</th>

        <th>Doctor</th>

        <th>Total</th>

        <th>Paid</th>

        <th>Remaining</th>

        <th>Method</th>

        <th>Next Payment</th>

        <th>Status</th>

    </tr>

    </thead>

    <tbody>
        @forelse($payments as $index => $payment)

    @php

        $paidAmount = $payment->payments->sum('amount');

        $isOverdue = $payment->next_payment_date &&
            \Carbon\Carbon::parse($payment->next_payment_date)->isPast();

    @endphp

    <tr>

        <td class="text-center">
            {{ $index + 1 }}
        </td>

        <td>
            {{ $payment->predict3d_id }}
        </td>

        <td>
            {{ optional($payment->patient)->FullName ?? '-' }}
        </td>

        <td>
            {{ optional($payment->patient)->DoctorName ?? '-' }}
        </td>

        <td class="text-right">
            {{ number_format($payment->total_amount, 2) }}
        </td>

        <td class="text-right">
            {{ number_format($paidAmount, 2) }}
        </td>

        <td class="text-right">
            {{ number_format($payment->remaining_amount, 2) }}
        </td>

        <td class="text-center">
            {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
        </td>

        <td class="text-center">

            @if($payment->next_payment_date)

                {{ \Carbon\Carbon::parse($payment->next_payment_date)->format('d M Y') }}

            @else

                -

            @endif

        </td>

        <td class="text-center">

            @if($isOverdue)

                <span class="badge-overdue">
                    Overdue
                </span>

            @else

                <span class="badge-current">
                    Current
                </span>

            @endif

        </td>

    </tr>

@empty

    <tr>

        <td colspan="10" class="text-center">

            No due records found.

        </td>

    </tr>

@endforelse

    </tbody>

</table>

<div class="footer">

    Report generated on
    {{ $generatedAt->format('d M Y h:i A') }}

</div>

</body>

</html>