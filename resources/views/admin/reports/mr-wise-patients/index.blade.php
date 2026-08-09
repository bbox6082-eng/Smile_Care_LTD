@extends('layouts.dashboard')

@section('title', 'MR-wise Patients Report')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fas fa-user-tie me-2 text-info"></i>

                MR-wise Patients

            </h3>

            <p class="text-muted mb-0">

                Patients grouped by Marketing Representative.

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
                    'admin.reports.mr-wise-patients.export.excel',
                    request()->query()
                ) }}"
                class="btn btn-success"
            >

                <i class="fas fa-file-excel me-1"></i>

                Excel

            </a>


            <a
                href="{{ route(
                    'admin.reports.mr-wise-patients.export.pdf',
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


        {{-- Total MRs --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Marketing Representatives
                            </p>

                            <h3 class="fw-bold text-info mb-0">

                                {{ number_format(
                                    $summary['total_mrs']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-info fs-2">

                            <i class="fas fa-user-tie"></i>

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

                            <h3 class="fw-bold text-primary mb-0">

                                {{ number_format(
                                    $summary['total_patients']
                                ) }}

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


        {{-- Unassigned Patients --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Unassigned Patients
                            </p>

                            <h3 class="fw-bold text-warning mb-0">

                                {{ number_format(
                                    $summary['unassigned_patients']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-warning fs-2">

                            <i class="fas fa-user-slash"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        REPORT OVERVIEW
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-pie me-2 text-info"></i>

                Patient Overview

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Total --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Total Patients
                        </small>

                        <h4 class="fw-bold text-primary mb-1 mt-1">

                            {{ number_format(
                                $summary['total_patients']
                            ) }}

                        </h4>

                        <small class="text-muted">
                            Matching current filters
                        </small>

                    </div>

                </div>


                {{-- Active --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Active
                        </small>

                        <h4 class="fw-bold text-success mb-1 mt-1">

                            {{ number_format(
                                $summary['active_patients']
                            ) }}

                        </h4>

                        <small class="text-muted">
                            Currently active patients
                        </small>

                    </div>

                </div>


                {{-- Inactive --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Inactive
                        </small>

                        <h4 class="fw-bold text-secondary mb-1 mt-1">

                            {{ number_format(
                                $summary['inactive_patients']
                            ) }}

                        </h4>

                        <small class="text-muted">
                            Currently inactive patients
                        </small>

                    </div>

                </div>


                {{-- Unassigned --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Unassigned
                        </small>

                        <h4 class="fw-bold text-warning mb-1 mt-1">

                            {{ number_format(
                                $summary['unassigned_patients']
                            ) }}

                        </h4>

                        <small class="text-muted">
                            Without an assigned MR
                        </small>

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

                <i class="fas fa-filter me-2 text-info"></i>

                Filter Patients

            </h5>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.reports.mr-wise-patients.index'
                ) }}"
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


                    {{-- Marketing Representative --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Marketing Representative
                        </label>

                        <select
                            name="mr"
                            class="form-select"
                        >

                            <option value="">
                                All MRs
                            </option>

                            @foreach($marketingRepresentatives as $mr)

                                <option
                                    value="{{ $mr }}"
                                    @selected(
                                        request('mr') === $mr
                                    )
                                >

                                    {{ $mr }}

                                </option>

                            @endforeach

                            <option
                                value="Unassigned"
                                @selected(
                                    request('mr') === 'Unassigned'
                                )
                            >
                                Unassigned
                            </option>

                        </select>

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
                                    @selected(
                                        request('doctor') === $doctor
                                    )
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

                            @foreach($statuses as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        request('status') === $status
                                    )
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

                            @foreach($genders as $gender)

                                <option
                                    value="{{ $gender }}"
                                    @selected(
                                        request('gender') === $gender
                                    )
                                >

                                    {{ $gender }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Scanning Type --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Scanning For
                        </label>

                        <select
                            name="scanning_for"
                            class="form-select"
                        >

                            <option value="">
                                All Types
                            </option>

                            @foreach($scanningTypes as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        request('scanning_for') === $type
                                    )
                                >

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- From Date --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from"
                            class="form-control"
                            value="{{ request('from') }}"
                        >

                    </div>


                    {{-- To Date --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to"
                            class="form-control"
                            value="{{ request('to') }}"
                        >

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
                                'admin.reports.mr-wise-patients.index'
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
        MR-WISE PATIENT SUMMARY TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">

                    <i class="fas fa-users me-2 text-info"></i>

                    MR-wise Patient Summary

                </h5>


                <span class="badge bg-info">

                    {{ number_format(
                        $mrPatients->total()
                    ) }}

                    MRs

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
                                Marketing Representative
                            </th>

                            <th class="text-center">
                                Patients
                            </th>

                            <th class="text-center">
                                Active
                            </th>

                            <th class="text-center">
                                Inactive
                            </th>

                            <th class="text-center">
                                Doctors
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($mrPatients as $row)

                            <tr>

                                {{-- Serial --}}

                                <td class="text-center">

                                    {{ $mrPatients->firstItem() + $loop->index }}

                                </td>


                                {{-- MR Name --}}

                                <td>

                                    <div class="d-flex align-items-center">

                                        <div
                                            class="rounded-circle bg-info bg-opacity-10
                                                   text-info d-flex align-items-center
                                                   justify-content-center me-2"
                                            style="width:38px;height:38px;"
                                        >

                                            <i class="fas fa-user-tie"></i>

                                        </div>

                                        <div>

                                            <div class="fw-semibold">

                                                {{ $row['mr'] }}

                                            </div>

                                            <small class="text-muted">

                                                Marketing Representative

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- Patients --}}

                                <td class="text-center">

                                    <span class="badge bg-primary">

                                        {{ number_format(
                                            $row['patients']
                                        ) }}

                                    </span>

                                </td>


                                {{-- Active --}}

                                <td class="text-center">

                                    <span class="badge bg-success">

                                        {{ number_format(
                                            $row['active']
                                        ) }}

                                    </span>

                                </td>


                                {{-- Inactive --}}

                                <td class="text-center">

                                    <span class="badge bg-secondary">

                                        {{ number_format(
                                            $row['inactive']
                                        ) }}

                                    </span>

                                </td>


                                {{-- Doctors --}}

                                <td class="text-center">

                                    <span class="badge bg-light text-dark border">

                                        {{ number_format(
                                            $row['doctors']
                                        ) }}

                                    </span>

                                </td>


                                {{-- Action --}}

                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'admin.reports.mr-wise-patients.show',
                                            [
                                                'mr' => $row['mr'],
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View MR Details"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="fas fa-users-slash fa-2x mb-3"></i>

                                        <p class="mb-0 fw-semibold">

                                            No MR-wise patient records found.

                                        </p>

                                        <small>

                                            Try changing your filters
                                            or date range.

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

        @if($mrPatients->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <small class="text-muted mb-2 mb-md-0">

                        Showing

                        <strong>
                            {{ $mrPatients->firstItem() }}
                        </strong>

                        to

                        <strong>
                            {{ $mrPatients->lastItem() }}
                        </strong>

                        of

                        <strong>
                            {{ $mrPatients->total() }}
                        </strong>

                        marketing representatives

                    </small>


                    <div>

                        {{ $mrPatients->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

        {{-- =========================================================
        REPORT INSIGHTS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-bar me-2 text-info"></i>

                Report Insights

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Total MRs --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Marketing Representatives
                        </small>

                        <h4 class="fw-bold text-info mb-1 mt-1">

                            {{ number_format(
                                $summary['total_mrs']
                            ) }}

                        </h4>

                        <small class="text-muted">
                            Representatives with assigned patients
                        </small>

                    </div>

                </div>


                {{-- Total Patients --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Total Patients
                        </small>

                        <h4 class="fw-bold text-primary mb-1 mt-1">

                            {{ number_format(
                                $summary['total_patients']
                            ) }}

                        </h4>

                        <small class="text-muted">
                            Patients matching the current filters
                        </small>

                    </div>

                </div>


                {{-- Active Percentage --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Active Patient Rate
                        </small>

                        <h4 class="fw-bold text-success mb-1 mt-1">

                            {{ $summary['total_patients'] > 0
                                ? number_format(
                                    (
                                        $summary['active_patients'] /
                                        $summary['total_patients']
                                    ) * 100,
                                    1
                                )
                                : '0.0'
                            }}%

                        </h4>

                        <small class="text-muted">
                            Active patients / total patients
                        </small>

                    </div>

                </div>


                {{-- Unassigned Percentage --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Unassigned Rate
                        </small>

                        <h4 class="fw-bold text-warning mb-1 mt-1">

                            {{ $summary['total_patients'] > 0
                                ? number_format(
                                    (
                                        $summary['unassigned_patients'] /
                                        $summary['total_patients']
                                    ) * 100,
                                    1
                                )
                                : '0.0'
                            }}%

                        </h4>

                        <small class="text-muted">
                            Patients without an assigned MR
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        APPLIED FILTERS
    ========================================================== --}}

    @if(
        request()->filled('search') ||
        request()->filled('mr') ||
        request()->filled('doctor') ||
        request()->filled('status') ||
        request()->filled('gender') ||
        request()->filled('scanning_for') ||
        request()->filled('from') ||
        request()->filled('to')
    )

        <div class="alert alert-light border shadow-sm mb-4">

            <div class="d-flex align-items-start">

                <i class="fas fa-filter text-info me-2 mt-1"></i>

                <div>

                    <strong>
                        Filters Applied
                    </strong>

                    <span class="text-muted ms-1">

                        The patient report is currently filtered
                        according to your selected criteria.

                    </span>

                    <a
                        href="{{ route(
                            'admin.reports.mr-wise-patients.index'
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

            MR-wise Patients Report

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