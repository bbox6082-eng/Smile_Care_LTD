@extends('layouts.dashboard')

@section('title', 'Delivery Pending Report')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fas fa-truck me-2 text-info"></i>

                Delivery Pending Report

            </h3>

            <p class="text-muted mb-0">

                Monitor pending upper and lower case deliveries.

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
                    'admin.reports.delivery-pending.export.excel',
                    request()->query()
                ) }}"
                class="btn btn-success"
            >

                <i class="fas fa-file-excel me-1"></i>

                Excel

            </a>


            <a
                href="{{ route(
                    'admin.reports.delivery-pending.export.pdf',
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
        PRIMARY SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Pending Cases --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Pending Cases
                            </p>

                            <h3 class="fw-bold mb-0">

                                {{ number_format(
                                    $summary['total_cases']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-info fs-2">

                            <i class="fas fa-truck"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Pending --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Total Pending
                            </p>

                            <h3 class="fw-bold text-danger mb-0">

                                {{ number_format(
                                    $summary['total_pending']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-danger fs-2">

                            <i class="fas fa-hourglass-half"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Pending Upper --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Pending Upper
                            </p>

                            <h3 class="fw-bold text-primary mb-0">

                                {{ number_format(
                                    $summary['remaining_upper']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-primary fs-2">

                            <i class="fas fa-arrow-up"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Pending Lower --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Pending Lower
                            </p>

                            <h3 class="fw-bold text-warning mb-0">

                                {{ number_format(
                                    $summary['remaining_lower']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-warning fs-2">

                            <i class="fas fa-arrow-down"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DELIVERY SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Total Upper --}}

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Total Upper Cases
                    </p>

                    <h4 class="fw-bold mb-0">

                        {{ number_format(
                            $summary['total_upper']
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Delivered Upper --}}

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Delivered Upper
                    </p>

                    <h4 class="fw-bold text-success mb-0">

                        {{ number_format(
                            $summary['delivered_upper']
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Total Lower --}}

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Total Lower Cases
                    </p>

                    <h4 class="fw-bold mb-0">

                        {{ number_format(
                            $summary['total_lower']
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Delivered Lower --}}

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <p class="text-muted mb-1">
                        Delivered Lower
                    </p>

                    <h4 class="fw-bold text-success mb-0">

                        {{ number_format(
                            $summary['delivered_lower']
                        ) }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DELIVERY PROGRESS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-line me-2 text-success"></i>

                Delivery Progress

            </h5>

        </div>

        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">

                <span class="text-muted">
                    Average Delivery Progress
                </span>

                <strong>

                    {{ number_format(
                        $summary['average_delivery_percentage'],
                        2
                    ) }}%

                </strong>

            </div>

            <div
                class="progress"
                style="height: 10px;"
            >

                <div
                    class="progress-bar bg-success"
                    role="progressbar"
                    style="width: {{ min(
                        100,
                        max(
                            0,
                            $summary['average_delivery_percentage']
                        )
                    ) }}%;"
                ></div>

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

                Filter Delivery Pending

            </h5>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reports.delivery-pending.index') }}"
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


                    {{-- Delivery Status --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Delivery Status
                        </label>

                        <select
                            name="delivery_status"
                            class="form-select"
                        >

                            <option value="">
                                All Pending
                            </option>

                            <option
                                value="upper_pending"
                                @selected(request('delivery_status') === 'upper_pending')
                            >
                                Upper Pending
                            </option>

                            <option
                                value="lower_pending"
                                @selected(request('delivery_status') === 'lower_pending')
                            >
                                Lower Pending
                            </option>

                            <option
                                value="both_pending"
                                @selected(request('delivery_status') === 'both_pending')
                            >
                                Both Pending
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
                            href="{{ route('admin.reports.delivery-pending.index') }}"
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
        PENDING DELIVERY RECORDS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">

                    <i class="fas fa-truck-loading me-2 text-info"></i>

                    Pending Delivery Records

                </h5>


                <span class="badge bg-info text-dark">

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

                            <th class="text-center">
                                Upper
                            </th>

                            <th class="text-center">
                                Lower
                            </th>

                            <th class="text-center">
                                Pending
                            </th>

                            <th>
                                Progress
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


                                {{-- Upper --}}

                                <td class="text-center">

                                    <div class="small text-muted">

                                        Total:
                                        {{ $payment->total_upper }}

                                    </div>

                                    <div class="fw-semibold">

                                        Delivered:
                                        <span class="text-success">

                                            {{ $payment->delivered_upper }}

                                        </span>

                                    </div>

                                    <div class="fw-semibold">

                                        Pending:
                                        <span class="text-danger">

                                            {{ $payment->remaining_upper }}

                                        </span>

                                    </div>

                                </td>


                                {{-- Lower --}}

                                <td class="text-center">

                                    <div class="small text-muted">

                                        Total:
                                        {{ $payment->total_lower }}

                                    </div>

                                    <div class="fw-semibold">

                                        Delivered:
                                        <span class="text-success">

                                            {{ $payment->delivered_lower }}

                                        </span>

                                    </div>

                                    <div class="fw-semibold">

                                        Pending:
                                        <span class="text-danger">

                                            {{ $payment->remaining_lower }}

                                        </span>

                                    </div>

                                </td>


                                {{-- Total Pending --}}

                                <td class="text-center">

                                    <span class="badge bg-danger fs-6">

                                        {{ $payment->remaining_cases }}

                                    </span>

                                </td>


                                {{-- Progress --}}

                                <td style="min-width: 140px;">

                                    <div class="d-flex justify-content-between mb-1">

                                        <small class="text-muted">
                                            Progress
                                        </small>

                                        <small class="fw-semibold">

                                            {{ number_format(
                                                $payment->delivery_percentage,
                                                2
                                            ) }}%

                                        </small>

                                    </div>

                                    <div
                                        class="progress"
                                        style="height: 7px;"
                                    >

                                        <div
                                            class="progress-bar bg-success"
                                            role="progressbar"
                                            style="width: {{ min(
                                                100,
                                                max(
                                                    0,
                                                    $payment->delivery_percentage
                                                )
                                            ) }}%;"
                                        ></div>

                                    </div>

                                </td>


                                {{-- Status --}}

                                <td>

                                    @if(
                                        $payment->delivery_status === 'both_pending'
                                    )

                                        <span class="badge bg-danger">

                                            Both Pending

                                        </span>

                                    @elseif(
                                        $payment->delivery_status === 'upper_pending'
                                    )

                                        <span class="badge bg-primary">

                                            Upper Pending

                                        </span>

                                    @elseif(
                                        $payment->delivery_status === 'lower_pending'
                                    )

                                        <span class="badge bg-warning text-dark">

                                            Lower Pending

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            Pending

                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}

                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'admin.reports.delivery-pending.show',
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
                                    colspan="10"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="fas fa-truck fa-2x mb-3"></i>

                                        <p class="mb-0 fw-semibold">

                                            No pending deliveries found.

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

                        pending delivery records

                    </small>


                    <div>

                        {{ $payments->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

        {{-- =========================================================
        DELIVERY ANALYSIS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-pie me-2 text-info"></i>

                Delivery Analysis

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Upper Delivery --}}

                <div class="col-lg-6">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <div>

                                <small class="text-muted d-block">
                                    Upper Case Delivery
                                </small>

                                <h4 class="fw-bold mb-0">

                                    {{ number_format(
                                        $summary['delivered_upper']
                                    ) }}

                                    <small class="text-muted fs-6">

                                        /
                                        {{ number_format(
                                            $summary['total_upper']
                                        ) }}

                                    </small>

                                </h4>

                            </div>

                            <div class="text-primary fs-2">

                                <i class="fas fa-arrow-up"></i>

                            </div>

                        </div>

                        @php

                            $upperPercentage =
                                $summary['total_upper'] > 0
                                    ? (
                                        $summary['delivered_upper']
                                        /
                                        $summary['total_upper']
                                    ) * 100
                                    : 0;

                        @endphp

                        <div class="d-flex justify-content-between mb-1">

                            <small class="text-muted">
                                Delivery Progress
                            </small>

                            <small class="fw-semibold">

                                {{ number_format(
                                    $upperPercentage,
                                    2
                                ) }}%

                            </small>

                        </div>

                        <div
                            class="progress"
                            style="height: 8px;"
                        >

                            <div
                                class="progress-bar bg-primary"
                                role="progressbar"
                                style="width: {{ min(
                                    100,
                                    max(
                                        0,
                                        $upperPercentage
                                    )
                                ) }}%;"
                            ></div>

                        </div>

                        <div class="mt-2">

                            <small class="text-danger">

                                Pending:

                                <strong>

                                    {{ number_format(
                                        $summary['remaining_upper']
                                    ) }}

                                </strong>

                            </small>

                        </div>

                    </div>

                </div>


                {{-- Lower Delivery --}}

                <div class="col-lg-6">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <div>

                                <small class="text-muted d-block">
                                    Lower Case Delivery
                                </small>

                                <h4 class="fw-bold mb-0">

                                    {{ number_format(
                                        $summary['delivered_lower']
                                    ) }}

                                    <small class="text-muted fs-6">

                                        /
                                        {{ number_format(
                                            $summary['total_lower']
                                        ) }}

                                    </small>

                                </h4>

                            </div>

                            <div class="text-warning fs-2">

                                <i class="fas fa-arrow-down"></i>

                            </div>

                        </div>

                        @php

                            $lowerPercentage =
                                $summary['total_lower'] > 0
                                    ? (
                                        $summary['delivered_lower']
                                        /
                                        $summary['total_lower']
                                    ) * 100
                                    : 0;

                        @endphp

                        <div class="d-flex justify-content-between mb-1">

                            <small class="text-muted">
                                Delivery Progress
                            </small>

                            <small class="fw-semibold">

                                {{ number_format(
                                    $lowerPercentage,
                                    2
                                ) }}%

                            </small>

                        </div>

                        <div
                            class="progress"
                            style="height: 8px;"
                        >

                            <div
                                class="progress-bar bg-warning"
                                role="progressbar"
                                style="width: {{ min(
                                    100,
                                    max(
                                        0,
                                        $lowerPercentage
                                    )
                                ) }}%;"
                            ></div>

                        </div>

                        <div class="mt-2">

                            <small class="text-danger">

                                Pending:

                                <strong>

                                    {{ number_format(
                                        $summary['remaining_lower']
                                    ) }}

                                </strong>

                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PENDING STATUS SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-tasks me-2 text-danger"></i>

                Pending Delivery Overview

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3 text-center">

                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <small class="text-muted d-block">
                            Pending Upper
                        </small>

                        <h4 class="fw-bold text-primary mb-0 mt-1">

                            {{ number_format(
                                $summary['remaining_upper']
                            ) }}

                        </h4>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <small class="text-muted d-block">
                            Pending Lower
                        </small>

                        <h4 class="fw-bold text-warning mb-0 mt-1">

                            {{ number_format(
                                $summary['remaining_lower']
                            ) }}

                        </h4>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <small class="text-muted d-block">
                            Total Pending
                        </small>

                        <h4 class="fw-bold text-danger mb-0 mt-1">

                            {{ number_format(
                                $summary['total_pending']
                            ) }}

                        </h4>

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
        request()->filled('delivery_status') ||
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

                        The report is currently filtered
                        based on your selected criteria.

                    </span>

                    <a
                        href="{{ route(
                            'admin.reports.delivery-pending.index'
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

            Delivery Pending Report

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