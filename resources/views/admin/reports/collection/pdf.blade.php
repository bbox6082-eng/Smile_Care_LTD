<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Collection Report</title>

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

        .footer{
            margin-top:20px;
            text-align:right;
            font-size:10px;
            color:#666;
        }

    </style>

</head>

<body>

<h2>Collection Report</h2>

<div class="subtitle">

    Collection Summary Report

</div>

<table class="report-info">

    <tr>

        <td>

            <strong>Generated:</strong>

            {{ $generatedAt->format('d M Y h:i A') }}

        </td>

        <td class="text-right">

            <strong>Total Records:</strong>

            {{ $collections->count() }}

        </td>

    </tr>

</table>

<table class="summary">

    <thead>

    <tr>

        <th>Total Collection</th>

        <th>Today's Collection</th>

        <th>Monthly Collection</th>

        <th>Total Transactions</th>

    </tr>

    </thead>

    <tbody>

    <tr>

        <td>

            ৳ {{ number_format($summary['total_collection'],2) }}

        </td>

        <td>

            ৳ {{ number_format($summary['today_collection'],2) }}

        </td>

        <td>

            ৳ {{ number_format($summary['monthly_collection'],2) }}

        </td>

        <td>

            {{ $summary['total_transactions'] }}

        </td>

    </tr>

    </tbody>

</table>

<table>

    <thead>

    <tr>

        <th>#</th>

        <th>Date</th>

        <th>Predict3D ID</th>

        <th>Patient</th>

        <th>Doctor</th>

        <th>Amount</th>

        <th>Method</th>

        <th>Bank / Mobile</th>

    </tr>

    </thead>

    <tbody>

    @forelse($collections as $index => $collection)

        @php

            $patient = optional(optional($collection->paymentPlan)->patient);

        @endphp

        <tr>

            <td class="text-center">

                {{ $index + 1 }}

            </td>

            <td class="text-center">

                {{ optional($collection->payment_date)->format('d M Y') }}

            </td>

            <td>

                {{ optional($collection->paymentPlan)->predict3d_id }}

            </td>

            <td>

                {{ $patient->FullName ?? '-' }}

            </td>

            <td>

                {{ $patient->DoctorName ?? '-' }}

            </td>

            <td class="text-right">

                {{ number_format($collection->amount,2) }}

            </td>

            <td class="text-center">

                {{ ucwords(str_replace('_',' ',$collection->payment_method)) }}

            </td>

            <td>

                @if($collection->payment_method == 'bank_transfer')

                    {{ $collection->bank_name ?? '-' }}

                @elseif($collection->payment_method == 'mobile_banking')

                    {{ $collection->mobile_provider ?? '-' }}

                @else

                    -

                @endif

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="8" class="text-center">

                No collection records found.

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