@extends('layouts.dashboard')
@section('title', 'Patient Report')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h2 class="fw-bold mb-1">

                        <i class="fas fa-users me-2 text-primary"></i>

                        Patient Report

                    </h2>

                    <p class="text-muted mb-0">

                        Monitor patient records, demographics, treatment types,
                        doctors, territories and patient status.

                    </p>

                </div>


                {{-- =================================================
                    HEADER ACTIONS
                ================================================== --}}

                <div class="mt-3 mt-md-0 d-flex gap-2 flex-wrap">

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


                    <a href="{{ route('admin.reports.patient.export.excel', request()->query()) }}"
                       class="btn btn-success shadow-sm">

                        <i class="fas fa-file-excel me-1"></i>

                        Excel

                    </a>


                    <a href="{{ route('admin.reports.patient.export.pdf', request()->query()) }}"
                       class="btn btn-danger shadow-sm">

                        <i class="fas fa-file-pdf me-1"></i>

                        PDF

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

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">

                                Total Patients

                            </small>

                            <h3 class="fw-bold mt-2 mb-0">

                                {{ number_format($summary['total_patients']) }}

                            </h3>

                        </div>

                        <div class="text-primary fs-2">

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

                            <small class="text-muted">

                                Active Patients

                            </small>

                            <h3 class="fw-bold text-success mt-2 mb-0">

                                {{ number_format($summary['active_patients']) }}

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

                            <small class="text-muted">

                                Inactive Patients

                            </small>

                            <h3 class="fw-bold text-warning mt-2 mb-0">

                                {{ number_format($summary['inactive_patients']) }}

                            </h3>

                        </div>

                        <div class="text-warning fs-2">

                            <i class="fas fa-user-clock"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Aligner Patients --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">

                                Aligner Patients

                            </small>

                            <h3 class="fw-bold text-info mt-2 mb-0">

                                {{ number_format($summary['aligner_patients']) }}

                            </h3>

                        </div>

                        <div class="text-info fs-2">

                            <i class="fas fa-tooth"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTER PANEL
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-filter me-2 text-primary"></i>

                Filter Patient Report

            </h5>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.reports.patient.index') }}">


                {{-- =================================================
                    ROW 1
                ================================================== --}}

                <div class="row g-3">


                    {{-- Search --}}

                    <div class="col-lg-4 col-md-6">

                        <label class="form-label fw-semibold">

                            Search

                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Patient / Predict3D ID / Doctor / Phone"
                            value="{{ request('search') }}">

                    </div>


                    {{-- Status --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">

                            Status

                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">

                                All Status

                            </option>

                            @foreach($filters['statuses'] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(request('status') === $status)>

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
                            class="form-select">

                            <option value="">

                                All Gender

                            </option>

                            @foreach($filters['genders'] as $gender)

                                <option
                                    value="{{ $gender }}"
                                    @selected(request('gender') === $gender)>

                                    {{ $gender }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Scanning For --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">

                            Scanning For

                        </label>

                        <select
                            name="scanning_for"
                            class="form-select">

                            <option value="">

                                All Types

                            </option>

                            @foreach($filters['scanning_types'] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(request('scanning_for') === $type)>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Doctor --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">

                            Doctor

                        </label>

                        <select
                            name="doctor"
                            class="form-select">

                            <option value="">

                                All Doctors

                            </option>

                            @foreach($filters['doctors'] as $doctor)

                                <option
                                    value="{{ $doctor }}"
                                    @selected(request('doctor') === $doctor)>

                                    {{ $doctor }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- =================================================
                    ROW 2
                ================================================== --}}

                <div class="row g-3 mt-1">


                    {{-- Region --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">

                            Region

                        </label>

                        <select
                            name="region"
                            class="form-select">

                            <option value="">

                                All Regions

                            </option>

                            @foreach($filters['regions'] as $region)

                                <option
                                    value="{{ $region }}"
                                    @selected(request('region') === $region)>

                                    {{ $region }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Territory --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">

                            Territory

                        </label>

                        <select
                            name="territory"
                            class="form-select">

                            <option value="">

                                All Territories

                            </option>

                            @foreach($filters['territories'] as $territory)

                                <option
                                    value="{{ $territory }}"
                                    @selected(request('territory') === $territory)>

                                    {{ $territory }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Filter Buttons --}}

                    <div class="col-lg-6 col-md-12 d-flex align-items-end justify-content-lg-end">

                        <button
                            type="submit"
                            class="btn btn-primary me-2">

                            <i class="fas fa-search me-1"></i>

                            Apply Filters

                        </button>


                        <a href="{{ route('admin.reports.patient.index') }}"
                           class="btn btn-secondary">

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

        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">

            <div>

                <h5 class="mb-1 fw-semibold">

                    <i class="fas fa-user-injured me-2 text-primary"></i>

                    Patient Records

                </h5>

                <small class="text-muted">

                    Showing filtered patient records

                </small>

            </div>


            <span class="badge bg-primary">

                {{ number_format($patients->total()) }}

                {{ $patients->total() == 1 ? 'Patient' : 'Patients' }}

            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60" class="text-center">
                                SL
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
                                Gender
                            </th>

                            <th>
                                Doctor
                            </th>

                            <th>
                                Scanning For
                            </th>

                            <th>
                                Region
                            </th>

                            <th>
                                Territory
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="80" class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($patients as $patient)

                            <tr>

                                {{-- =================================
                                    SL
                                ================================== --}}

                                <td class="text-center">

                                    {{ $patients->firstItem() + $loop->index }}

                                </td>


                                {{-- =================================
                                    Predict3D ID
                                ================================== --}}

                                <td>

                                    <span class="fw-semibold">

                                        {{ $patient->Predict3DId }}

                                    </span>

                                </td>


                                {{-- =================================
                                    Patient
                                ================================== --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $patient->FullName }}

                                    </div>

                                    @if($patient->DateOfBirth)

                                        <small class="text-muted">

                                            DOB:
                                            {{ $patient->DateOfBirth->format('d M Y') }}

                                        </small>

                                    @endif

                                </td>


                                {{-- =================================
                                    Phone
                                ================================== --}}

                                <td>

                                    {{ $patient->PhoneNumber ?: '-' }}

                                </td>


                                {{-- =================================
                                    Gender
                                ================================== --}}

                                <td>

                                    @if($patient->Gender === 'Male')

                                        <span class="badge bg-primary">

                                            Male

                                        </span>

                                    @elseif($patient->Gender === 'Female')

                                        <span class="badge bg-danger">

                                            Female

                                        </span>

                                    @elseif($patient->Gender === 'Custom')

                                        <span class="badge bg-secondary">

                                            Custom

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            -

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================
                                    Doctor
                                ================================== --}}

                                <td>

                                    {{ $patient->DoctorName ?: '-' }}

                                </td>


                                {{-- =================================
                                    Scanning For
                                ================================== --}}

                                <td>

                                    @if($patient->ScanningFor === 'Aligner')

                                        <span class="badge bg-info text-dark">

                                            Aligner

                                        </span>

                                    @elseif($patient->ScanningFor === 'Zirconia')

                                        <span class="badge bg-warning text-dark">

                                            Zirconia

                                        </span>

                                    @elseif($patient->ScanningFor === 'Others')

                                        <span class="badge bg-secondary">

                                            Others

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            -

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================
                                    Region
                                ================================== --}}

                                <td>

                                    {{ $patient->RegionalName ?: '-' }}

                                </td>


                                {{-- =================================
                                    Territory
                                ================================== --}}

                                <td>

                                    {{ $patient->TerritoryName ?: '-' }}

                                </td>


                                {{-- =================================
                                    Status
                                ================================== --}}

                                <td>

                                    @if($patient->status === 'active')

                                        <span class="badge bg-success">

                                            Active

                                        </span>

                                    @elseif($patient->status === 'inactive')

                                        <span class="badge bg-warning text-dark">

                                            Inactive

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ ucfirst($patient->status ?? 'Unknown') }}

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================
                                    Action
                                ================================== --}}

                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'admin.reports.patient.show',
                                            $patient->Predict3DId
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View Patient Details">

                                        <i class="fas fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="11"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fas fa-user-slash fs-2 mb-3"></i>

                                        <div class="fw-semibold">

                                            No patient records found.

                                        </div>

                                        <small>

                                            Try adjusting your filters
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

                    <small class="text-muted">

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


                    <div class="mt-2 mt-md-0">

                        {{ $patients->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

        {{-- =========================================================
        PATIENT STATISTICS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- =====================================================
            GENDER DISTRIBUTION
        ====================================================== --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-venus-mars me-2 text-primary"></i>

                        Gender Distribution

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row text-center">

                        <div class="col-4">

                            <div class="text-primary fs-3">

                                <i class="fas fa-mars"></i>

                            </div>

                            <h4 class="fw-bold mb-1">

                                {{ number_format($summary['male_patients']) }}

                            </h4>

                            <small class="text-muted">

                                Male

                            </small>

                        </div>


                        <div class="col-4">

                            <div class="text-danger fs-3">

                                <i class="fas fa-venus"></i>

                            </div>

                            <h4 class="fw-bold mb-1">

                                {{ number_format($summary['female_patients']) }}

                            </h4>

                            <small class="text-muted">

                                Female

                            </small>

                        </div>


                        <div class="col-4">

                            <div class="text-secondary fs-3">

                                <i class="fas fa-user"></i>

                            </div>

                            <h4 class="fw-bold mb-1">

                                {{ number_format($summary['custom_gender_patients']) }}

                            </h4>

                            <small class="text-muted">

                                Custom

                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            SCANNING TYPE DISTRIBUTION
        ====================================================== --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-tooth me-2 text-info"></i>

                        Treatment / Scanning Distribution

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row text-center">

                        <div class="col-4">

                            <div class="text-info fs-3">

                                <i class="fas fa-tooth"></i>

                            </div>

                            <h4 class="fw-bold mb-1">

                                {{ number_format($summary['aligner_patients']) }}

                            </h4>

                            <small class="text-muted">

                                Aligner

                            </small>

                        </div>


                        <div class="col-4">

                            <div class="text-warning fs-3">

                                <i class="fas fa-teeth"></i>

                            </div>

                            <h4 class="fw-bold mb-1">

                                {{ number_format($summary['zirconia_patients']) }}

                            </h4>

                            <small class="text-muted">

                                Zirconia

                            </small>

                        </div>


                        <div class="col-4">

                            <div class="text-secondary fs-3">

                                <i class="fas fa-ellipsis"></i>

                            </div>

                            <h4 class="fw-bold mb-1">

                                {{ number_format($summary['others_patients']) }}

                            </h4>

                            <small class="text-muted">

                                Others

                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        REPORT SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-pie me-2 text-primary"></i>

                Report Summary

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Total --}}

                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted">

                            Total Patients

                        </small>

                        <h4 class="fw-bold mt-2 mb-0">

                            {{ number_format($summary['total_patients']) }}

                        </h4>

                    </div>

                </div>


                {{-- Active --}}

                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted">

                            Active

                        </small>

                        <h4 class="fw-bold text-success mt-2 mb-0">

                            {{ number_format($summary['active_patients']) }}

                        </h4>

                    </div>

                </div>


                {{-- Inactive --}}

                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted">

                            Inactive

                        </small>

                        <h4 class="fw-bold text-warning mt-2 mb-0">

                            {{ number_format($summary['inactive_patients']) }}

                        </h4>

                    </div>

                </div>


                {{-- Aligner --}}

                <div class="col-md-3">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted">

                            Aligner

                        </small>

                        <h4 class="fw-bold text-info mt-2 mb-0">

                            {{ number_format($summary['aligner_patients']) }}

                        </h4>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ACTIVE FILTER SUMMARY
    ========================================================== --}}

    @if(
        request()->filled('search') ||
        request()->filled('status') ||
        request()->filled('gender') ||
        request()->filled('scanning_for') ||
        request()->filled('doctor') ||
        request()->filled('region') ||
        request()->filled('territory')
    )

        <div class="alert alert-light border shadow-sm mb-4">

            <div class="d-flex align-items-start">

                <i class="fas fa-filter text-primary me-2 mt-1"></i>

                <div>

                    <strong>

                        Active Filters:

                    </strong>

                    <span class="text-muted">

                        The report is currently filtered based on your selected criteria.

                    </span>

                    <a
                        href="{{ route('admin.reports.patient.index') }}"
                        class="ms-2">

                        Clear Filters

                    </a>

                </div>

            </div>

        </div>

    @endif


</div>

@endsection