@extends('layouts.dashboard')

@section('title', 'MR Performance Details')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h3 class="fw-bold mb-1">

                        <i class="fas fa-chart-line me-2 text-info"></i>

                        MR Performance Details

                    </h3>

                    <p class="text-muted mb-0">

                        Detailed performance information for
                        <strong>{{ $mr }}</strong>.

                    </p>

                </div>


                <div class="mt-3 mt-md-0">

                    <a
                        href="{{ route(
                            'admin.reports.mr-performance.index'
                        ) }}"
                        class="btn btn-light border shadow-sm"
                    >

                        <i class="fas fa-arrow-left me-1"></i>

                        Back to MR Performance

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PERFORMANCE SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Patients --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Total Patients
                    </small>

                    <h3 class="fw-bold text-primary mb-0 mt-1">

                        {{ number_format(
                            $performance['patients']
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        {{-- Doctors --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Doctors Covered
                    </small>

                    <h3 class="fw-bold text-info mb-0 mt-1">

                        {{ number_format(
                            $performance['doctors']
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        {{-- Collected Revenue --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Collected Revenue
                    </small>

                    <h3 class="fw-bold text-success mb-0 mt-1">

                        {{ number_format(
                            $performance['collected_revenue'],
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        {{-- Outstanding Due --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Outstanding Due
                    </small>

                    <h3 class="fw-bold text-warning mb-0 mt-1">

                        {{ number_format(
                            $performance['outstanding_due'],
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PERFORMANCE METRICS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-bar me-2 text-info"></i>

                Performance Metrics

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4 text-center">


                {{-- Active Patients --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Active Patients
                    </small>

                    <h4 class="fw-bold text-success mb-1 mt-1">

                        {{ number_format(
                            $performance['active']
                        ) }}

                    </h4>

                    <small class="text-muted">

                        Active patient records

                    </small>

                </div>


                {{-- Inactive Patients --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Inactive Patients
                    </small>

                    <h4 class="fw-bold text-secondary mb-1 mt-1">

                        {{ number_format(
                            $performance['inactive']
                        ) }}

                    </h4>

                    <small class="text-muted">

                        Inactive patient records

                    </small>

                </div>


                {{-- Payment Plans --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Payment Plans
                    </small>

                    <h4 class="fw-bold text-primary mb-1 mt-1">

                        {{ number_format(
                            $performance['payment_plans']
                        ) }}

                    </h4>

                    <small class="text-muted">

                        Total payment plans

                    </small>

                </div>


                {{-- Paid Plans --}}

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Paid Plans
                    </small>

                    <h4 class="fw-bold text-success mb-1 mt-1">

                        {{ number_format(
                            $performance['paid_plans']
                        ) }}

                    </h4>

                    <small class="text-muted">

                        Fully settled plans

                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        REVENUE PERFORMANCE
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-money-bill-trend-up me-2 text-success"></i>

                Revenue Performance

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Planned Revenue --}}

                <div class="col-lg-4">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Planned Revenue
                        </small>

                        <h4 class="fw-bold text-primary mb-1 mt-1">

                            {{ number_format(
                                $performance['planned_revenue'],
                                2
                            ) }}

                        </h4>

                        <small class="text-muted">

                            Total value of payment plans

                        </small>

                    </div>

                </div>


                {{-- Collected Revenue --}}

                <div class="col-lg-4">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Collected Revenue
                        </small>

                        <h4 class="fw-bold text-success mb-1 mt-1">

                            {{ number_format(
                                $performance['collected_revenue'],
                                2
                            ) }}

                        </h4>

                        <small class="text-muted">

                            Total payments received

                        </small>

                    </div>

                </div>


                {{-- Outstanding Due --}}

                <div class="col-lg-4">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Outstanding Due
                        </small>

                        <h4 class="fw-bold text-warning mb-1 mt-1">

                            {{ number_format(
                                $performance['outstanding_due'],
                                2
                            ) }}

                        </h4>

                        <small class="text-muted">

                            Remaining payment amount

                        </small>

                    </div>

                </div>

            </div>


            {{-- Collection Progress --}}

            <div class="mt-4">

                <div class="d-flex justify-content-between mb-2">

                    <span class="fw-semibold">
                        Collection Progress
                    </span>

                    <span class="fw-bold">

                        {{ number_format(
                            $performance['collection_rate'],
                            1
                        ) }}%

                    </span>

                </div>


                <div
                    class="progress"
                    style="height: 12px;"
                >

                    <div
                        class="progress-bar
                            @if($performance['collection_rate'] >= 90)
                                bg-success
                            @elseif($performance['collection_rate'] >= 70)
                                bg-info
                            @elseif($performance['collection_rate'] >= 50)
                                bg-warning
                            @else
                                bg-danger
                            @endif"
                        role="progressbar"
                        style="width:
                            {{ min(
                                100,
                                max(
                                    0,
                                    $performance['collection_rate']
                                )
                            ) }}%;"
                    ></div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PATIENT LIST
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">

                    <i class="fas fa-users me-2 text-info"></i>

                    Patients Managed by {{ $mr }}

                </h5>


                <span class="badge bg-primary">

                    {{ number_format(
                        $patients->count()
                    ) }}

                    Patients

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="text-center">
                                #
                            </th>

                            <th>
                                Predict3D ID
                            </th>

                            <th>
                                Patient
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Doctor
                            </th>

                            <th>
                                Scanning
                            </th>

                            <th class="text-center">
                                Status
                            </th>

                            <th>
                                Created
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

                                    <span class="fw-semibold">

                                        {{ $patient->Predict3DId ?: '-' }}

                                    </span>

                                </td>


                                <td>

                                    {{ $patient->FullName ?: '-' }}

                                </td>


                                <td>

                                    {{ $patient->PhoneNumber ?: '-' }}

                                </td>


                                <td>

                                    {{ $patient->DoctorName ?: '-' }}

                                </td>


                                <td>

                                    @if($patient->ScanningFor)

                                        <span class="badge bg-info text-dark">

                                            {{ $patient->ScanningFor }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>


                                <td class="text-center">

                                    @if($patient->status === 'active')

                                        <span class="badge bg-success">

                                            Active

                                        </span>

                                    @elseif($patient->status === 'inactive')

                                        <span class="badge bg-secondary">

                                            Inactive

                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark">

                                            {{ $patient->status
                                                ? ucfirst(
                                                    $patient->status
                                                )
                                                : 'Unknown'
                                            }}

                                        </span>

                                    @endif

                                </td>


                                <td>

                                    {{ $patient->created_at
                                        ? \Carbon\Carbon::parse(
                                            $patient->created_at
                                        )->format('d M Y')
                                        : '-'
                                    }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="fas fa-users-slash fa-2x mb-3"></i>

                                        <p class="mb-0 fw-semibold">

                                            No patients found.

                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <div class="d-flex justify-content-end mb-4">

        <a
            href="{{ route(
                'admin.reports.mr-performance.index'
            ) }}"
            class="btn btn-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back to Performance

        </a>

    </div>

</div>

@endsection