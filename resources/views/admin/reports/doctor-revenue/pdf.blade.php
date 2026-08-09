<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>Doctor Revenue Report</title>

    <style>

        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 4px 0 0;
            color: #666;
            font-size: 9px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .summary-table td {
            width: 25%;
            border: 1px solid #ddd;
            padding: 8px;
        }

        .summary-label {
            color: #777;
            font-size: 8px;
        }

        .summary-value {
            font-size: 13px;
            font-weight: bold;
            margin-top: 3px;
        }

        .filter-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .filter-table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 8px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .report-table th {
            background: #f1f3f5;
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 7px;
            text-align: left;
        }

        .report-table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 7px;
        }

        .doctor-heading {
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 7px;
            margin-top: 12px;
            margin-bottom: 5px;
            font-size: 10px;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .revenue {
            font-weight: bold;
        }

        .due {
            font-weight: bold;
        }

        .footer {
            margin-top: 18px;
            padding-top: 7px;
            border-top: 1px solid #ddd;
            font-size: 7.5px;
            color: #777;
            text-align: right;
        }

    </style>

</head>

<body>

    {{-- =========================================================
        REPORT HEADER
    ========================================================== --}}

    <div class="header">

        <h1>
            Doctor Revenue Report
        </h1>

        <p>
            Revenue collected and summarized by doctor
        </p>

        @if(!empty($generatedAt))

            <p>

                Generated:
                {{ $generatedAt->format('d M Y h:i A') }}

            </p>

        @endif

    </div>


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}

    <table class="summary-table">

        <tr>

            <td>

                <div class="summary-label">
                    Total Revenue
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_revenue'],
                        2
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Total Transactions
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_transactions']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Payment Plans
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_plans']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Pending Due
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['pending_due'],
                        2
                    ) }}

                </div>

            </td>

        </tr>

    </table>


    {{-- =========================================================
        APPLIED FILTERS
    ========================================================== --}}

    @if(
        !empty($filters['search']) ||
        !empty($filters['doctor']) ||
        !empty($filters['payment_method']) ||
        !empty($filters['status']) ||
        !empty($filters['from']) ||
        !empty($filters['to'])
    )

        <table class="filter-table">

            <tr>

                <td>

                    <strong>Applied Filters:</strong>


                    @if(!empty($filters['search']))

                        Search:
                        {{ $filters['search'] }}

                        &nbsp; | &nbsp;

                    @endif


                    @if(!empty($filters['doctor']))

                        Doctor:
                        {{ $filters['doctor'] }}

                        &nbsp; | &nbsp;

                    @endif


                    @if(!empty($filters['payment_method']))

                        Payment Method:
                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $filters['payment_method']
                            )
                        ) }}

                        &nbsp; | &nbsp;

                    @endif


                    @if(!empty($filters['status']))

                        Status:
                        {{ $filters['status'] }}

                        &nbsp; | &nbsp;

                    @endif


                    @if(!empty($filters['from']))

                        From:
                        {{ $filters['from'] }}

                        &nbsp; | &nbsp;

                    @endif


                    @if(!empty($filters['to']))

                        To:
                        {{ $filters['to'] }}

                    @endif

                </td>

            </tr>

        </table>

    @endif


    {{-- =========================================================
        DOCTOR REVENUE SUMMARY
    ========================================================== --}}

    <div class="doctor-heading">

        Doctor-wise Revenue Summary

    </div>


    <table class="report-table">

        <thead>

            <tr>

                <th width="5%" class="text-center">
                    #
                </th>

                <th width="28%">
                    Doctor
                </th>

                <th width="20%" class="text-right">
                    Total Revenue
                </th>

                <th width="15%" class="text-center">
                    Transactions
                </th>

                <th width="15%" class="text-center">
                    Payment Plans
                </th>

                <th width="17%" class="text-right">
                    Avg. / Transaction
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($doctorRevenue as $row)

                <tr>

                    <td class="text-center">

                        {{ $loop->iteration }}

                    </td>


                    <td>

                        {{ $row['doctor'] ?: 'Unknown' }}

                    </td>


                    <td class="text-right revenue">

                        {{ number_format(
                            $row['revenue'],
                            2
                        ) }}

                    </td>


                    <td class="text-center">

                        {{ number_format(
                            $row['transactions']
                        ) }}

                    </td>


                    <td class="text-center">

                        {{ number_format(
                            $row['payment_plans']
                        ) }}

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            $row['transactions'] > 0
                                ? $row['revenue'] /
                                  $row['transactions']
                                : 0,
                            2
                        ) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="6"
                        class="text-center"
                    >

                        No doctor revenue records found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =========================================================
        PAYMENT DETAILS
    ========================================================== --}}

    <div class="doctor-heading">

        Payment Details

    </div>


    <table class="report-table">

        <thead>

            <tr>

                <th width="4%" class="text-center">
                    #
                </th>

                <th width="12%">
                    Predict3D ID
                </th>

                <th width="18%">
                    Patient
                </th>

                <th width="15%">
                    Doctor
                </th>

                <th width="13%" class="text-right">
                    Plan Amount
                </th>

                <th width="13%" class="text-right">
                    Collected
                </th>

                <th width="13%" class="text-right">
                    Remaining
                </th>

                <th width="12%">
                    Method
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($payments as $paymentPlan)

                @php

                    $collected = $paymentPlan->payments
                        ->sum('amount');

                    $remaining =
                        (float) $paymentPlan->remaining_amount;

                @endphp


                <tr>

                    <td class="text-center">

                        {{ $loop->iteration }}

                    </td>


                    <td>

                        {{ $paymentPlan->predict3d_id ?: '-' }}

                    </td>


                    <td>

                        {{ $paymentPlan->patient?->FullName ?: '-' }}

                    </td>


                    <td>

                        {{ $paymentPlan->patient?->DoctorName ?: 'Unknown' }}

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            (float) $paymentPlan->total_amount,
                            2
                        ) }}

                    </td>


                    <td class="text-right revenue">

                        {{ number_format(
                            $collected,
                            2
                        ) }}

                    </td>


                    <td class="text-right due">

                        {{ number_format(
                            $remaining,
                            2
                        ) }}

                    </td>


                    <td>

                        {{ $paymentPlan->payment_method
                            ? ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $paymentPlan->payment_method
                                )
                            )
                            : '-'
                        }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="8"
                        class="text-center"
                    >

                        No payment records found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <div class="footer">

        Doctor Revenue Report &bull; DentLab-OS

    </div>

</body>
</html>