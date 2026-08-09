<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>MR Performance Report</title>

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

        .section-heading {
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 7px;
            margin-top: 12px;
            margin-bottom: 5px;
            font-size: 10px;
            font-weight: bold;
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

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .positive {
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
            MR Performance Report
        </h1>

        <p>
            Marketing Representative Performance
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
                    Marketing Representatives
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_mrs']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Total Patients
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_patients']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Collected Revenue
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['collected_revenue'],
                        2
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Outstanding Due
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['outstanding_due'],
                        2
                    ) }}

                </div>

            </td>

        </tr>

    </table>


    {{-- =========================================================
        PERFORMANCE OVERVIEW
    ========================================================== --}}

    <div class="section-heading">

        Performance Overview

    </div>


    <table class="report-table">

        <thead>

            <tr>

                <th>
                    Metric
                </th>

                <th class="text-right">
                    Value
                </th>

            </tr>

        </thead>


        <tbody>

            <tr>

                <td>
                    Doctors Covered
                </td>

                <td class="text-right">

                    {{ number_format(
                        $summary['total_doctors']
                    ) }}

                </td>

            </tr>


            <tr>

                <td>
                    Active Patients
                </td>

                <td class="text-right">

                    {{ number_format(
                        $summary['active_patients']
                    ) }}

                </td>

            </tr>


            <tr>

                <td>
                    Payment Plans
                </td>

                <td class="text-right">

                    {{ number_format(
                        $summary['payment_plans']
                    ) }}

                </td>

            </tr>


            <tr>

                <td>
                    Paid Plans
                </td>

                <td class="text-right">

                    {{ number_format(
                        $summary['paid_plans']
                    ) }}

                </td>

            </tr>


            <tr>

                <td>
                    Planned Revenue
                </td>

                <td class="text-right">

                    {{ number_format(
                        $summary['planned_revenue'],
                        2
                    ) }}

                </td>

            </tr>


            <tr>

                <td>
                    Collected Revenue
                </td>

                <td class="text-right">

                    {{ number_format(
                        $summary['collected_revenue'],
                        2
                    ) }}

                </td>

            </tr>


            <tr>

                <td>
                    Outstanding Due
                </td>

                <td class="text-right">

                    {{ number_format(
                        $summary['outstanding_due'],
                        2
                    ) }}

                </td>

            </tr>


            <tr>

                <td>
                    Collection Rate
                </td>

                <td class="text-right positive">

                    {{ number_format(
                        $summary['collection_rate'],
                        2
                    ) }}%

                </td>

            </tr>


            <tr>

                <td>
                    Average Revenue / Patient
                </td>

                <td class="text-right">

                    {{ number_format(
                        $summary[
                            'average_revenue_per_patient'
                        ],
                        2
                    ) }}

                </td>

            </tr>

        </tbody>

    </table>


    {{-- =========================================================
        APPLIED FILTERS
    ========================================================== --}}

    @if(
        !empty($filters['search']) ||
        !empty($filters['mr']) ||
        !empty($filters['doctor']) ||
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


                    @if(!empty($filters['mr']))

                        MR:
                        {{ $filters['mr'] }}

                        &nbsp; | &nbsp;

                    @endif


                    @if(!empty($filters['doctor']))

                        Doctor:
                        {{ $filters['doctor'] }}

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
        MR PERFORMANCE TABLE
    ========================================================== --}}

    <div class="section-heading">

        Marketing Representative Performance Ranking

    </div>


    <table class="report-table">

        <thead>

            <tr>

                <th width="5%" class="text-center">
                    Rank
                </th>

                <th width="20%">
                    Marketing Representative
                </th>

                <th width="8%" class="text-center">
                    Patients
                </th>

                <th width="8%" class="text-center">
                    Doctors
                </th>

                <th width="8%" class="text-center">
                    Plans
                </th>

                <th width="12%" class="text-right">
                    Planned
                </th>

                <th width="12%" class="text-right">
                    Collected
                </th>

                <th width="10%" class="text-right">
                    Due
                </th>

                <th width="9%" class="text-center">
                    Rate
                </th>

                <th width="8%" class="text-right">
                    Avg / Patient
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($performance as $row)

                <tr>

                    <td class="text-center">

                        {{ $loop->iteration }}

                    </td>


                    <td>

                        {{ $row['mr'] }}

                    </td>


                    <td class="text-center">

                        {{ number_format(
                            $row['patients']
                        ) }}

                    </td>


                    <td class="text-center">

                        {{ number_format(
                            $row['doctors']
                        ) }}

                    </td>


                    <td class="text-center">

                        {{ number_format(
                            $row['payment_plans']
                        ) }}

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            $row['planned_revenue'],
                            2
                        ) }}

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            $row['collected_revenue'],
                            2
                        ) }}

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            $row['outstanding_due'],
                            2
                        ) }}

                    </td>


                    <td class="text-center">

                        {{ number_format(
                            $row['collection_rate'],
                            1
                        ) }}%

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            $row[
                                'average_revenue_per_patient'
                            ],
                            2
                        ) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="10"
                        class="text-center"
                    >

                        No performance records found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <div class="footer">

        MR Performance Report &bull; DentLab-OS

    </div>

</body>
</html>