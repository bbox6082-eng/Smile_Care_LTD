<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>Completed Cases Report</title>

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

        .completed {
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
            Completed Cases Report
        </h1>

        <p>
            Completed treatment cases
        </p>

        @if(!empty($generated_at))

            <p>
                Generated:
                {{ $generated_at->format('d M Y h:i A') }}
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
                    Completed Cases
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_cases']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Doctors
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_doctors']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Total Case Value
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_amount'],
                        2
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Remaining Amount
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['remaining_amount'],
                        2
                    ) }}

                </div>

            </td>

        </tr>


        <tr>

            <td>

                <div class="summary-label">
                    Paid Amount
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['paid_amount'],
                        2
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Installment Cases
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['installment_cases']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Full Payment Cases
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['full_payment_cases']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Total Deliveries
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_deliveries']
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
        (
            isset($filters['is_installment']) &&
            $filters['is_installment'] !== null &&
            $filters['is_installment'] !== ''
        ) ||
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
        COMPLETED CASE TABLE
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
                    Remaining
                </th>

                <th width="8%">
                    Installment
                </th>

                <th width="9%">
                    Status
                </th>

                <th width="8%">
                    Closed
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($cases as $case)

                @php

                    $paidAmount = max(
                        0,
                        (float) $case->total_amount -
                        (float) $case->remaining_amount
                    );

                @endphp

                <tr>

                    <td class="text-center">

                        {{ $loop->iteration }}

                    </td>


                    <td>

                        {{ $case->predict3d_id ?: '-' }}

                    </td>


                    <td>

                        {{ $case->patient?->FullName ?: '-' }}

                    </td>


                    <td>

                        {{ $case->patient?->DoctorName ?: '-' }}

                    </td>


                    <td>

                        {{ $case->payment_method
                            ? ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $case->payment_method
                                )
                            )
                            : '-' }}

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            (float) $case->total_amount,
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
                            (float) $case->remaining_amount,
                            2
                        ) }}

                    </td>


                    <td>

                        {{ $case->is_installment
                            ? 'Yes'
                            : 'No' }}

                    </td>


                    <td class="completed">

                        Completed

                    </td>


                    <td>

                        {{ $case->updated_at
                            ? $case->updated_at->format('d M Y')
                            : '-' }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="11"
                        class="text-center"
                        style="padding:20px;"
                    >

                        No completed cases found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <div class="footer">

        Completed Cases Report &bull; DentLab-OS

    </div>

</body>
</html>