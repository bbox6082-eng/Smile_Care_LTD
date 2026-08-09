@extends('layouts.dashboard')

@section('title', 'MR Performance Report')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fas fa-chart-line me-2 text-info"></i>

                MR Performance

            </h3>

            <p class="text-muted mb-0">

                Marketing Representative performance based on
                patient activity and revenue collection.

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
                    'admin.reports.mr-performance.export.excel',
                    request()->query()
                ) }}"
                class="btn btn-success"
            >

                <i class="fas fa-file-excel me-1"></i>

                Excel

            </a>


            <a
                href="{{ route(
                    'admin.reports.mr-performance.export.pdf',
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


        {{-- Collected Revenue --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Collected Revenue
                            </p>

                            <h3 class="fw-bold text-success mb-0">

                                {{ number_format(
                                    $summary['collected_revenue'],
                                    2
                                ) }}

                            </h3>

                        </div>

                        <div class="text-success fs-2">

                            <i class="fas fa-money-bill-wave"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Outstanding Due --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Outstanding Due
                            </p>

                            <h3 class="fw-bold text-warning mb-0">

                                {{ number_format(
                                    $summary['outstanding_due'],
                                    2
                                ) }}

                            </h3>

                        </div>

                        <div class="text-warning fs-2">

                            <i class="fas fa-hourglass-half"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PERFORMANCE OVERVIEW
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-pie me-2 text-info"></i>

                Performance Overview

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Active Patients --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Active Patients
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


                {{-- Doctors Covered --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Doctors Covered
                        </small>

                        <h4 class="fw-bold text-info mb-1 mt-1">

                            {{ number_format(
                                $summary['total_doctors']
                            ) }}

                        </h4>

                        <small class="text-muted">

                            Unique doctors represented

                        </small>

                    </div>

                </div>


                {{-- Collection Rate --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Collection Rate
                        </small>

                        <h4 class="fw-bold text-primary mb-1 mt-1">

                            {{ number_format(
                                $summary['collection_rate'],
                                1
                            ) }}%

                        </h4>

                        <small class="text-muted">

                            Collected / planned revenue

                        </small>

                    </div>

                </div>


                {{-- Average Revenue / Patient --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Avg. Revenue / Patient
                        </small>

                        <h4 class="fw-bold text-success mb-1 mt-1">

                            {{ number_format(
                                $summary[
                                    'average_revenue_per_patient'
                                ],
                                2
                            ) }}

                        </h4>

                        <small class="text-muted">

                            Average collected amount

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

                Filter Performance

            </h5>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route(
                    'admin.reports.mr-performance.index'
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
                            placeholder="MR, patient, ID, doctor..."
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
                            Patient Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Statuses
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
                                'admin.reports.mr-performance.index'
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
        PERFORMANCE RANKING
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-ranking-star me-2 text-info"></i>

                        MR Performance Ranking

                    </h5>

                    <small class="text-muted">

                        Ranked by collected revenue

                    </small>

                </div>


                <span class="badge bg-info">

                    {{ number_format(
                        $performance->total()
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
                                Rank
                            </th>

                            <th>
                                Marketing Representative
                            </th>

                            <th class="text-center">
                                Patients
                            </th>

                            <th class="text-center">
                                Doctors
                            </th>

                            <th class="text-center">
                                Plans
                            </th>

                            <th class="text-end">
                                Planned Revenue
                            </th>

                            <th class="text-end">
                                Collected Revenue
                            </th>

                            <th class="text-end">
                                Due
                            </th>

                            <th class="text-center">
                                Collection Rate
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($performance as $row)

                            <tr>


                                {{-- Rank --}}

                                <td class="text-center">

                                    @if(
                                        $performance->firstItem() +
                                        $loop->index === 1
                                    )

                                        <span
                                            class="badge bg-warning text-dark"
                                            title="Top Performer"
                                        >

                                            <i class="fas fa-trophy"></i>

                                            1

                                        </span>

                                    @elseif(
                                        $performance->firstItem() +
                                        $loop->index === 2
                                    )

                                        <span class="badge bg-secondary">

                                            2

                                        </span>

                                    @elseif(
                                        $performance->firstItem() +
                                        $loop->index === 3
                                    )

                                        <span class="badge bg-dark">

                                            3

                                        </span>

                                    @else

                                        <span class="fw-semibold">

                                            {{
                                                $performance->firstItem()
                                                +
                                                $loop->index
                                            }}

                                        </span>

                                    @endif

                                </td>


                                {{-- MR --}}

                                <td>

                                    <div class="d-flex align-items-center">

                                        <div
                                            class="rounded-circle
                                                   bg-info
                                                   bg-opacity-10
                                                   text-info
                                                   d-flex
                                                   align-items-center
                                                   justify-content-center
                                                   me-2"
                                            style="width:40px;height:40px;"
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


                                {{-- Doctors --}}

                                <td class="text-center">

                                    {{ number_format(
                                        $row['doctors']
                                    ) }}

                                </td>


                                {{-- Plans --}}

                                <td class="text-center">

                                    <span class="badge bg-light text-dark border">

                                        {{ number_format(
                                            $row['payment_plans']
                                        ) }}

                                    </span>

                                </td>


                                {{-- Planned Revenue --}}

                                <td class="text-end">

                                    {{ number_format(
                                        $row['planned_revenue'],
                                        2
                                    ) }}

                                </td>


                                {{-- Collected Revenue --}}

                                <td class="text-end">

                                    <span class="fw-bold text-success">

                                        {{ number_format(
                                            $row['collected_revenue'],
                                            2
                                        ) }}

                                    </span>

                                </td>


                                {{-- Due --}}

                                <td class="text-end">

                                    @if(
                                        $row['outstanding_due'] > 0
                                    )

                                        <span class="fw-semibold text-warning">

                                            {{ number_format(
                                                $row['outstanding_due'],
                                                2
                                            ) }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            0.00
                                        </span>

                                    @endif

                                </td>


                                {{-- Collection Rate --}}

                                <td class="text-center">

                                    @php

                                        $rate =
                                            (float)
                                            $row['collection_rate'];

                                    @endphp


                                    @if($rate >= 90)

                                        <span class="badge bg-success">

                                            {{ number_format(
                                                $rate,
                                                1
                                            ) }}%

                                        </span>

                                    @elseif($rate >= 70)

                                        <span class="badge bg-info">

                                            {{ number_format(
                                                $rate,
                                                1
                                            ) }}%

                                        </span>

                                    @elseif($rate >= 50)

                                        <span class="badge bg-warning text-dark">

                                            {{ number_format(
                                                $rate,
                                                1
                                            ) }}%

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            {{ number_format(
                                                $rate,
                                                1
                                            ) }}%

                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}

                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'admin.reports.mr-performance.show',
                                            [
                                                'mr' => $row['mr'],
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View Performance"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i
                                            class="fas fa-chart-line fa-2x mb-3"
                                        ></i>

                                        <p class="mb-0 fw-semibold">

                                            No performance records found.

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

        @if($performance->hasPages())

            <div class="card-footer bg-white">

                <div
                    class="d-flex
                           justify-content-between
                           align-items-center
                           flex-wrap"
                >

                    <small class="text-muted mb-2 mb-md-0">

                        Showing

                        <strong>
                            {{ $performance->firstItem() }}
                        </strong>

                        to

                        <strong>
                            {{ $performance->lastItem() }}
                        </strong>

                        of

                        <strong>
                            {{ $performance->total() }}
                        </strong>

                        marketing representatives

                    </small>


                    <div>

                        {{ $performance->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

        {{-- =========================================================
        PERFORMANCE INSIGHTS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-lightbulb me-2 text-warning"></i>

                Performance Insights

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Planned Revenue --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Planned Revenue
                        </small>

                        <h4 class="fw-bold text-primary mb-1 mt-1">

                            {{ number_format(
                                $summary['planned_revenue'],
                                2
                            ) }}

                        </h4>

                        <small class="text-muted">

                            Total payment-plan value

                        </small>

                    </div>

                </div>


                {{-- Paid Plans --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Paid Plans
                        </small>

                        <h4 class="fw-bold text-success mb-1 mt-1">

                            {{ number_format(
                                $summary['paid_plans']
                            ) }}

                            <span class="fs-6 text-muted">

                                /
                                {{ number_format(
                                    $summary['payment_plans']
                                ) }}

                            </span>

                        </h4>

                        <small class="text-muted">

                            Fully settled payment plans

                        </small>

                    </div>

                </div>


                {{-- Collection Rate --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Collection Rate
                        </small>

                        <h4 class="fw-bold mb-1 mt-1">

                            @if(
                                $summary['collection_rate'] >= 90
                            )

                                <span class="text-success">

                                    {{ number_format(
                                        $summary['collection_rate'],
                                        1
                                    ) }}%

                                </span>

                            @elseif(
                                $summary['collection_rate'] >= 70
                            )

                                <span class="text-info">

                                    {{ number_format(
                                        $summary['collection_rate'],
                                        1
                                    ) }}%

                                </span>

                            @elseif(
                                $summary['collection_rate'] >= 50
                            )

                                <span class="text-warning">

                                    {{ number_format(
                                        $summary['collection_rate'],
                                        1
                                    ) }}%

                                </span>

                            @else

                                <span class="text-danger">

                                    {{ number_format(
                                        $summary['collection_rate'],
                                        1
                                    ) }}%

                                </span>

                            @endif

                        </h4>

                        <small class="text-muted">

                            Revenue successfully collected

                        </small>

                    </div>

                </div>


                {{-- Average Revenue --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Avg. Revenue / Patient
                        </small>

                        <h4 class="fw-bold text-success mb-1 mt-1">

                            {{ number_format(
                                $summary[
                                    'average_revenue_per_patient'
                                ],
                                2
                            ) }}

                        </h4>

                        <small class="text-muted">

                            Average collected revenue

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

                        Performance metrics are calculated
                        using the selected filters.

                    </span>

                    <a
                        href="{{ route(
                            'admin.reports.mr-performance.index'
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
        PERFORMANCE LEGEND
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex align-items-center flex-wrap gap-3">

                <strong class="me-2">

                    Collection Rate:

                </strong>


                <span class="d-inline-flex align-items-center">

                    <span
                        class="badge bg-success me-1"
                    >
                        ≥ 90%
                    </span>

                    Excellent

                </span>


                <span class="d-inline-flex align-items-center">

                    <span
                        class="badge bg-info me-1"
                    >
                        70–89%
                    </span>

                    Good

                </span>


                <span class="d-inline-flex align-items-center">

                    <span
                        class="badge bg-warning text-dark me-1"
                    >
                        50–69%
                    </span>

                    Moderate

                </span>


                <span class="d-inline-flex align-items-center">

                    <span
                        class="badge bg-danger me-1"
                    >
                        &lt; 50%
                    </span>

                    Needs Attention

                </span>

            </div>

        </div>

    </div>


    {{-- =========================================================
        REPORT FOOTER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <small class="text-muted">

            MR Performance Report

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