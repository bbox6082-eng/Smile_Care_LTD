@extends('layouts.dashboard')

@section('title', 'Patient by Doctor Report')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fas fa-user-doctor me-2 text-primary"></i>

                Patient by Doctor Report

            </h3>

            <p class="text-muted mb-0">

                View and analyze patient records by doctor.

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
                    'admin.reports.patient-by-doctor.export.excel',
                    request()->query()
                ) }}"
                class="btn btn-success"
            >

                <i class="fas fa-file-excel me-1"></i>

                Excel

            </a>


            <a
                href="{{ route(
                    'admin.reports.patient-by-doctor.export.pdf',
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


        {{-- Total Doctors --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Total Doctors
                            </p>

                            <h3 class="fw-bold mb-0">

                                {{ number_format($summary['total_doctors']) }}

                            </h3>

                        </div>

                        <div class="text-success fs-2">

                            <i class="fas fa-user-doctor"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Male Patients --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Male Patients
                            </p>

                            <h3 class="fw-bold mb-0">

                                {{ number_format($summary['male_patients']) }}

                            </h3>

                        </div>

                        <div class="text-info fs-2">

                            <i class="fas fa-mars"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Female Patients --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Female Patients
                            </p>

                            <h3 class="fw-bold mb-0">

                                {{ number_format($summary['female_patients']) }}

                            </h3>

                        </div>

                        <div class="text-danger fs-2">

                            <i class="fas fa-venus"></i>

                        </div>

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

                Filter Patients by Doctor

            </h5>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reports.patient-by-doctor.index') }}"
            >

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
                            value="{{ request('search') }}"
                            placeholder="Patient name, Predict3D ID, phone, doctor..."
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

                            @foreach($filters['doctors'] as $doctor)

                                <option
                                    value="{{ $doctor }}"
                                    @selected(request('doctor') === $doctor)
                                >

                                    {{ $doctor }}

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
                                All
                            </option>

                            @foreach($filters['genders'] as $gender)

                                <option
                                    value="{{ $gender }}"
                                    @selected(request('gender') === $gender)
                                >

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
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            @foreach($filters['scanning_types'] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(request('scanning_for') === $type)
                                >

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Region --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Region
                        </label>

                        <select
                            name="region"
                            class="form-select"
                        >

                            <option value="">
                                All Regions
                            </option>

                            @foreach($filters['regions'] as $region)

                                <option
                                    value="{{ $region }}"
                                    @selected(request('region') === $region)
                                >

                                    {{ $region }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Territory --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Territory
                        </label>

                        <select
                            name="territory"
                            class="form-select"
                        >

                            <option value="">
                                All Territories
                            </option>

                            @foreach($filters['territories'] as $territory)

                                <option
                                    value="{{ $territory }}"
                                    @selected(request('territory') === $territory)
                                >

                                    {{ $territory }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Date From --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Registration From
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            class="form-control"
                            value="{{ request('date_from') }}"
                        >

                    </div>


                    {{-- Date To --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Registration To
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="{{ request('date_to') }}"
                        >

                    </div>


                    {{-- Filter Buttons --}}

                    <div class="col-lg-6 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="fas fa-search me-1"></i>

                            Apply Filters

                        </button>


                        <a
                            href="{{ route('admin.reports.patient-by-doctor.index') }}"
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

                    <i class="fas fa-user-doctor me-2 text-primary"></i>

                    Patient Records by Doctor

                </h5>


                <span class="badge bg-primary">

                    {{ number_format($patients->total()) }}

                    Records

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
                                Doctor
                            </th>

                            <th>
                                Patient
                            </th>

                            <th>
                                Predict3D ID
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Gender
                            </th>

                            <th>
                                Scanning For
                            </th>

                            <th>
                                Chamber
                            </th>

                            <th>
                                Region
                            </th>

                            <th>
                                Territory
                            </th>

                            <th>
                                Registered
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($patients as $patient)

                            <tr>

                                <td class="text-center">

                                    {{ $patients->firstItem() + $loop->index }}

                                </td>


                                <td>

                                    <span class="fw-semibold">

                                        {{ $patient->DoctorName ?: '-' }}

                                    </span>

                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        {{ $patient->FullName ?: '-' }}

                                    </div>

                                </td>


                                <td>

                                    {{ $patient->Predict3DId ?: '-' }}

                                </td>


                                <td>

                                    {{ $patient->PhoneNumber ?: '-' }}

                                </td>


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

                                        -

                                    @endif

                                </td>


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

                                        {{ $patient->ScanningFor ?: '-' }}

                                    @endif

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


                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'admin.reports.patient-by-doctor.show',
                                            $patient->Predict3DId
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View Patient"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="12"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="fas fa-user-doctor fa-2x mb-3"></i>

                                        <p class="mb-0 fw-semibold">

                                            No patient records found.

                                        </p>

                                        <small>

                                            Try changing your search or filter criteria.

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
        APPLIED FILTER SUMMARY
    ========================================================== --}}

    @if(
        request()->filled('search') ||
        request()->filled('doctor') ||
        request()->filled('gender') ||
        request()->filled('scanning_for') ||
        request()->filled('region') ||
        request()->filled('territory') ||
        request()->filled('date_from') ||
        request()->filled('date_to')
    )

        <div class="alert alert-light border shadow-sm mb-4">

            <div class="d-flex align-items-start">

                <i class="fas fa-filter text-primary me-2 mt-1"></i>

                <div>

                    <strong>
                        Applied Filters:
                    </strong>

                    <span class="text-muted">
                        The report is currently filtered based on your selected criteria.
                    </span>

                    <a
                        href="{{ route('admin.reports.patient-by-doctor.index') }}"
                        class="ms-2"
                    >
                        Clear Filters
                    </a>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        REPORT INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4 text-center">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Total Patients
                    </small>

                    <h4 class="fw-bold text-primary mb-0 mt-1">

                        {{ number_format($summary['total_patients']) }}

                    </h4>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Total Doctors
                    </small>

                    <h4 class="fw-bold text-success mb-0 mt-1">

                        {{ number_format($summary['total_doctors']) }}

                    </h4>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Aligner Patients
                    </small>

                    <h4 class="fw-bold text-info mb-0 mt-1">

                        {{ number_format($summary['aligner_patients']) }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PAGE FOOTER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <small class="text-muted">

            Patient by Doctor Report

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