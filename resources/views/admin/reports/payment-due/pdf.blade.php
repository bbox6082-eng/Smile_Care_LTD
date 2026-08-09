<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>Payment Due Report</title>

    <style>

        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
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

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th {
            background: #f1f3f5;
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 7.5px;
            text-align: left;
        }

        .report-table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 7.5px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .overdue {
            font-weight: bold;
        }

        .upcoming {
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
            Payment Due Report
        </h1>

        <p>
            Outstanding patient payments
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
                    Total Outstanding
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_outstanding'],
                        2
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Overdue Amount
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['overdue_amount'],
                        2
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Upcoming Amount
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['upcoming_amount'],
                        2
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Patients With Due
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['patients_with_due']
                    ) }}

                </div>

            </td>

        </tr>


        <tr>

            <td>

                <div class="summary-label">
                    Average Due
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['average_due'],
                        2
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Overdue Cases
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['overdue_count']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Upcoming Cases
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['upcoming_count']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Report Status
                </div>

                <div class="summary-value">

                    Payment Due

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
        (
            isset($filters['is_installment']) &&
            $filters['is_installment'] !== null &&
            $filters['is_installment'] !== ''
        ) ||
        !empty($filters['status']) ||
        !empty($filters['date_from']) ||
        !empty($filters['date_to'])
    )

        <table style="width:100%; margin-bottom:12px;">

            <tr>

                <td style="font-size:8px;">

                    <strong>Applied Filters:</strong>

                    @if(!empty($filters['search']))

                        Search:
                        {{ $filters['search'] }}
                        |

                    @endif


                    @if(!empty($filters['doctor']))

                        Doctor:
                        {{ $filters['doctor'] }}
                        |

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

                        |

                    @endif


                    @if(
                        isset($filters['is_installment']) &&
                        $filters['is_installment'] !== null &&
                        $filters['is_installment'] !== ''
                    )

                        Payment Type:

                        {{ $filters['is_installment'] == '1'
                            ? 'Installment'
                            : 'Full Payment'
                        }}

                        |

                    @endif


                    @if(!empty($filters['status']))

                        Status:

                        {{ ucfirst(
                            $filters['status']
                        ) }}

                        |

                    @endif


                    @if(!empty($filters['date_from']))

                        From:
                        {{ $filters['date_from'] }}
                        |

                    @endif


                    @if(!empty($filters['date_to']))

                        To:
                        {{ $filters['date_to'] }}

                    @endif

                </td>

            </tr>

        </table>

    @endif


    {{-- =========================================================
        PAYMENT DUE TABLE
    ========================================================== --}}

    <table class="report-table">

        <thead>

            <tr>

                <th width="4%" class="text-center">
                    #
                </th>

                <th width="9%">
                    Predict3D ID
                </th>

                <th width="13%">
                    Patient
                </th>

                <th width="12%">
                    Doctor
                </th>

                <th width="10%">
                    Payment Method
                </th>

                <th width="9%" class="text-right">
                    Total
                </th>

                <th width="9%" class="text-right">
                    Paid
                </th>

                <th width="9%" class="text-right">
                    Due
                </th>

                <th width="10%">
                    Next Payment
                </th>

                <th width="9%">
                    Status
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($payments as $payment)

                @php

                    $paidAmount = max(
                        0,
                        (float) $payment->total_amount -
                        (float) $payment->remaining_amount
                    );

                    if (
                        $payment->next_payment_date &&
                        \Carbon\Carbon::parse(
                            $payment->next_payment_date
                        )->lt(\Carbon\Carbon::today())
                    ) {

                        $dueStatus = 'Overdue';

                    } elseif ($payment->next_payment_date) {

                        $dueStatus = 'Upcoming';

                    } else {

                        $dueStatus = 'Pending';

                    }

                @endphp

                <tr>

                    <td class="text-center">

                        {{ $loop->iteration }}

                    </td>


                    <td>

                        {{ $payment->predict3d_id ?: '-' }}

                    </td>


                    <td>

                        {{ $payment->patient?->FullName ?: '-' }}

                    </td>


                    <td>

                        {{ $payment->patient?->DoctorName ?: '-' }}

                    </td>


                    <td>

                        {{ $payment->payment_method
                            ? ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $payment->payment_method
                                )
                            )
                            : '-' }}

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            (float) $payment->total_amount,
                            2
                        ) }}

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            $paidAmount,
                            2
                        ) }}

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            (float) $payment->remaining_amount,
                            2
                        ) }}

                    </td>


                    <td>

                        {{ $payment->next_payment_date
                            ? \Carbon\Carbon::parse(
                                $payment->next_payment_date
                            )->format('d M Y')
                            : '-' }}

                    </td>


                    <td
                        class="{{ strtolower($dueStatus) }}"
                    >

                        {{ $dueStatus }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="10"
                        class="text-center"
                        style="padding:20px;"
                    >

                        No payment dues found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <div class="footer">

        Payment Due Report &bull; DentLab-OS

    </div>

</body>
</html>