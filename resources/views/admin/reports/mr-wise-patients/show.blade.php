@extends('layouts.dashboard')

@section('title', 'MR Patient Details')

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

                        <i class="fas fa-user-tie me-2 text-info"></i>

                        MR Patient Details

                    </h3>

                    <p class="text-muted mb-0">

                        Patient records associated with
                        <strong>{{ $mr }}</strong>.

                    </p>

                </div>


                <div class="mt-3 mt-md-0">

                    <a
                        href="{{ route(
                            'admin.reports.mr-wise-patients.index'
                        ) }}"
                        class="btn btn-light border shadow-sm"
                    >

                        <i class="fas fa-arrow-left me-1"></i>

                        Back to MR-wise Patients

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Total Patients --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Total Patients
                    </small>

                    <h3 class="fw-bold text-primary mb-0 mt-1">

                        {{ number_format(
                            $totalPatients
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        {{-- Active Patients --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Active Patients
                    </small>

                    <h3 class="fw-bold text-success mb-0 mt-1">

                        {{ number_format(
                            $activePatients
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        {{-- Inactive Patients --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Inactive Patients
                    </small>

                    <h3 class="fw-bold text-secondary mb-0 mt-1">

                        {{ number_format(
                            $inactivePatients
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
                        Doctors
                    </small>

                    <h3 class="fw-bold text-info mb-0 mt-1">

                        {{ number_format(
                            $doctors->count()
                        ) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        MR INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-user-tie me-2 text-info"></i>

                Marketing Representative Information

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4 text-center">

                {{-- MR --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Marketing Representative
                    </small>

                    <h4 class="fw-bold text-info mb-0 mt-1">

                        {{ $mr }}

                    </h4>

                </div>


                {{-- Active Percentage --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Active Patient Rate
                    </small>

                    <h4 class="fw-bold text-success mb-0 mt-1">

                        {{ $totalPatients > 0
                            ? number_format(
                                (
                                    $activePatients /
                                    $totalPatients
                                ) * 100,
                                1
                            )
                            : '0.0'
                        }}%

                    </h4>

                </div>


                {{-- Average Patients / Doctor --}}

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Average Patients / Doctor
                    </small>

                    <h4 class="fw-bold text-primary mb-0 mt-1">

                        {{ $doctors->count() > 0
                            ? number_format(
                                $totalPatients /
                                $doctors->count(),
                                1
                            )
                            : '0.0'
                        }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DOCTORS UNDER MR
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-user-md me-2 text-primary"></i>

                Doctors Associated with this MR

            </h5>

        </div>


        <div class="card-body">

            @if($doctors->isNotEmpty())

                <div class="row g-2">

                    @foreach($doctors as $doctor)

                        <div class="col-lg-3 col-md-4 col-sm-6">

                            <div class="border rounded p-3">

                                <i class="fas fa-user-md text-primary me-2"></i>

                                <span class="fw-semibold">

                                    {{ $doctor }}

                                </span>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-center text-muted py-3">

                    <i class="fas fa-user-md fa-2x mb-2"></i>

                    <p class="mb-0">

                        No doctors found for this MR.

                    </p>

                </div>

            @endif

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

                    Patients under {{ $mr }}

                </h5>


                <span class="badge bg-primary">

                    {{ number_format(
                        $totalPatients
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

                            <th class="text-center">
                                Gender
                            </th>

                            <th>
                                Doctor
                            </th>

                            <th>
                                Chamber
                            </th>

                            <th>
                                Scanning Type
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

                                {{-- Serial --}}

                                <td class="text-center">

                                    {{ $loop->iteration }}

                                </td>


                                {{-- Predict3D ID --}}

                                <td>

                                    <span class="fw-semibold">

                                        {{ $patient->Predict3DId ?: '-' }}

                                    </span>

                                </td>


                                {{-- Patient --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $patient->FullName ?: '-' }}

                                    </div>

                                </td>


                                {{-- Phone --}}

                                <td>

                                    {{ $patient->PhoneNumber ?: '-' }}

                                </td>


                                {{-- Gender --}}

                                <td class="text-center">

                                    {{ $patient->Gender ?: '-' }}

                                </td>


                                {{-- Doctor --}}

                                <td>

                                    {{ $patient->DoctorName ?: '-' }}

                                </td>


                                {{-- Chamber --}}

                                <td>

                                    {{ $patient->ChamberName ?: '-' }}

                                </td>


                                {{-- Scanning Type --}}

                                <td>

                                    @if($patient->ScanningFor)

                                        <span class="badge bg-info text-dark">

                                            {{ $patient->ScanningFor }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- Status --}}

                                <td class="text-center">

                                    @if($patient->status === 'active')

                                        <span class="badge bg-success">

                                            <i class="fas fa-check-circle me-1"></i>

                                            Active

                                        </span>

                                    @elseif($patient->status === 'inactive')

                                        <span class="badge bg-secondary">

                                            <i class="fas fa-clock me-1"></i>

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


                                {{-- Created --}}

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
                                    colspan="10"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="fas fa-users-slash fa-2x mb-3"></i>

                                        <p class="mb-0 fw-semibold">

                                            No patients found for this MR.

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
                'admin.reports.mr-wise-patients.index'
            ) }}"
            class="btn btn-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back to Report

        </a>

    </div>

</div>

@endsection