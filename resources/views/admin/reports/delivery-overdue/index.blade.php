@extends('layouts.dashboard')

@section('title', 'Delivery Overdue Report')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fas fa-exclamation-triangle me-2 text-danger"></i>

                Delivery Overdue Report

            </h3>

            <p class="text-muted mb-0">

                Monitor cases where the scheduled delivery date has passed.

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
                    'admin.reports.delivery-overdue.export.excel',
                    request()->query()
                ) }}"
                class="btn btn-success"
            >

                <i class="fas fa-file-excel me-1"></i>

                Excel

            </a>


            <a
                href="{{ route(
                    'admin.reports.delivery-overdue.export.pdf',
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


        {{-- Overdue Cases --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Overdue Cases
                            </p>

                            <h3 class="fw-bold text-danger mb-0">

                                {{ number_format(
                                    $summary['total_cases']
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


        {{-- Average Overdue --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Average Overdue
                            </p>

                            <h3 class="fw-bold mb-0">

                                {{ number_format(
                                    $summary['average_overdue_days'],
                                    1
                                ) }}

                                <small class="text-muted fs-6">
                                    days
                                </small>

                            </h3>

                        </div>

                        <div class="text-warning fs-2">

                            <i class="fas fa-clock"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Maximum Overdue --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Maximum Overdue
                            </p>

                            <h3 class="fw-bold text-danger mb-0">

                                {{ number_format(
                                    $summary['maximum_overdue_days']
                                ) }}

                                <small class="text-muted fs-6">
                                    days
                                </small>

                            </h3>

                        </div>

                        <div class="text-danger fs-2">

                            <i class="fas fa-calendar-times"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Days Per Aligner --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Days Per Aligner
                            </p>

                            <h3 class="fw-bold text-primary mb-0">

                                {{ number_format(
                                    $payments->first()?->days_per_aligner ?? 15
                                ) }}

                                <small class="text-muted fs-6">
                                    days
                                </small>

                            </h3>

                        </div>

                        <div class="text-primary fs-2">

                            <i class="fas fa-calendar-alt"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        OVERDUE SEVERITY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-line me-2 text-danger"></i>

                Overdue Severity

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3 text-center">

                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <small class="text-muted d-block">
                            Overdue Cases
                        </small>

                        <h4 class="fw-bold text-danger mb-0 mt-1">

                            {{ number_format(
                                $summary['total_cases']
                            ) }}

                        </h4>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <small class="text-muted d-block">
                            Average Delay
                        </small>

                        <h4 class="fw-bold text-warning mb-0 mt-1">

                            {{ number_format(
                                $summary['average_overdue_days'],
                                1
                            ) }}

                            <small class="fs-6">
                                days
                            </small>

                        </h4>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <small class="text-muted d-block">
                            Longest Delay
                        </small>

                        <h4 class="fw-bold text-danger mb-0 mt-1">

                            {{ number_format(
                                $summary['maximum_overdue_days']
                            ) }}

                            <small class="fs-6">
                                days
                            </small>

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

                Filter Overdue Deliveries

            </h5>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reports.delivery-overdue.index') }}"
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


                    {{-- Date From --}}

                    <div class="col-lg-2 col-md-6">

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

                    <div class="col-lg-2 col-md-6">

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

                    <div class="col-lg-4 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="fas fa-search me-1"></i>

                            Apply Filters

                        </button>


                        <a
                            href="{{ route('admin.reports.delivery-overdue.index') }}"
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
        OVERDUE RECORDS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">

                    <i class="fas fa-calendar-times me-2 text-danger"></i>

                    Overdue Delivery Records

                </h5>


                <span class="badge bg-danger">

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
                                Last Delivery
                            </th>

                            <th>
                                Next Due
                            </th>

                            <th class="text-center">
                                Cycle
                            </th>

                            <th class="text-center">
                                Overdue
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


                                {{-- Predict3D --}}

                                <td>

                                    <span class="fw-semibold">

                                        {{ $payment->predict3d_id ?: '-' }}

                                    </span>

                                </td>


                                {{-- Patient --}}

                                <td>

                                    {{ $payment->patient?->FullName ?: '-' }}

                                </td>


                                {{-- Doctor --}}

                                <td>

                                    {{ $payment->patient?->DoctorName ?: '-' }}

                                </td>


                                {{-- Last Delivery --}}

                                <td>

                                    @if($payment->latest_delivery_date)

                                        <div class="fw-semibold">

                                            {{ \Carbon\Carbon::parse(
                                                $payment->latest_delivery_date
                                            )->format('d M Y') }}

                                        </div>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- Next Due --}}

                                <td>

                                    @if($payment->next_delivery_due_date)

                                        <div class="fw-semibold text-danger">

                                            {{ \Carbon\Carbon::parse(
                                                $payment->next_delivery_due_date
                                            )->format('d M Y') }}

                                        </div>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- Cycle --}}

                                <td class="text-center">

                                    <div class="fw-semibold">

                                        {{ number_format(
                                            $payment->max_cases
                                        ) }}

                                        cases

                                    </div>

                                    <small class="text-muted">

                                        {{ number_format(
                                            $payment->cycle_days
                                        ) }}
                                        days

                                    </small>

                                </td>


                                {{-- Overdue --}}

                                <td class="text-center">

                                    <span class="badge bg-danger fs-6">

                                        {{ number_format(
                                            $payment->overdue_days
                                        ) }}

                                    </span>

                                    <small class="d-block text-muted mt-1">

                                        day(s)

                                    </small>

                                </td>


                                {{-- Status --}}

                                <td>

                                    <span class="badge bg-danger">

                                        <i class="fas fa-exclamation-circle me-1"></i>

                                        Overdue

                                    </span>

                                </td>


                                {{-- Action --}}

                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'admin.reports.delivery-overdue.show',
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

                                        <i class="fas fa-check-circle fa-2x mb-3 text-success"></i>

                                        <p class="mb-0 fw-semibold">

                                            No overdue deliveries found.

                                        </p>

                                        <small>

                                            All scheduled deliveries
                                            are currently within their
                                            delivery cycle.

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

                        overdue records

                    </small>


                    <div>

                        {{ $payments->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

        {{-- =========================================================
        OVERDUE ANALYSIS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-bar me-2 text-danger"></i>

                Overdue Analysis

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Delivery Cycle --}}

                <div class="col-lg-4">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Delivery Cycle
                        </small>

                        <h4 class="fw-bold mt-1 mb-2">

                            {{ number_format(
                                $payments->first()?->cycle_days ?? 0
                            ) }}

                            <small class="text-muted fs-6">
                                days
                            </small>

                        </h4>

                        <p class="text-muted small mb-0">

                            Based on the latest delivery cycle
                            and configured days per aligner.

                        </p>

                    </div>

                </div>


                {{-- Average Delay --}}

                <div class="col-lg-4">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Average Delay
                        </small>

                        <h4 class="fw-bold text-warning mt-1 mb-2">

                            {{ number_format(
                                $summary['average_overdue_days'],
                                1
                            ) }}

                            <small class="text-muted fs-6">
                                days
                            </small>

                        </h4>

                        <p class="text-muted small mb-0">

                            Average number of days past
                            the scheduled delivery date.

                        </p>

                    </div>

                </div>


                {{-- Maximum Delay --}}

                <div class="col-lg-4">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Maximum Delay
                        </small>

                        <h4 class="fw-bold text-danger mt-1 mb-2">

                            {{ number_format(
                                $summary['maximum_overdue_days']
                            ) }}

                            <small class="text-muted fs-6">
                                days
                            </small>

                        </h4>

                        <p class="text-muted small mb-0">

                            Longest delivery delay
                            currently recorded.

                        </p>

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
                            'admin.reports.delivery-overdue.index'
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

            Delivery Overdue Report

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