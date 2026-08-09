@extends('layouts.dashboard')

@section('title', 'Payment Due Report')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fas fa-wallet me-2 text-warning"></i>

                Payment Due Report

            </h3>

            <p class="text-muted mb-0">

                Monitor outstanding payments and upcoming or overdue dues.

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
                    'admin.reports.payment-due.export.excel',
                    request()->query()
                ) }}"
                class="btn btn-success"
            >

                <i class="fas fa-file-excel me-1"></i>

                Excel

            </a>


            <a
                href="{{ route(
                    'admin.reports.payment-due.export.pdf',
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


        {{-- Total Outstanding --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Total Outstanding
                            </p>

                            <h3 class="fw-bold mb-0">

                                {{ number_format(
                                    $summary['total_outstanding'],
                                    2
                                ) }}

                            </h3>

                        </div>

                        <div class="text-warning fs-2">

                            <i class="fas fa-wallet"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Overdue Amount --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Overdue Amount
                            </p>

                            <h3 class="fw-bold text-danger mb-0">

                                {{ number_format(
                                    $summary['overdue_amount'],
                                    2
                                ) }}

                            </h3>

                        </div>

                        <div class="text-danger fs-2">

                            <i class="fas fa-exclamation-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Upcoming Amount --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Upcoming Amount
                            </p>

                            <h3 class="fw-bold text-primary mb-0">

                                {{ number_format(
                                    $summary['upcoming_amount'],
                                    2
                                ) }}

                            </h3>

                        </div>

                        <div class="text-primary fs-2">

                            <i class="fas fa-calendar-alt"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Patients With Due --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Patients With Due
                            </p>

                            <h3 class="fw-bold mb-0">

                                {{ number_format(
                                    $summary['patients_with_due']
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

    </div>


    {{-- =========================================================
        SECONDARY SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Average Due --}}

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Average Due
                    </p>

                    <h4 class="fw-bold mb-0">

                        {{ number_format(
                            $summary['average_due'],
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Overdue Count --}}

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Overdue Cases
                    </p>

                    <h4 class="fw-bold text-danger mb-0">

                        {{ number_format(
                            $summary['overdue_count']
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Upcoming Count --}}

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Upcoming Cases
                    </p>

                    <h4 class="fw-bold text-primary mb-0">

                        {{ number_format(
                            $summary['upcoming_count']
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Outstanding Status --}}

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Report Status
                    </p>

                    <h4 class="mb-0">

                        <span class="badge bg-warning text-dark">

                            Payment Due

                        </span>

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

                Filter Payment Due

            </h5>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reports.payment-due.index') }}"
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

                            <option
                                value="cash"
                                @selected(request('payment_method') === 'cash')
                            >
                                Cash
                            </option>

                            <option
                                value="card"
                                @selected(request('payment_method') === 'card')
                            >
                                Card
                            </option>

                            <option
                                value="bank_transfer"
                                @selected(request('payment_method') === 'bank_transfer')
                            >
                                Bank Transfer
                            </option>

                            <option
                                value="mobile_banking"
                                @selected(request('payment_method') === 'mobile_banking')
                            >
                                Mobile Banking
                            </option>

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

                            <option
                                value="1"
                                @selected(request('is_installment') === '1')
                            >
                                Installment
                            </option>

                            <option
                                value="0"
                                @selected(request('is_installment') === '0')
                            >
                                Full Payment
                            </option>

                        </select>

                    </div>


                    {{-- Due Status --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Due Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Due
                            </option>

                            <option
                                value="overdue"
                                @selected(request('status') === 'overdue')
                            >
                                Overdue
                            </option>

                            <option
                                value="upcoming"
                                @selected(request('status') === 'upcoming')
                            >
                                Upcoming
                            </option>

                        </select>

                    </div>


                    {{-- Date From --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            From Date
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
                            To Date
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
                            href="{{ route('admin.reports.payment-due.index') }}"
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
        DUE PAYMENT RECORDS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">

                    <i class="fas fa-money-bill-wave me-2 text-warning"></i>

                    Payment Due Records

                </h5>


                <span class="badge bg-warning text-dark">

                    {{ number_format($payments->total()) }}

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
                                Due
                            </th>

                            <th>
                                Next Payment
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($payments as $payment)

                            @php

                                $paidAmount = max(
                                    0,
                                    (float) $payment->total_amount -
                                    (float) $payment->remaining_amount
                                );

                                if (
                                    $payment->next_payment_date &&
                                    \Carbon\Carbon::parse(
                                        $payment->next_payment_date
                                    )->lt(\Carbon\Carbon::today())
                                ) {

                                    $dueStatus = 'overdue';

                                } elseif ($payment->next_payment_date) {

                                    $dueStatus = 'upcoming';

                                } else {

                                    $dueStatus = 'pending';

                                }

                            @endphp

                            <tr>

                                <td class="text-center">

                                    {{ $payments->firstItem() + $loop->index }}

                                </td>


                                <td>

                                    <span class="fw-semibold">

                                        {{ $payment->predict3d_id ?: '-' }}

                                    </span>

                                </td>


                                <td>

                                    {{ $payment->patient?->FullName ?: '-' }}

                                </td>


                                <td>

                                    {{ $payment->patient?->DoctorName ?: '-' }}

                                </td>


                                <td>

                                    @if($payment->payment_method === 'cash')

                                        <span class="badge bg-success">
                                            Cash
                                        </span>

                                    @elseif($payment->payment_method === 'card')

                                        <span class="badge bg-primary">
                                            Card
                                        </span>

                                    @elseif($payment->payment_method === 'bank_transfer')

                                        <span class="badge bg-info text-dark">
                                            Bank Transfer
                                        </span>

                                    @elseif($payment->payment_method === 'mobile_banking')

                                        <span class="badge bg-warning text-dark">
                                            Mobile Banking
                                        </span>

                                    @else

                                        {{ $payment->payment_method ?: '-' }}

                                    @endif

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        (float) $payment->total_amount,
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

                                    <span class="text-danger fw-semibold">

                                        {{ number_format(
                                            (float) $payment->remaining_amount,
                                            2
                                        ) }}

                                    </span>

                                </td>


                                <td>

                                    @if($payment->next_payment_date)

                                        {{ \Carbon\Carbon::parse(
                                            $payment->next_payment_date
                                        )->format('d M Y') }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    @if($dueStatus === 'overdue')

                                        <span class="badge bg-danger">

                                            <i class="fas fa-exclamation-circle me-1"></i>

                                            Overdue

                                        </span>

                                    @elseif($dueStatus === 'upcoming')

                                        <span class="badge bg-primary">

                                            <i class="fas fa-calendar-check me-1"></i>

                                            Upcoming

                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">

                                            Pending

                                        </span>

                                    @endif

                                </td>


                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'admin.reports.payment-due.show',
                                            $payment->id
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View Details"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="11"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="fas fa-wallet fa-2x mb-3"></i>

                                        <p class="mb-0 fw-semibold">

                                            No payment dues found.

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

        @if($payments->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <small class="text-muted mb-2 mb-md-0">

                        Showing

                        <strong>
                            {{ $payments->firstItem() }}
                        </strong>

                        to

                        <strong>
                            {{ $payments->lastItem() }}
                        </strong>

                        of

                        <strong>
                            {{ $payments->total() }}
                        </strong>

                        payment-due records

                    </small>


                    <div>

                        {{ $payments->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

        {{-- =========================================================
        DUE ANALYSIS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-pie me-2 text-warning"></i>

                Due Payment Analysis

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4 text-center">

                {{-- Total Outstanding --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Total Outstanding
                    </small>

                    <h4 class="fw-bold text-warning mb-0 mt-1">

                        {{ number_format(
                            $summary['total_outstanding'],
                            2
                        ) }}

                    </h4>

                </div>


                {{-- Overdue Amount --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Overdue Amount
                    </small>

                    <h4 class="fw-bold text-danger mb-0 mt-1">

                        {{ number_format(
                            $summary['overdue_amount'],
                            2
                        ) }}

                    </h4>

                </div>


                {{-- Upcoming Amount --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Upcoming Amount
                    </small>

                    <h4 class="fw-bold text-primary mb-0 mt-1">

                        {{ number_format(
                            $summary['upcoming_amount'],
                            2
                        ) }}

                    </h4>

                </div>


                {{-- Average Due --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Average Due
                    </small>

                    <h4 class="fw-bold text-info mb-0 mt-1">

                        {{ number_format(
                            $summary['average_due'],
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DUE STATUS SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-exclamation-triangle me-2 text-danger"></i>

                Due Status Summary

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Overdue --}}

                <div class="col-lg-6">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted d-block">
                                    Overdue Cases
                                </small>

                                <h4 class="fw-bold text-danger mb-1">

                                    {{ number_format(
                                        $summary['overdue_count']
                                    ) }}

                                </h4>

                                <small class="text-muted">

                                    Amount:

                                    <strong class="text-danger">

                                        {{ number_format(
                                            $summary['overdue_amount'],
                                            2
                                        ) }}

                                    </strong>

                                </small>

                            </div>

                            <div class="text-danger fs-2">

                                <i class="fas fa-exclamation-circle"></i>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Upcoming --}}

                <div class="col-lg-6">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted d-block">
                                    Upcoming Cases
                                </small>

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ number_format(
                                        $summary['upcoming_count']
                                    ) }}

                                </h4>

                                <small class="text-muted">

                                    Amount:

                                    <strong class="text-primary">

                                        {{ number_format(
                                            $summary['upcoming_amount'],
                                            2
                                        ) }}

                                    </strong>

                                </small>

                            </div>

                            <div class="text-primary fs-2">

                                <i class="fas fa-calendar-check"></i>

                            </div>

                        </div>

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
        request()->filled('doctor') ||
        request()->filled('payment_method') ||
        request()->filled('is_installment') ||
        request()->filled('status') ||
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
                        href="{{ route('admin.reports.payment-due.index') }}"
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

            Payment Due Report

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