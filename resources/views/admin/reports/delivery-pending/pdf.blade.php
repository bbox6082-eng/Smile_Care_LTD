<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>Delivery Pending Report</title>

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
            font-size: 7px;
            text-align: left;
        }

        .report-table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 7px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .status {
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
            Delivery Pending Report
        </h1>

        <p>
            Pending upper and lower case deliveries
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
                    Pending Cases
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_cases']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Total Pending
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_pending']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Pending Upper
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['remaining_upper']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Pending Lower
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['remaining_lower']
                    ) }}

                </div>

            </td>

        </tr>


        <tr>

            <td>

                <div class="summary-label">
                    Total Upper
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_upper']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Delivered Upper
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['delivered_upper']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Total Lower
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_lower']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Delivered Lower
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['delivered_lower']
                    ) }}

                </div>

            </td>

        </tr>


        <tr>

            <td colspan="4">

                <div class="summary-label">
                    Average Delivery Progress
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['average_delivery_percentage'],
                        2
                    ) }}%

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
        !empty($filters['delivery_status']) ||
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


                    @if(!empty($filters['delivery_status']))

                        Delivery Status:

                        {{ ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $filters['delivery_status']
                            )
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
        DELIVERY PENDING TABLE
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

                <th width="10%" class="text-center">
                    Upper
                </th>

                <th width="10%" class="text-center">
                    Lower
                </th>

                <th width="8%" class="text-center">
                    Pending
                </th>

                <th width="10%" class="text-center">
                    Progress
                </th>

                <th width="10%">
                    Status
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($payments as $payment)

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


                    <td class="text-center">

                        Total:
                        {{ $payment->total_upper }}

                        <br>

                        Delivered:
                        {{ $payment->delivered_upper }}

                        <br>

                        <strong>
                            Pending:
                            {{ $payment->remaining_upper }}
                        </strong>

                    </td>


                    <td class="text-center">

                        Total:
                        {{ $payment->total_lower }}

                        <br>

                        Delivered:
                        {{ $payment->delivered_lower }}

                        <br>

                        <strong>
                            Pending:
                            {{ $payment->remaining_lower }}
                        </strong>

                    </td>


                    <td class="text-center">

                        <strong>

                            {{ $payment->remaining_cases }}

                        </strong>

                    </td>


                    <td class="text-center">

                        {{ number_format(
                            $payment->delivery_percentage,
                            2
                        ) }}%

                    </td>


                    <td class="status">

                        @switch($payment->delivery_status)

                            @case('both_pending')

                                Both Pending

                                @break

                            @case('upper_pending')

                                Upper Pending

                                @break

                            @case('lower_pending')

                                Lower Pending

                                @break

                            @default

                                Pending

                        @endswitch

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="9"
                        class="text-center"
                        style="padding:20px;"
                    >

                        No pending deliveries found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <div class="footer">

        Delivery Pending Report &bull; DentLab-OS

    </div>

</body>
</html>