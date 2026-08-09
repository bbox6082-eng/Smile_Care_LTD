@extends('layouts.dashboard')

@section('title', 'Doctor-wise Patients Report')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fas fa-user-md me-2 text-primary"></i>

                Doctor-wise Patients

            </h3>

            <p class="text-muted mb-0">

                View and analyze patients grouped by doctor.

            </p>

        </div>


        <div class="d-flex gap-2 mt-3 mt-md-0">

        
            <a href="{{ route('admin.dashboard') }}"
            class="btn btn-light border shadow-sm">

                <i class="fas fa-home me-1"></i>

                Dashboard

            </a>

            <a href="{{ route('admin.reports.index') }}"
            class="btn btn-light border shadow-sm">

                <i class="fas fa-chart-bar me-1"></i>

                Reports

            </a>

            <a
                href="{{ route(
                    'admin.reports.doctor-wise-patients.export.excel',
                    request()->query()
                ) }}"
                class="btn btn-success"
            >

                <i class="fas fa-file-excel me-1"></i>

                Excel

            </a>


            <a
                href="{{ route(
                    'admin.reports.doctor-wise-patients.export.pdf',
                    request()->query()
                ) }}"
                class="btn btn-danger"
            >

                <i class="fas fa-file-pdf me-1"></i>

                PDF

            </a>

        </div>

    </div>


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Total Doctors --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Total Doctors
                            </p>

                            <h3 class="fw-bold text-primary mb-0">

                                {{ number_format(
                                    $summary['total_doctors']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-primary fs-2">

                            <i class="fas fa-user-md"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Patients --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Total Patients
                            </p>

                            <h3 class="fw-bold mb-0">

                                {{ number_format(
                                    $summary['total_patients']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-info fs-2">

                            <i class="fas fa-users"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Active Patients --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Active Patients
                            </p>

                            <h3 class="fw-bold text-success mb-0">

                                {{ number_format(
                                    $summary['active_patients']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-success fs-2">

                            <i class="fas fa-user-check"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Inactive Patients --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Inactive Patients
                            </p>

                            <h3 class="fw-bold text-secondary mb-0">

                                {{ number_format(
                                    $summary['inactive_patients']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-secondary fs-2">

                            <i class="fas fa-user-clock"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DOCTOR DISTRIBUTION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-pie me-2 text-primary"></i>

                Doctor Distribution

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="border rounded p-3 text-center">

                        <small class="text-muted d-block">
                            Doctors
                        </small>

                        <h4 class="fw-bold text-primary mb-0 mt-1">

                            {{ number_format(
                                $summary['total_doctors']
                            ) }}

                        </h4>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3 text-center">

                        <small class="text-muted d-block">
                            Patients
                        </small>

                        <h4 class="fw-bold text-info mb-0 mt-1">

                            {{ number_format(
                                $summary['total_patients']
                            ) }}

                        </h4>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3 text-center">

                        <small class="text-muted d-block">
                            Average Patients / Doctor
                        </small>

                        <h4 class="fw-bold text-success mb-0 mt-1">

                            {{ $summary['total_doctors'] > 0
                                ? number_format(
                                    $summary['total_patients'] /
                                    $summary['total_doctors'],
                                    1
                                )
                                : '0.0'
                            }}

                        </h4>

                    </div>

                </div>

            </div>

        </div>

    </div>

        {{-- =========================================================
        FILTERS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-filter me-2 text-primary"></i>

                Filter Patients

            </h5>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reports.doctor-wise-patients.index') }}"
            >

                <div class="row g-3">

                    {{-- Search --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Patient, ID, phone, doctor..."
                        >

                    </div>


                    {{-- Doctor --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Doctor
                        </label>

                        <select
                            name="doctor"
                            class="form-select"
                        >

                            <option value="">
                                All Doctors
                            </option>

                            @foreach($doctors as $doctor)

                                <option
                                    value="{{ $doctor }}"
                                    @selected(request('doctor') === $doctor)
                                >

                                    {{ $doctor }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            @foreach($filterOptions['statuses'] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(request('status') === $status)
                                >

                                    {{ ucfirst($status) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Gender --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Gender
                        </label>

                        <select
                            name="gender"
                            class="form-select"
                        >

                            <option value="">
                                All Genders
                            </option>

                            @foreach($filterOptions['genders'] as $gender)

                                <option
                                    value="{{ $gender }}"
                                    @selected(request('gender') === $gender)
                                >

                                    {{ $gender }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Scanning Type --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Scanning Type
                        </label>

                        <select
                            name="scanning_for"
                            class="form-select"
                        >

                            <option value="">
                                All Types
                            </option>

                            @foreach($filterOptions['scanning_types'] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(request('scanning_for') === $type)
                                >

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="col-lg-3 col-md-6 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="fas fa-search me-1"></i>

                            Apply Filters

                        </button>


                        <a
                            href="{{ route(
                                'admin.reports.doctor-wise-patients.index'
                            ) }}"
                            class="btn btn-light border"
                        >

                            <i class="fas fa-rotate-left me-1"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        PATIENT RECORDS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">

                    <i class="fas fa-users me-2 text-primary"></i>

                    Patient Records

                </h5>


                <span class="badge bg-primary">

                    {{ number_format($patients->total()) }}

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
                                Doctor
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
                                Created
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($patients as $patient)

                            <tr>

                                {{-- Serial --}}

                                <td class="text-center">

                                    {{ $patients->firstItem() + $loop->index }}

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


                                {{-- Doctor --}}

                                <td>

                                    @if($patient->DoctorName)

                                        <span class="fw-semibold">

                                            {{ $patient->DoctorName }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            Unknown
                                        </span>

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


                                {{-- Created --}}

                                <td>

                                    {{ $patient->created_at
                                        ? $patient->created_at->format('d M Y')
                                        : '-'
                                    }}

                                </td>


                                {{-- Action --}}

                                <td class="text-center">

                                    @if($patient->DoctorName)

                                        <a
                                            href="{{ route(
                                                'admin.reports.doctor-wise-patients.show',
                                                [
                                                    'doctor' => $patient->DoctorName,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="View Doctor Patients"
                                        >

                                            <i class="fas fa-user-md"></i>

                                        </a>

                                    @else

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary"
                                            disabled
                                        >

                                            <i class="fas fa-user-md"></i>

                                        </button>

                                    @endif

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

                                            No patients found.

                                        </p>

                                        <small>

                                            Try changing your filters
                                            or search criteria.

                                        </small>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}

        @if($patients->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <small class="text-muted mb-2 mb-md-0">

                        Showing

                        <strong>
                            {{ $patients->firstItem() }}
                        </strong>

                        to

                        <strong>
                            {{ $patients->lastItem() }}
                        </strong>

                        of

                        <strong>
                            {{ $patients->total() }}
                        </strong>

                        patients

                    </small>


                    <div>

                        {{ $patients->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

        {{-- =========================================================
        DOCTOR ANALYSIS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-user-md me-2 text-primary"></i>

                Doctor Analysis

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Total Doctors --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Total Doctors
                        </small>

                        <h4 class="fw-bold text-primary mb-0 mt-1">

                            {{ number_format(
                                $summary['total_doctors']
                            ) }}

                        </h4>

                    </div>

                </div>


                {{-- Total Patients --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Total Patients
                        </small>

                        <h4 class="fw-bold text-info mb-0 mt-1">

                            {{ number_format(
                                $summary['total_patients']
                            ) }}

                        </h4>

                    </div>

                </div>


                {{-- Active Patients --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Active Patients
                        </small>

                        <h4 class="fw-bold text-success mb-0 mt-1">

                            {{ number_format(
                                $summary['active_patients']
                            ) }}

                        </h4>

                    </div>

                </div>


                {{-- Inactive Patients --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Inactive Patients
                        </small>

                        <h4 class="fw-bold text-secondary mb-0 mt-1">

                            {{ number_format(
                                $summary['inactive_patients']
                            ) }}

                        </h4>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ACTIVE FILTER NOTICE
    ========================================================== --}}

    @if(
        request()->filled('search') ||
        request()->filled('doctor') ||
        request()->filled('status') ||
        request()->filled('gender') ||
        request()->filled('scanning_for')
    )

        <div class="alert alert-light border shadow-sm mb-4">

            <div class="d-flex align-items-start">

                <i class="fas fa-filter text-primary me-2 mt-1"></i>

                <div>

                    <strong>
                        Filters Applied
                    </strong>

                    <span class="text-muted ms-1">

                        The patient list is currently filtered
                        according to your selected criteria.

                    </span>

                    <a
                        href="{{ route(
                            'admin.reports.doctor-wise-patients.index'
                        ) }}"
                        class="ms-2"
                    >

                        Clear Filters

                    </a>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        REPORT FOOTER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <small class="text-muted">

            Doctor-wise Patients Report

            &bull;

            {{ now()->format('d M Y h:i A') }}

        </small>


        <a
            href="{{ route('admin.reports.index') }}"
            class="btn btn-light border btn-sm"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Reports Dashboard

        </a>

    </div>

</div>

@endsection