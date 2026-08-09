<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>Patient by Doctor Report</title>

    <style>

        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 9px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
            width: 25%;
        }

        .summary-label {
            color: #777;
            font-size: 9px;
        }

        .summary-value {
            font-size: 14px;
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
            padding: 6px;
            font-size: 8.5px;
            text-align: left;
        }

        .report-table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 8px;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 8px;
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
            Patient by Doctor Report
        </h1>

        <p>
            Patient records organized by assigned doctor
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
                    Total Patients
                </div>

                <div class="summary-value">

                    {{ number_format($summary['total_patients']) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Total Doctors
                </div>

                <div class="summary-value">

                    {{ number_format($summary['total_doctors']) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Male Patients
                </div>

                <div class="summary-value">

                    {{ number_format($summary['male_patients']) }}

                </div>

            </td>


            <td>

                <div class="summary-label">
                    Female Patients
                </div>

                <div class="summary-value">

                    {{ number_format($summary['female_patients']) }}

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
        !empty($filters['gender']) ||
        !empty($filters['scanning_for']) ||
        !empty($filters['region']) ||
        !empty($filters['territory']) ||
        !empty($filters['date_from']) ||
        !empty($filters['date_to'])
    )

        <table style="width:100%; margin-bottom:15px;">

            <tr>

                <td style="font-size:9px;">

                    <strong>Applied Filters:</strong>

                    @if(!empty($filters['search']))
                        Search: {{ $filters['search'] }} |
                    @endif

                    @if(!empty($filters['doctor']))
                        Doctor: {{ $filters['doctor'] }} |
                    @endif

                    @if(!empty($filters['gender']))
                        Gender: {{ $filters['gender'] }} |
                    @endif

                    @if(!empty($filters['scanning_for']))
                        Scanning For: {{ $filters['scanning_for'] }} |
                    @endif

                    @if(!empty($filters['region']))
                        Region: {{ $filters['region'] }} |
                    @endif

                    @if(!empty($filters['territory']))
                        Territory: {{ $filters['territory'] }} |
                    @endif

                    @if(!empty($filters['date_from']))
                        From: {{ $filters['date_from'] }} |
                    @endif

                    @if(!empty($filters['date_to']))
                        To: {{ $filters['date_to'] }}
                    @endif

                </td>

            </tr>

        </table>

    @endif


    {{-- =========================================================
        PATIENT TABLE
    ========================================================== --}}

    <table class="report-table">

        <thead>

            <tr>

                <th width="4%" class="text-center">
                    #
                </th>

                <th width="14%">
                    Doctor
                </th>

                <th width="15%">
                    Patient
                </th>

                <th width="10%">
                    Predict3D ID
                </th>

                <th width="9%">
                    Phone
                </th>

                <th width="7%">
                    Gender
                </th>

                <th width="10%">
                    Scanning
                </th>

                <th width="9%">
                    Chamber
                </th>

                <th width="8%">
                    Region
                </th>

                <th width="8%">
                    Territory
                </th>

                <th width="8%">
                    Registered
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($patients as $patient)

                <tr>

                    <td class="text-center">

                        {{ $loop->iteration }}

                    </td>


                    <td>

                        {{ $patient->DoctorName ?: '-' }}

                    </td>


                    <td>

                        {{ $patient->FullName ?: '-' }}

                    </td>


                    <td>

                        {{ $patient->Predict3DId ?: '-' }}

                    </td>


                    <td>

                        {{ $patient->PhoneNumber ?: '-' }}

                    </td>


                    <td>

                        {{ $patient->Gender ?: '-' }}

                    </td>


                    <td>

                        {{ $patient->ScanningFor ?: '-' }}

                    </td>


                    <td>

                        {{ $patient->ChamberName ?: '-' }}

                    </td>


                    <td>

                        {{ $patient->RegionalName ?: '-' }}

                    </td>


                    <td>

                        {{ $patient->TerritoryName ?: '-' }}

                    </td>


                    <td>

                        {{ $patient->created_at
                            ? $patient->created_at->format('d M Y')
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

        Patient by Doctor Report &bull; DentLab-OS

    </div>

</body>
</html>