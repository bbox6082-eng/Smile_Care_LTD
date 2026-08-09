@extends('layouts.dashboard')

@section('title', 'Doctor Revenue Report')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fas fa-coins me-2 text-info"></i>

                Doctor Revenue

            </h3>

            <p class="text-muted mb-0">

                Revenue collected and summarized by doctor.

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
                    'admin.reports.doctor-revenue.export.excel',
                    request()->query()
                ) }}"
                class="btn btn-success"
            >

                <i class="fas fa-file-excel me-1"></i>

                Excel

            </a>


            <a
                href="{{ route(
                    'admin.reports.doctor-revenue.export.pdf',
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


        {{-- Total Revenue --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Total Revenue
                            </p>

                            <h3 class="fw-bold text-success mb-0">

                                {{ number_format(
                                    $summary['total_revenue'],
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


        {{-- Transactions --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Transactions
                            </p>

                            <h3 class="fw-bold text-primary mb-0">

                                {{ number_format(
                                    $summary['total_transactions']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-primary fs-2">

                            <i class="fas fa-receipt"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Payment Plans --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Payment Plans
                            </p>

                            <h3 class="fw-bold text-info mb-0">

                                {{ number_format(
                                    $summary['total_plans']
                                ) }}

                            </h3>

                        </div>

                        <div class="text-info fs-2">

                            <i class="fas fa-file-invoice-dollar"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Pending Due --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Pending Due
                            </p>

                            <h3 class="fw-bold text-danger mb-0">

                                {{ number_format(
                                    $summary['pending_due'],
                                    2
                                ) }}

                            </h3>

                        </div>

                        <div class="text-danger fs-2">

                            <i class="fas fa-wallet"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        REVENUE ANALYSIS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-line me-2 text-info"></i>

                Revenue Analysis

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Doctors --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Doctors
                        </small>

                        <h4 class="fw-bold text-info mb-0 mt-1">

                            {{ number_format(
                                $doctorRevenue->total()
                            ) }}

                        </h4>

                    </div>

                </div>


                {{-- Revenue --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Collected Revenue
                        </small>

                        <h4 class="fw-bold text-success mb-0 mt-1">

                            {{ number_format(
                                $summary['total_revenue'],
                                2
                            ) }}

                        </h4>

                    </div>

                </div>


                {{-- Average Transaction --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Average Transaction
                        </small>

                        <h4 class="fw-bold text-primary mb-0 mt-1">

                            {{ number_format(
                                $summary['average_transaction'],
                                2
                            ) }}

                        </h4>

                    </div>

                </div>


                {{-- Pending --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 text-center h-100">

                        <small class="text-muted d-block">
                            Outstanding Due
                        </small>

                        <h4 class="fw-bold text-danger mb-0 mt-1">

                            {{ number_format(
                                $summary['pending_due'],
                                2
                            ) }}

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

                <i class="fas fa-filter me-2 text-info"></i>

                Filter Revenue

            </h5>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reports.doctor-revenue.index') }}"
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
                            placeholder="Patient, ID, doctor..."
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
                                @selected(
                                    request('payment_method') === 'bank_transfer'
                                )
                            >
                                Bank Transfer
                            </option>

                            <option
                                value="bkash"
                                @selected(request('payment_method') === 'bkash')
                            >
                                Bkash
                            </option>

                            <option
                                value="nagad"
                                @selected(request('payment_method') === 'nagad')
                            >
                                Nagad
                            </option>

                            <option
                                value="roket"
                                @selected(request('payment_method') === 'roket')
                            >
                                Roket
                            </option>

                        </select>

                    </div>


                    {{-- Payment Status --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Payment Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="Paid"
                                @selected(request('status') === 'Paid')
                            >
                                Paid
                            </option>

                            <option
                                value="Partial"
                                @selected(request('status') === 'Partial')
                            >
                                Partial
                            </option>

                            <option
                                value="Due"
                                @selected(request('status') === 'Due')
                            >
                                Due
                            </option>

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
                                'admin.reports.doctor-revenue.index'
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
        DOCTOR REVENUE TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">

                    <i class="fas fa-coins me-2 text-success"></i>

                    Doctor Revenue Summary

                </h5>


                <span class="badge bg-info">

                    {{ number_format(
                        $doctorRevenue->total()
                    ) }}

                    Doctors

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

                            <th class="text-end">
                                Total Revenue
                            </th>

                            <th class="text-center">
                                Transactions
                            </th>

                            <th class="text-center">
                                Payment Plans
                            </th>

                            <th class="text-end">
                                Avg. / Transaction
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($doctorRevenue as $row)

                            <tr>

                                {{-- Serial --}}

                                <td class="text-center">

                                    {{ $doctorRevenue->firstItem() + $loop->index }}

                                </td>


                                {{-- Doctor --}}

                                <td>

                                    <div class="d-flex align-items-center">

                                        <div
                                            class="rounded-circle bg-info bg-opacity-10
                                                   text-info d-flex align-items-center
                                                   justify-content-center me-2"
                                            style="width:38px;height:38px;"
                                        >

                                            <i class="fas fa-user-md"></i>

                                        </div>

                                        <div>

                                            <div class="fw-semibold">

                                                {{ $row['doctor'] ?: 'Unknown' }}

                                            </div>

                                            <small class="text-muted">

                                                Doctor Revenue

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- Revenue --}}

                                <td class="text-end">

                                    <span class="fw-bold text-success">

                                        {{ number_format(
                                            $row['revenue'],
                                            2
                                        ) }}

                                    </span>

                                </td>


                                {{-- Transactions --}}

                                <td class="text-center">

                                    <span class="badge bg-primary">

                                        {{ number_format(
                                            $row['transactions']
                                        ) }}

                                    </span>

                                </td>


                                {{-- Payment Plans --}}

                                <td class="text-center">

                                    <span class="badge bg-light text-dark border">

                                        {{ number_format(
                                            $row['payment_plans']
                                        ) }}

                                    </span>

                                </td>


                                {{-- Average --}}

                                <td class="text-end fw-semibold">

                                    {{ number_format(
                                        $row['transactions'] > 0
                                            ? $row['revenue'] /
                                              $row['transactions']
                                            : 0,
                                        2
                                    ) }}

                                </td>


                                {{-- Action --}}

                                <td class="text-center">

                                    <a
                                        href="{{ route(
                                            'admin.reports.doctor-revenue.show',
                                            [
                                                'doctor' => $row['doctor'],
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View Doctor Revenue"
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

                                        <i class="fas fa-coins fa-2x mb-3"></i>

                                        <p class="mb-0 fw-semibold">

                                            No revenue records found.

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

        @if($doctorRevenue->hasPages())

            <div class="card-footer bg-white">

                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <small class="text-muted mb-2 mb-md-0">

                        Showing

                        <strong>
                            {{ $doctorRevenue->firstItem() }}
                        </strong>

                        to

                        <strong>
                            {{ $doctorRevenue->lastItem() }}
                        </strong>

                        of

                        <strong>
                            {{ $doctorRevenue->total() }}
                        </strong>

                        doctors

                    </small>


                    <div>

                        {{ $doctorRevenue->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

        {{-- =========================================================
        REVENUE INSIGHTS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-bar me-2 text-info"></i>

                Revenue Insights

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Total Revenue --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Total Collected Revenue
                        </small>

                        <h4 class="fw-bold text-success mb-1 mt-1">

                            {{ number_format(
                                $summary['total_revenue'],
                                2
                            ) }}

                        </h4>

                        <small class="text-muted">
                            From recorded payment transactions
                        </small>

                    </div>

                </div>


                {{-- Average Transaction --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Average Transaction
                        </small>

                        <h4 class="fw-bold text-primary mb-1 mt-1">

                            {{ number_format(
                                $summary['average_transaction'],
                                2
                            ) }}

                        </h4>

                        <small class="text-muted">
                            Average collected amount per transaction
                        </small>

                    </div>

                </div>


                {{-- Payment Plans --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Payment Plans
                        </small>

                        <h4 class="fw-bold text-info mb-1 mt-1">

                            {{ number_format(
                                $summary['total_plans']
                            ) }}

                        </h4>

                        <small class="text-muted">
                            Payment plans matching the filters
                        </small>

                    </div>

                </div>


                {{-- Pending Due --}}

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded p-3 h-100">

                        <small class="text-muted d-block">
                            Outstanding Due
                        </small>

                        <h4 class="fw-bold text-danger mb-1 mt-1">

                            {{ number_format(
                                $summary['pending_due'],
                                2
                            ) }}

                        </h4>

                        <small class="text-muted">
                            Remaining amount on matching plans
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
        request()->filled('doctor') ||
        request()->filled('payment_method') ||
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

                        The revenue report is currently filtered
                        according to your selected criteria.

                    </span>

                    <a
                        href="{{ route(
                            'admin.reports.doctor-revenue.index'
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

            Doctor Revenue Report

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