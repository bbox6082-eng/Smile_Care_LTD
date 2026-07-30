<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <title>Payment Report</title>

    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }

        h2 {
            margin: 0;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 15px;
            color: #666;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary td {
            border: 1px solid #999;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report th {
            background: #2c3e50;
            color: white;
            border: 1px solid #555;
            padding: 8px;
            font-size: 10px;
        }

        table.report td {
            border: 1px solid #999;
            padding: 6px;
            font-size: 10px;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: gray;
        }
    </style>
</head>

<body>

    <h2>Payment Report</h2>

    <div class="subtitle">
        Generated:
        {{ $generatedAt->format('d M Y h:i A') }}
    </div>

    <table class="summary">
        <tr>
            <td>Total Revenue<br><strong>{{ number_format($summary['total_revenue'],2) }}</strong></td>

            <td>Today's Collection<br><strong>{{ number_format($summary['today_collection'],2) }}</strong></td>

            <td>Pending Due<br><strong>{{ number_format($summary['pending_due'],2) }}</strong></td>

            <td>Completed Plans<br><strong>{{ $summary['completed_plans'] }}</strong></td>
        </tr>
    </table>

    <table class="report">

        <thead>

        <tr>

            <th>ID</th>

            <th>Patient</th>

            <th>Doctor</th>

            <th>Total</th>

            <th>Paid</th>

            <th>Due</th>

            <th>Method</th>

            <th>Status</th>

            <th>Date</th>

        </tr>

        </thead>

        <tbody>

        @foreach($payments as $payment)

            @php

                $paid = $payment->payments->sum('amount');

                if($payment->remaining_amount <= 0){

                    $status = 'Paid';

                }elseif($paid > 0){

                    $status = 'Partial';

                }else{

                    $status = 'Due';

                }

            @endphp

            <tr>

                <td>{{ $payment->predict3d_id }}</td>

                <td>{{ optional($payment->patient)->FullName }}</td>

                <td>{{ optional($payment->patient)->DoctorName }}</td>

                <td class="right">{{ number_format($payment->total_amount,2) }}</td>

                <td class="right">{{ number_format($paid,2) }}</td>

                <td class="right">{{ number_format($payment->remaining_amount,2) }}</td>

                <td class="center">
                    {{ ucwords(str_replace('_',' ',$payment->payment_method)) }}
                </td>

                <td class="center">{{ $status }}</td>

                <td class="center">
                    {{ optional($payment->created_at)->format('d M Y') }}
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    <div class="footer">

        Smile Care LTD

    </div>

</body>

</html>