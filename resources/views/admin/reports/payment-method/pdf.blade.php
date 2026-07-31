<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Payment Method Report</title>

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
            color:#333;
        }

        h2{
            text-align:center;
            margin-bottom:5px;
        }

        .subtitle{
            text-align:center;
            color:#666;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }

        th,td{
            border:1px solid #ccc;
            padding:7px;
        }

        th{
            background:#f3f3f3;
            text-align:left;
        }

        .text-right{
            text-align:right;
        }

        .summary{
            margin-bottom:20px;
        }

        .summary td{
            border:none;
            padding:4px 0;
        }

        .footer{
            margin-top:25px;
            text-align:right;
            font-size:11px;
            color:#777;
        }

    </style>

</head>

<body>

<h2>

    Payment Method Report

</h2>

<div class="subtitle">

    Generated on {{ $generatedAt->format('d M Y h:i A') }}

</div>

<table class="summary">

    <tr>

        <td>

            <strong>Total Transactions :</strong>

            {{ count($transactions) }}

        </td>

        <td>

            <strong>Cash :</strong>

            ৳ {{ number_format($summary['cash_total'],2) }}

        </td>

    </tr>

    <tr>

        <td>

            <strong>Card :</strong>

            ৳ {{ number_format($summary['card_total'],2) }}

        </td>

        <td>

            <strong>Bank Transfer :</strong>

            ৳ {{ number_format($summary['bank_total'],2) }}

        </td>

    </tr>

    <tr>

        <td colspan="2">

            <strong>Mobile Banking :</strong>

            ৳ {{ number_format($summary['mobile_total'],2) }}

        </td>

    </tr>

</table>

<table>

    <thead>

    <tr>

        <th width="4%">#</th>

        <th width="11%">Date</th>

        <th width="12%">Predict3D</th>

        <th width="18%">Patient</th>

        <th width="18%">Doctor</th>

        <th width="14%">Method</th>

        <th width="13%">Provider</th>

        <th width="10%" class="text-right">

            Amount

        </th>

    </tr>

    </thead>

    <tbody>

    @forelse($transactions as $transaction)

        @php

            $plan = $transaction->paymentPlan;
            $patient = $plan?->patient;

            $provider='-';

            if($transaction->payment_method=='bank_transfer'){

                $provider=$transaction->bank_name ?: '-';

            }elseif($transaction->payment_method=='mobile_banking'){

                $provider=$transaction->mobile_provider ?: '-';

            }

        @endphp

        <tr>

            <td>

                {{ $loop->iteration }}

            </td>

            <td>

                {{ optional($transaction->payment_date)->format('d M Y') }}

            </td>

            <td>

                {{ $plan?->predict3d_id }}

            </td>

            <td>

                {{ $patient?->FullName }}

            </td>

            <td>

                {{ $patient?->DoctorName }}

            </td>

            <td>

                {{ ucwords(str_replace('_',' ',$transaction->payment_method)) }}

            </td>

            <td>

                {{ $provider }}

            </td>

            <td class="text-right">

                {{ number_format($transaction->amount,2) }}

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="8" style="text-align:center">

                No payment transactions found.

            </td>

        </tr>

    @endforelse

    </tbody>

</table>

<div class="footer">

    Payment Method Report • DentLab-OS

</div>

</body>

</html>