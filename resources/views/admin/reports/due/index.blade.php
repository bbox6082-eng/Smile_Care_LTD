@extends('layouts.dashboard')

@section('title', 'Due Report')

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

                        <i class="fas fa-hand-holding-usd me-2 text-danger"></i>

                        Due Report

                    </h2>

                    <p class="text-muted mb-0">

                        Monitor outstanding balances, overdue payments, installment dues and pending collections.

                    </p>

                </div>

                <div class="mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">

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

                    <span class="btn btn-danger shadow-sm disabled">

                        <i class="fas fa-hand-holding-usd me-1"></i>

                        Due Report

                    </span>

                </div>

            </div>

        </div>

    </div>
    {{-- ==========================================
        Summary Cards
    =========================================== --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <small class="text-muted">
                        Total Outstanding
                    </small>

                    <h3 class="fw-bold mt-2 text-danger">
                        ৳ {{ number_format($summary['total_outstanding'],2) }}
                    </h3>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <small class="text-muted">
                        Overdue Amount
                    </small>

                    <h3 class="fw-bold mt-2 text-warning">
                        ৳ {{ number_format($summary['overdue_amount'],2) }}
                    </h3>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <small class="text-muted">
                        Patients With Due
                    </small>

                    <h3 class="fw-bold mt-2 text-primary">
                        {{ $summary['patients_with_due'] }}
                    </h3>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <small class="text-muted">
                        Average Due
                    </small>

                    <h3 class="fw-bold mt-2 text-success">
                        ৳ {{ number_format($summary['average_due'],2) }}
                    </h3>

                </div>
            </div>
        </div>

    </div>

        {{-- ==========================================
        Filter Panel
    =========================================== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-filter me-2"></i>
                Filter Due Report
            </h5>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.reports.due.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Predict3D ID / Patient / Doctor"
                            value="{{ request('search') }}">
                    </div>

                    {{-- Start Date --}}
                    <div class="col-lg-2">
                        <label class="form-label fw-semibold">
                            Start Date
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-control"
                            value="{{ request('start_date') }}">
                    </div>

                    {{-- End Date --}}
                    <div class="col-lg-2">
                        <label class="form-label fw-semibold">
                            End Date
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control"
                            value="{{ request('end_date') }}">
                    </div>

                    {{-- Payment Method --}}
                    <div class="col-lg-2">
                        <label class="form-label fw-semibold">
                            Payment Method
                        </label>

                        <select
                            name="payment_method"
                            class="form-select">

                            <option value="">
                                All Methods
                            </option>

                            <option value="cash"
                                {{ request('payment_method')=='cash' ? 'selected' : '' }}>
                                Cash
                            </option>

                            <option value="card"
                                {{ request('payment_method')=='card' ? 'selected' : '' }}>
                                Card
                            </option>

                            <option value="bank_transfer"
                                {{ request('payment_method')=='bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer
                            </option>

                            <option value="mobile_banking"
                                {{ request('payment_method')=='mobile_banking' ? 'selected' : '' }}>
                                Mobile Banking
                            </option>

                        </select>
                    </div>

                    {{-- Installment --}}
                    <div class="col-lg-2">
                        <label class="form-label fw-semibold">
                            Installment
                        </label>

                        <select
                            name="is_installment"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="1"
                                {{ request('is_installment')==='1' ? 'selected' : '' }}>
                                Yes
                            </option>

                            <option value="0"
                                {{ request('is_installment')==='0' ? 'selected' : '' }}>
                                No
                            </option>

                        </select>
                    </div>

                    {{-- Due Status --}}
                    <div class="col-lg-3">
                        <label class="form-label fw-semibold">
                            Due Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="overdue"
                                {{ request('status')=='overdue' ? 'selected' : '' }}>
                                Overdue
                            </option>

                            <option value="upcoming"
                                {{ request('status')=='upcoming' ? 'selected' : '' }}>
                                Upcoming
                            </option>

                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-lg-9 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary me-2">

                            <i class="fas fa-search me-1"></i>
                            Apply Filters

                        </button>

                        <a href="{{ route('admin.reports.due.index') }}"
                           class="btn btn-outline-secondary">

                            <i class="fas fa-undo me-1"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>

        {{-- ==========================================
        Export Toolbar
    =========================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="mb-0 fw-semibold">
            Due Records
        </h5>

        <div>

            <a href="{{ route('admin.reports.due.export.excel', request()->query()) }}"
               class="btn btn-success">

                <i class="fas fa-file-excel me-1"></i>
                Export Excel

            </a>

            <a href="{{ route('admin.reports.due.export.pdf', request()->query()) }}"
               class="btn btn-danger ms-2">

                <i class="fas fa-file-pdf me-1"></i>
                Export PDF

            </a>

        </div>

    </div>

    {{-- ==========================================
        Due Report Table
    =========================================== --}}
    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Predict3D ID</th>

                            <th>Patient</th>

                            <th>Doctor</th>

                            <th>Total</th>

                            <th>Paid</th>

                            <th>Remaining</th>

                            <th>Method</th>

                            <th>Next Payment</th>

                            <th>Status</th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($payments as $payment)

                            @php

                                $paidAmount = $payment->payments->sum('amount');

                                $isOverdue = $payment->next_payment_date
                                    && \Carbon\Carbon::parse($payment->next_payment_date)->isPast();

                            @endphp

                            <tr>

                                <td>
                                    {{ $payments->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <span class="fw-semibold">
                                        {{ $payment->predict3d_id }}
                                    </span>
                                </td>

                                <td>
                                    {{ optional($payment->patient)->FullName ?? '-' }}
                                </td>

                                <td>
                                    {{ optional($payment->patient)->DoctorName ?? '-' }}
                                </td>

                                <td>
                                    ৳ {{ number_format($payment->total_amount,2) }}
                                </td>

                                <td class="text-success fw-semibold">
                                    ৳ {{ number_format($paidAmount,2) }}
                                </td>

                                <td class="text-danger fw-bold">
                                    ৳ {{ number_format($payment->remaining_amount,2) }}
                                </td>

                                <td>
                                    {{ ucwords(str_replace('_',' ',$payment->payment_method)) }}
                                </td>

                                <td>

                                    @if($payment->next_payment_date)

                                        {{ \Carbon\Carbon::parse($payment->next_payment_date)->format('d M Y') }}

                                    @else

                                        -

                                    @endif

                                </td>

                                <td>

                                    @if($isOverdue)

                                        <span class="badge bg-danger">
                                            Overdue
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            Current
                                        </span>

                                    @endif

                                </td>

                                <td class="text-center">

                                    <a href="{{ route('admin.reports.due.show', $payment->id) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="fas fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="11" class="text-center py-5">

                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>

                                    <h6 class="mb-1">
                                        No Due Records Found
                                    </h6>

                                    <small class="text-muted">
                                        Try changing the filters or search criteria.
                                    </small>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($payments->hasPages())

            <div class="card-footer bg-white">

                {{ $payments->appends(request()->query())->links() }}

            </div>

        @endif

    </div>

        {{-- ==========================================
        Charts Section
    =========================================== --}}
    <div class="row mt-4">

        {{-- Due Trend Chart --}}
        <div class="col-lg-8 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        Due Trend
                    </h5>

                </div>

                <div class="card-body">

                    <canvas id="dueTrendChart"
                            height="110">
                    </canvas>

                </div>

            </div>

        </div>

        {{-- Doctor-wise Due --}}
        <div class="col-lg-4 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-user-md me-2 text-success"></i>
                        Doctor-wise Due
                    </h5>

                </div>

                <div class="card-body">

                    <canvas id="doctorDueChart"
                            height="260">
                    </canvas>

                </div>

            </div>

        </div>

    </div>

    {{-- ==========================================
        Chart Data
    =========================================== --}}
    @php

        $dueLabels = collect($dueChart)->pluck('label');

        $dueValues = collect($dueChart)->pluck('value');

        $doctorLabels = collect($doctorChart)->keys();

        $doctorValues = collect($doctorChart)->values();

    @endphp

        {{-- ==========================================
        Statistics & Insights
    =========================================== --}}
    @php
        $overdueCount = $payments->filter(function ($payment) {
            return $payment->next_payment_date &&
                   \Carbon\Carbon::parse($payment->next_payment_date)->isPast();
        })->count();

        $currentCount = $payments->count() - $overdueCount;
    @endphp

    <div class="row">

        {{-- Total Due Records --}}
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body text-center">

                    <div class="display-6 fw-bold text-primary">
                        {{ $payments->total() }}
                    </div>

                    <div class="text-muted mt-2">
                        Total Due Records
                    </div>

                </div>

            </div>

        </div>

        {{-- Overdue --}}
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body text-center">

                    <div class="display-6 fw-bold text-danger">
                        {{ $overdueCount }}
                    </div>

                    <div class="text-muted mt-2">
                        Overdue Cases
                    </div>

                </div>

            </div>

        </div>

        {{-- Current --}}
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body text-center">

                    <div class="display-6 fw-bold text-success">
                        {{ $currentCount }}
                    </div>

                    <div class="text-muted mt-2">
                        Current Due Cases
                    </div>

                </div>

            </div>

        </div>

        {{-- Outstanding --}}
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body text-center">

                    <div class="display-6 fw-bold text-warning">
                        ৳ {{ number_format($summary['total_outstanding'], 0) }}
                    </div>

                    <div class="text-muted mt-2">
                        Outstanding Balance
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ==========================================
        Report Summary
    =========================================== --}}
    <div class="card shadow-sm border-0 mt-2">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-pie me-2 text-info"></i>

                Report Summary

            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-lg-6">

                    <h6 class="fw-bold mb-3">
                        Quick Insights
                    </h6>

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item d-flex justify-content-between">

                            <span>Total Outstanding</span>

                            <strong>
                                ৳ {{ number_format($summary['total_outstanding'],2) }}
                            </strong>

                        </li>

                        <li class="list-group-item d-flex justify-content-between">

                            <span>Overdue Amount</span>

                            <strong class="text-danger">
                                ৳ {{ number_format($summary['overdue_amount'],2) }}
                            </strong>

                        </li>

                        <li class="list-group-item d-flex justify-content-between">

                            <span>Average Due</span>

                            <strong>
                                ৳ {{ number_format($summary['average_due'],2) }}
                            </strong>

                        </li>

                    </ul>

                </div>

                <div class="col-lg-6">

                    <h6 class="fw-bold mb-3">
                        Status Legend
                    </h6>

                    <div class="mb-3">

                        <span class="badge bg-danger me-2">
                            Overdue
                        </span>

                        Payment date has already passed.

                    </div>

                    <div>

                        <span class="badge bg-success me-2">
                            Current
                        </span>

                        Payment is scheduled and not overdue.

                    </div>

                </div>

            </div>

        </div>

    </div>

    @endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Due Trend Chart
    |--------------------------------------------------------------------------
    */

    const dueTrendCtx = document.getElementById('dueTrendChart');

    if (dueTrendCtx) {

        new Chart(dueTrendCtx, {

            type: 'line',

            data: {

                labels: @json($dueLabels),

                datasets: [{
                    label: 'Outstanding Due (৳)',
                    data: @json($dueValues),
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true
                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: true
                    }

                },

                scales: {

                    y: {

                        beginAtZero: true

                    }

                }

            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Doctor-wise Due Chart
    |--------------------------------------------------------------------------
    */

    const doctorDueCtx = document.getElementById('doctorDueChart');

    if (doctorDueCtx) {

        new Chart(doctorDueCtx, {

            type: 'doughnut',

            data: {

                labels: @json($doctorLabels),

                datasets: [{

                    data: @json($doctorValues),

                    borderWidth: 1

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        position: 'bottom'

                    }

                }

            }

        });

    }

});
</script>
@endpush