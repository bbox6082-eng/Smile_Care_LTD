@extends('layouts.dashboard')

@section('title', 'Doctor Patient Details')

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

                        <i class="fas fa-user-md me-2 text-primary"></i>

                        Doctor Patient Details

                    </h3>

                    <p class="text-muted mb-0">

                        Patient records associated with this doctor.

                    </p>

                </div>


                <div class="mt-3 mt-md-0">

                    <a
                        href="{{ route(
                            'admin.reports.doctor-wise-patients.index'
                        ) }}"
                        class="btn btn-light border shadow-sm"
                    >

                        <i class="fas fa-arrow-left me-1"></i>

                        Back to Doctor-wise Patients

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DOCTOR INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-user-md me-2 text-primary"></i>

                Doctor Information

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4 text-center">

                {{-- Doctor --}}

                <div class="col-lg-4 col-md-6">

                    <small class="text-muted d-block">
                        Doctor
                    </small>

                    <h4 class="fw-bold text-primary mb-0 mt-1">

                        {{ $doctor }}

                    </h4>

                </div>


                {{-- Total Patients --}}

                <div class="col-lg-4 col-md-6">

                    <small class="text-muted d-block">
                        Total Patients
                    </small>

                    <h4 class="fw-bold mb-0 mt-1">

                        {{ number_format(
                            $totalPatients
                        ) }}

                    </h4>

                </div>


                {{-- Active Patients --}}

                <div class="col-lg-4 col-md-6">

                    <small class="text-muted d-block">
                        Active Patients
                    </small>

                    <h4 class="fw-bold text-success mb-0 mt-1">

                        {{ number_format(
                            $activePatients
                        ) }}

                    </h4>

                </div>

            </div>


            <hr>


            <div class="row g-4 text-center">

                {{-- Inactive --}}

                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Inactive Patients
                    </small>

                    <h4 class="fw-bold text-secondary mb-0 mt-1">

                        {{ number_format(
                            $inactivePatients
                        ) }}

                    </h4>

                </div>


                {{-- Active Percentage --}}

                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Active Patient Percentage
                    </small>

                    <h4 class="fw-bold text-success mb-0 mt-1">

                        {{ $totalPatients > 0
                            ? number_format(
                                ($activePatients / $totalPatients) * 100,
                                1
                            )
                            : '0.0'
                        }}%

                    </h4>

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

                    Patients of {{ $doctor }}

                </h5>


                <span class="badge bg-primary">

                    {{ number_format($totalPatients) }}

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
                                Scanning Type
                            </th>

                            <th class="text-center">
                                Status
                            </th>

                            <th>
                                Chamber
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

                                    @if($patient->Email)

                                        <small class="text-muted">

                                            {{ $patient->Email }}

                                        </small>

                                    @endif

                                </td>


                                {{-- Phone --}}

                                <td>

                                    {{ $patient->PhoneNumber ?: '-' }}

                                </td>


                                {{-- Gender --}}

                                <td class="text-center">

                                    {{ $patient->Gender ?: '-' }}

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
                                                ? ucfirst($patient->status)
                                                : 'Unknown'
                                            }}

                                        </span>

                                    @endif

                                </td>


                                {{-- Chamber --}}

                                <td>

                                    {{ $patient->ChamberName ?: '-' }}

                                </td>


                                {{-- Created --}}

                                <td>

                                    {{ $patient->created_at
                                        ? $patient->created_at->format('d M Y')
                                        : '-'
                                    }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="fas fa-users-slash fa-2x mb-3"></i>

                                        <p class="mb-0 fw-semibold">

                                            No patients found for this doctor.

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
                'admin.reports.doctor-wise-patients.index'
            ) }}"
            class="btn btn-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back to Report

        </a>

    </div>

</div>

@endsection