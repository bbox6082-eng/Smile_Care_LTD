<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>MR-wise Patients Report</title>

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
            MR-wise Patients Report
        </h1>

        <p>
            Patients grouped by Marketing Representative
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
                    Active Patients
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['active_patients']
                    ) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Unassigned Patients
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['unassigned_patients']
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
        !empty($filters['mr']) ||
        !empty($filters['doctor']) ||
        !empty($filters['status']) ||
        !empty($filters['gender']) ||
        !empty($filters['scanning_for']) ||
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


                    @if(!empty($filters['gender']))

                        Gender:
                        {{ $filters['gender'] }}

                        &nbsp; | &nbsp;

                    @endif


                    @if(!empty($filters['scanning_for']))

                        Scanning For:
                        {{ $filters['scanning_for'] }}

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
        MR SUMMARY
    ========================================================== --}}

    <div class="section-heading">

        Marketing Representative-wise Patient Summary

    </div>


    <table class="report-table">

        <thead>

            <tr>

                <th width="5%" class="text-center">
                    #
                </th>

                <th width="32%">
                    Marketing Representative
                </th>

                <th width="16%" class="text-center">
                    Patients
                </th>

                <th width="15%" class="text-center">
                    Active
                </th>

                <th width="15%" class="text-center">
                    Inactive
                </th>

                <th width="17%" class="text-center">
                    Doctors
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($mrGroups as $mr => $patients)

                <tr>

                    <td class="text-center">

                        {{ $loop->iteration }}

                    </td>


                    <td>

                        {{ $mr }}

                    </td>


                    <td class="text-center">

                        {{ number_format(
                            $patients->count()
                        ) }}

                    </td>


                    <td class="text-center">

                        {{ number_format(
                            $patients
                                ->where('status', 'active')
                                ->count()
                        ) }}

                    </td>


                    <td class="text-center">

                        {{ number_format(
                            $patients
                                ->where('status', 'inactive')
                                ->count()
                        ) }}

                    </td>


                    <td class="text-center">

                        {{ number_format(
                            $patients
                                ->pluck('DoctorName')
                                ->filter()
                                ->unique()
                                ->count()
                        ) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="6"
                        class="text-center"
                    >

                        No MR-wise patient records found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =========================================================
        PATIENT DETAILS
    ========================================================== --}}

    <div class="section-heading">

        Patient Details

    </div>


    <table class="report-table">

        <thead>

            <tr>

                <th width="4%" class="text-center">
                    #
                </th>

                <th width="13%">
                    MR
                </th>

                <th width="11%">
                    Predict3D ID
                </th>

                <th width="16%">
                    Patient
                </th>

                <th width="10%">
                    Phone
                </th>

                <th width="7%">
                    Gender
                </th>

                <th width="13%">
                    Doctor
                </th>

                <th width="11%">
                    Chamber
                </th>

                <th width="8%">
                    Scanning
                </th>

                <th width="7%">
                    Status
                </th>

            </tr>

        </thead>


        <tbody>

            @php
                $patientNumber = 0;
            @endphp


            @forelse($mrGroups as $mr => $patients)

                @foreach($patients as $patient)

                    <tr>

                        <td class="text-center">

                            {{ ++$patientNumber }}

                        </td>


                        <td>

                            {{ $mr }}

                        </td>


                        <td>

                            {{ $patient->Predict3DId ?: '-' }}

                        </td>


                        <td>

                            {{ $patient->FullName ?: '-' }}

                        </td>


                        <td>

                            {{ $patient->PhoneNumber ?: '-' }}

                        </td>


                        <td>

                            {{ $patient->Gender ?: '-' }}

                        </td>


                        <td>

                            {{ $patient->DoctorName ?: '-' }}

                        </td>


                        <td>

                            {{ $patient->ChamberName ?: '-' }}

                        </td>


                        <td>

                            {{ $patient->ScanningFor ?: '-' }}

                        </td>


                        <td>

                            {{ $patient->status
                                ? ucfirst($patient->status)
                                : '-'
                            }}

                        </td>

                    </tr>

                @endforeach

            @empty

                <tr>

                    <td
                        colspan="10"
                        class="text-center"
                    >

                        No patient records found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <div class="footer">

        MR-wise Patients Report &bull; DentLab-OS

    </div>

</body>
</html>