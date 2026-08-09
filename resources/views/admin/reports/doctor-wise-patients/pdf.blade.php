<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>Doctor-wise Patients Report</title>

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

        .status-active {
            font-weight: bold;
        }

        .status-inactive {
            color: #777;
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
            Doctor-wise Patients Report
        </h1>

        <p>
            Patient distribution by doctor
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
                    Total Doctors
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['total_doctors']
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
                    Inactive Patients
                </div>

                <div class="summary-value">

                    {{ number_format(
                        $summary['inactive_patients']
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
        !empty($filters['status']) ||
        !empty($filters['gender']) ||
        !empty($filters['scanning_for'])
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


                    @if(!empty($filters['status']))

                        Status:
                        {{ ucfirst($filters['status']) }}
                        &nbsp; | &nbsp;

                    @endif


                    @if(!empty($filters['gender']))

                        Gender:
                        {{ $filters['gender'] }}
                        &nbsp; | &nbsp;

                    @endif


                    @if(!empty($filters['scanning_for']))

                        Scanning Type:
                        {{ $filters['scanning_for'] }}

                    @endif

                </td>

            </tr>

        </table>

    @endif


    {{-- =========================================================
        DOCTOR-WISE PATIENT TABLE
    ========================================================== --}}

    @forelse($doctorGroups as $doctor => $doctorPatients)

        <div class="doctor-heading">

            Doctor:
            {{ $doctor }}

            &nbsp;&nbsp;

            <span style="font-weight:normal;">

                Patients:
                {{ number_format(
                    $doctorPatients->count()
                ) }}

            </span>

        </div>


        <table class="report-table">

            <thead>

                <tr>

                    <th width="4%" class="text-center">
                        #
                    </th>

                    <th width="10%">
                        Predict3D ID
                    </th>

                    <th width="18%">
                        Patient
                    </th>

                    <th width="11%">
                        Phone
                    </th>

                    <th width="8%" class="text-center">
                        Gender
                    </th>

                    <th width="11%">
                        Scanning Type
                    </th>

                    <th width="9%" class="text-center">
                        Status
                    </th>

                    <th width="15%">
                        Chamber
                    </th>

                    <th width="10%">
                        Created
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($doctorPatients as $patient)

                    <tr>

                        <td class="text-center">

                            {{ $loop->iteration }}

                        </td>


                        <td>

                            {{ $patient->Predict3DId ?: '-' }}

                        </td>


                        <td>

                            {{ $patient->FullName ?: '-' }}

                            @if($patient->Email)

                                <br>

                                <span style="color:#777;">

                                    {{ $patient->Email }}

                                </span>

                            @endif

                        </td>


                        <td>

                            {{ $patient->PhoneNumber ?: '-' }}

                        </td>


                        <td class="text-center">

                            {{ $patient->Gender ?: '-' }}

                        </td>


                        <td>

                            {{ $patient->ScanningFor ?: '-' }}

                        </td>


                        <td class="text-center">

                            @if($patient->status === 'active')

                                <span class="status-active">
                                    Active
                                </span>

                            @elseif($patient->status === 'inactive')

                                <span class="status-inactive">
                                    Inactive
                                </span>

                            @else

                                {{ $patient->status
                                    ? ucfirst($patient->status)
                                    : '-'
                                }}

                            @endif

                        </td>


                        <td>

                            {{ $patient->ChamberName ?: '-' }}

                        </td>


                        <td>

                            {{ $patient->created_at
                                ? $patient->created_at->format('d M Y')
                                : '-'
                            }}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @empty

        <table class="report-table">

            <tr>

                <td
                    colspan="9"
                    class="text-center"
                    style="padding:20px;"
                >

                    No patients found.

                </td>

            </tr>

        </table>

    @endforelse


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <div class="footer">

        Doctor-wise Patients Report &bull; DentLab-OS

    </div>

</body>
</html>