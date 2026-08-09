@extends('layouts.dashboard')

@section('title', 'Completed Cases Report')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fas fa-check-circle me-2 text-success"></i>

                Completed Cases Report

            </h3>

            <p class="text-muted mb-0">

                View and analyze completed treatment cases.

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
                    'admin.reports.completed-cases.export.excel',
                    request()->query()
                ) }}"
                class="btn btn-success"
            >

                <i class="fas fa-file-excel me-1"></i>

                Excel

            </a>


            <a
                href="{{ route(
                    'admin.reports.completed-cases.export.pdf',
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
        PRIMARY SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Completed Cases --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Completed Cases
                            </p>

                            <h3 class="fw-bold mb-0">

                                {{ number_format(
                                    $summary['total_cases']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-success fs-2">

                            <i class="fas fa-check-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Doctors --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Doctors
                            </p>

                            <h3 class="fw-bold mb-0">

                                {{ number_format(
                                    $summary['total_doctors']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-primary fs-2">

                            <i class="fas fa-user-doctor"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Case Value --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Total Case Value
                            </p>

                            <h3 class="fw-bold mb-0">

                                {{ number_format(
                                    $summary['total_amount'],
                                    2
                                ) }}

                            </h3>

                        </div>

                        <div class="text-info fs-2">

                            <i class="fas fa-coins"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Remaining Amount --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Remaining Amount
                            </p>

                            <h3 class="fw-bold mb-0">

                                {{ number_format(
                                    $summary['remaining_amount'],
                                    2
                                ) }}

                            </h3>

                        </div>

                        <div class="text-warning fs-2">

                            <i class="fas fa-money-bill-wave"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SECONDARY SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Paid Amount --}}

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Paid Amount
                    </p>

                    <h4 class="fw-bold text-success mb-0">

                        {{ number_format(
                            $summary['paid_amount'],
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Installment Cases --}}

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Installment Cases
                    </p>

                    <h4 class="fw-bold mb-0">

                        {{ number_format(
                            $summary['installment_cases']
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Full Payment Cases --}}

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Full Payment Cases
                    </p>

                    <h4 class="fw-bold mb-0">

                        {{ number_format(
                            $summary['full_payment_cases']
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Total Deliveries --}}

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Total Deliveries
                    </p>

                    <h4 class="fw-bold mb-0">

                        {{ number_format(
                            $summary['total_deliveries']
                        ) }}

                    </h4>

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

                Filter Completed Cases

            </h5>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reports.completed-cases.index') }}"
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
                            placeholder="Patient, Predict3D ID, Doctor..."
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


                    {{-- Payment Method --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Payment Method
                        </label>

                        <select
                            name="payment_method"
                            class="form-select"
                        >

                            <option value="">
                                All Methods
                            </option>

                            @foreach($filters['payment_methods'] as $method)

                                <option
                                    value="{{ $method }}"
                                    @selected(request('payment_method') === $method)
                                >

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $method
                                        )
                                    ) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Payment Type --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Payment Type
                        </label>

                        <select
                            name="is_installment"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            @foreach(
                                $filters['installment_options']
                                as $value => $label
                            )

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        request('is_installment')
                                        === (string) $value
                                    )
                                >

                                    {{ $label }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Date From --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Case Completed From
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
                            Case Completed To
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="{{ request('date_to') }}"
                        >

                    </div>


                    {{-- Buttons --}}

                    <div class="col-lg-6 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="fas fa-search me-1"></i>

                            Apply Filters

                        </button>


                        <a
                            href="{{ route('admin.reports.completed-cases.index') }}"
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
        COMPLETED CASE RECORDS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">

                    <i class="fas fa-check-circle me-2 text-success"></i>

                    Completed Case Records

                </h5>


                <span class="badge bg-success">

                    {{ number_format($cases->total()) }}

                    Cases

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
                                Payment Method
                            </th>

                            <th class="text-end">
                                Total
                            </th>

                            <th class="text-end">
                                Paid
                            </th>

                            <th class="text-end">
                                Remaining
                            </th>

                            <th>
                                Installment
                            </th>

                            <th>
                                Case Closed
                            </th>

                            <th>
                                Opened
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($cases as $case)

                            @php

                                $paidAmount = max(
                                    0,
                                    (float) $case->total_amount -
                                    (float) $case->remaining_amount
                                );

                            @endphp

                            <tr>

                                <td class="text-center">

                                    {{ $cases->firstItem() + $loop->index }}

                                </td>


                                <td>

                                    <span class="fw-semibold">

                                        {{ $case->predict3d_id ?: '-' }}

                                    </span>

                                </td>


                                <td>

                                    {{ $case->patient?->FullName ?: '-' }}

                                </td>


                                <td>

                                    {{ $case->patient?->DoctorName ?: '-' }}

                                </td>


                                <td>

                                    @if($case->payment_method === 'cash')

                                        <span class="badge bg-success">
                                            Cash
                                        </span>

                                    @elseif($case->payment_method === 'card')

                                        <span class="badge bg-primary">
                                            Card
                                        </span>

                                    @elseif($case->payment_method === 'bank_transfer')

                                        <span class="badge bg-info text-dark">
                                            Bank Transfer
                                        </span>

                                    @elseif($case->payment_method === 'mobile_banking')

                                        <span class="badge bg-warning text-dark">
                                            Mobile Banking
                                        </span>

                                    @else

                                        {{ $case->payment_method ?: '-' }}

                                    @endif

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        (float) $case->total_amount,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end text-success">

                                    {{ number_format(
                                        $paidAmount,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    <span
                                        class="{{ $case->remaining_amount > 0
                                            ? 'text-danger fw-semibold'
                                            : 'text-success' }}"
                                    >

                                        {{ number_format(
                                            (float) $case->remaining_amount,
                                            2
                                        ) }}

                                    </span>

                                </td>


                                <td>

                                    @if($case->is_installment)

                                        <span class="badge bg-warning text-dark">
                                            Installment
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Full Payment
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <span class="badge bg-success">

                                        Completed

                                    </span>

                                </td>


                                <td>

                                    {{ $case->created_at
                                        ? $case->created_at->format('d M Y')
                                        : '-' }}

                                </td>


                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'admin.reports.completed-cases.show',
                                            $case->id
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View Case"
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

                                        <i class="fas fa-check-circle fa-2x mb-3"></i>

                                        <p class="mb-0 fw-semibold">

                                            No completed cases found.

                                        </p>

                                        <small>

                                            Try changing your search
                                            or filter criteria.

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

        @if($cases->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <small class="text-muted mb-2 mb-md-0">

                        Showing

                        <strong>
                            {{ $cases->firstItem() }}
                        </strong>

                        to

                        <strong>
                            {{ $cases->lastItem() }}
                        </strong>

                        of

                        <strong>
                            {{ $cases->total() }}
                        </strong>

                        completed cases

                    </small>


                    <div>

                        {{ $cases->links() }}

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
        request()->filled('payment_method') ||
        request()->filled('is_installment') ||
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
                        href="{{ route('admin.reports.completed-cases.index') }}"
                        class="ms-2"
                    >
                        Clear Filters
                    </a>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        CASE FINANCIAL SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-pie me-2 text-success"></i>

                Completed Case Financial Summary

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4 text-center">


                {{-- Total Case Value --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Total Case Value
                    </small>

                    <h4 class="fw-bold text-primary mb-0 mt-1">

                        {{ number_format(
                            $summary['total_amount'],
                            2
                        ) }}

                    </h4>

                </div>


                {{-- Paid Amount --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Paid Amount
                    </small>

                    <h4 class="fw-bold text-success mb-0 mt-1">

                        {{ number_format(
                            $summary['paid_amount'],
                            2
                        ) }}

                    </h4>

                </div>


                {{-- Remaining Amount --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Remaining Amount
                    </small>

                    <h4 class="fw-bold text-danger mb-0 mt-1">

                        {{ number_format(
                            $summary['remaining_amount'],
                            2
                        ) }}

                    </h4>

                </div>


                {{-- Total Payments --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Total Payments
                    </small>

                    <h4 class="fw-bold text-info mb-0 mt-1">

                        {{ number_format(
                            $summary['total_payments']
                        ) }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        COMPLETION SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4 text-center">


                {{-- Completed Cases --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Completed Cases
                    </small>

                    <h5 class="fw-bold text-success mb-0 mt-1">

                        {{ number_format(
                            $summary['total_cases']
                        ) }}

                    </h5>

                </div>


                {{-- Doctors --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Doctors
                    </small>

                    <h5 class="fw-bold text-primary mb-0 mt-1">

                        {{ number_format(
                            $summary['total_doctors']
                        ) }}

                    </h5>

                </div>


                {{-- Installment Cases --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Installment Cases
                    </small>

                    <h5 class="fw-bold mb-0 mt-1">

                        {{ number_format(
                            $summary['installment_cases']
                        ) }}

                    </h5>

                </div>


                {{-- Full Payment Cases --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Full Payment Cases
                    </small>

                    <h5 class="fw-bold mb-0 mt-1">

                        {{ number_format(
                            $summary['full_payment_cases']
                        ) }}

                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        REPORT INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4 text-center">


                {{-- Total Deliveries --}}

                <div class="col-lg-4 col-md-6">

                    <small class="text-muted d-block">
                        Total Deliveries
                    </small>

                    <h5 class="fw-bold text-info mb-0 mt-1">

                        {{ number_format(
                            $summary['total_deliveries']
                        ) }}

                    </h5>

                </div>


                {{-- Total Payments --}}

                <div class="col-lg-4 col-md-6">

                    <small class="text-muted d-block">
                        Total Payments
                    </small>

                    <h5 class="fw-bold text-success mb-0 mt-1">

                        {{ number_format(
                            $summary['total_payments']
                        ) }}

                    </h5>

                </div>


                {{-- Case Status --}}

                <div class="col-lg-4 col-md-6">

                    <small class="text-muted d-block">
                        Case Status
                    </small>

                    <h5 class="mb-0 mt-1">

                        <span class="badge bg-success">
                            Completed
                        </span>

                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PAGE FOOTER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <small class="text-muted">

            Completed Cases Report

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