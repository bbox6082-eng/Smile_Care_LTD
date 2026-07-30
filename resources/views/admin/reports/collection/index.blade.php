@extends('layouts.dashboard')

@section('title', 'Collection Report')

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

                        <i class="fas fa-coins me-2 text-success"></i>

                        Collection Report

                    </h2>

                    <p class="text-muted mb-0">

                        Monitor all received payments, collections, payment methods,
                        collection trends and financial performance.

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

                    <span class="btn btn-success shadow-sm disabled">

                        <i class="fas fa-coins me-1"></i>

                        Collection Report

                    </span>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Total Collection

                    </small>

                    <h3 class="fw-bold mt-2 text-success">

                        ৳ {{ number_format($summary['total_collection'],2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Today's Collection

                    </small>

                    <h3 class="fw-bold mt-2 text-primary">

                        ৳ {{ number_format($summary['today_collection'],2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Monthly Collection

                    </small>

                    <h3 class="fw-bold mt-2 text-warning">

                        ৳ {{ number_format($summary['monthly_collection'],2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Total Transactions

                    </small>

                    <h3 class="fw-bold mt-2 text-danger">

                        {{ $summary['total_transactions'] }}

                    </h3>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================================================
        FILTER PANEL
    ========================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-filter me-2"></i>

                Filter Collection Report

            </h5>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.reports.collection.index') }}">

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

                    {{-- Collection Period --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">

                            Collection Period

                        </label>

                        <select
                            name="period"
                            class="form-select">

                            <option value="">

                                All Time

                            </option>

                            <option value="today"
                                {{ request('period')=='today' ? 'selected' : '' }}>

                                Today

                            </option>

                            <option value="weekly"
                                {{ request('period')=='weekly' ? 'selected' : '' }}>

                                This Week

                            </option>

                            <option value="monthly"
                                {{ request('period')=='monthly' ? 'selected' : '' }}>

                                This Month

                            </option>

                            <option value="yearly"
                                {{ request('period')=='yearly' ? 'selected' : '' }}>

                                This Year

                            </option>

                        </select>

                    </div>

                    {{-- Buttons --}}
                    <div class="col-lg-12 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary me-2">

                            <i class="fas fa-search me-1"></i>

                            Apply Filters

                        </button>

                        <a href="{{ route('admin.reports.collection.index') }}"
                           class="btn btn-outline-secondary me-2">

                            <i class="fas fa-undo me-1"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- =========================================================
        EXPORT TOOLBAR
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="mb-0 fw-semibold">

            Collection Records

        </h5>

        <div>

            <a href="{{ route('admin.reports.collection.export.excel', request()->query()) }}"
               class="btn btn-success">

                <i class="fas fa-file-excel me-1"></i>

                Export Excel

            </a>

            <a href="{{ route('admin.reports.collection.export.pdf', request()->query()) }}"
               class="btn btn-danger ms-2">

                <i class="fas fa-file-pdf me-1"></i>

                Export PDF

            </a>

        </div>

    </div>
        {{-- =========================================================
        COLLECTION TABLE
    ========================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Date</th>

                            <th>Predict3D ID</th>

                            <th>Patient</th>

                            <th>Doctor</th>

                            <th>Amount</th>

                            <th>Method</th>

                            <th>Bank / Mobile</th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($collections as $collection)

                            @php

                                $patient = optional(optional($collection->paymentPlan)->patient);

                            @endphp

                            <tr>

                                <td>

                                    {{ $collections->firstItem() + $loop->index }}

                                </td>

                                <td>

                                    {{ $collection->payment_date ? \Carbon\Carbon::parse($collection->payment_date)->format('d M Y') : '-' }}

                                </td>

                                <td>

                                    <span class="fw-semibold">

                                        {{ optional($collection->paymentPlan)->predict3d_id }}

                                    </span>

                                </td>

                                <td>

                                    {{ $patient->FullName ?? '-' }}

                                </td>

                                <td>

                                    {{ $patient->DoctorName ?? '-' }}

                                </td>

                                <td class="fw-bold text-success">

                                    ৳ {{ number_format($collection->amount,2) }}

                                </td>

                                <td>

                                    <span class="badge bg-primary">

                                        {{ ucwords(str_replace('_',' ',$collection->payment_method)) }}

                                    </span>

                                </td>

                                <td>

                                    @if($collection->payment_method == 'bank_transfer')

                                        <div>

                                            <strong>

                                                {{ $collection->bank_name ?? '-' }}

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                {{ $collection->account_name }}

                                            </small>

                                        </div>

                                    @elseif($collection->payment_method == 'mobile_banking')

                                        <div>

                                            <strong>

                                                {{ $collection->mobile_provider ?? '-' }}

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                {{ $collection->transaction_id }}

                                            </small>

                                        </div>

                                    @else

                                        -

                                    @endif

                                </td>

                                <td class="text-center">

                                    <a href="{{ route('admin.reports.collection.show', $collection->id) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        <i class="fas fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>

                                    <h6 class="mb-1">

                                        No Collection Records Found

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

        @if($collections->hasPages())

            <div class="card-footer bg-white">

                {{ $collections->appends(request()->query())->links() }}

            </div>

        @endif

    </div>
        {{-- =========================================================
        CHARTS SECTION
    ========================================================== --}}

    <div class="row mt-4">

        {{-- Collection Trend --}}
        <div class="col-lg-8 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-chart-line me-2 text-success"></i>

                        Collection Trend

                    </h5>

                </div>

                <div class="card-body">

                    <canvas id="collectionTrendChart"
                            height="110"></canvas>

                </div>

            </div>

        </div>

        {{-- Payment Method Distribution --}}
        <div class="col-lg-4 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-credit-card me-2 text-primary"></i>

                        Payment Method Distribution

                    </h5>

                </div>

                <div class="card-body">

                    <canvas id="paymentMethodChart"
                            height="260"></canvas>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================================================
        MONTHLY COLLECTION
    ========================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-bar me-2 text-warning"></i>

                Monthly Collection

            </h5>

        </div>

        <div class="card-body">

            <canvas id="monthlyCollectionChart"
                    height="110"></canvas>

        </div>

    </div>

    @php

        $trendLabels = collect($trendChart)->pluck('label');
        $trendValues = collect($trendChart)->pluck('value');

        $monthlyLabels = collect($monthlyChart)->pluck('label');
        $monthlyValues = collect($monthlyChart)->pluck('value');

        $methodLabels = collect($methodChart)->keys();
        $methodValues = collect($methodChart)->values();

    @endphp

    {{-- =========================================================
        QUICK STATISTICS
    ========================================================== --}}

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <div class="display-6 fw-bold text-success">

                        {{ $collections->total() }}

                    </div>

                    <div class="text-muted mt-2">

                        Total Transactions

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <div class="display-6 fw-bold text-primary">

                        ৳ {{ number_format($summary['today_collection'],0) }}

                    </div>

                    <div class="text-muted mt-2">

                        Today's Collection

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <div class="display-6 fw-bold text-warning">

                        ৳ {{ number_format($summary['monthly_collection'],0) }}

                    </div>

                    <div class="text-muted mt-2">

                        Monthly Collection

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <div class="display-6 fw-bold text-danger">

                        ৳ {{ number_format($summary['total_collection'],0) }}

                    </div>

                    <div class="text-muted mt-2">

                        Total Collection

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================================================
        REPORT SUMMARY
    ========================================================== --}}

    <div class="card shadow-sm border-0">

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

                        Collection Summary

                    </h6>

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item d-flex justify-content-between">

                            <span>Total Collection</span>

                            <strong>

                                ৳ {{ number_format($summary['total_collection'],2) }}

                            </strong>

                        </li>

                        <li class="list-group-item d-flex justify-content-between">

                            <span>Today's Collection</span>

                            <strong>

                                ৳ {{ number_format($summary['today_collection'],2) }}

                            </strong>

                        </li>

                        <li class="list-group-item d-flex justify-content-between">

                            <span>Monthly Collection</span>

                            <strong>

                                ৳ {{ number_format($summary['monthly_collection'],2) }}

                            </strong>

                        </li>

                        <li class="list-group-item d-flex justify-content-between">

                            <span>Total Transactions</span>

                            <strong>

                                {{ $summary['total_transactions'] }}

                            </strong>

                        </li>

                    </ul>

                </div>

                <div class="col-lg-6">

                    <h6 class="fw-bold mb-3">

                        Report Information

                    </h6>

                    <p class="mb-2">

                        • Displays all received collections.

                    </p>

                    <p class="mb-2">

                        • Supports Excel & PDF export.

                    </p>

                    <p class="mb-2">

                        • Filter by payment method and date range.

                    </p>

                    <p class="mb-0">

                        • Includes interactive charts for analysis.

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    new Chart(document.getElementById('collectionTrendChart'), {

        type: 'line',

        data: {

            labels: @json($trendLabels),

            datasets: [{

                label: 'Collection (৳)',

                data: @json($trendValues),

                borderWidth: 3,

                tension: .35,

                fill: true

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false

        }

    });

    new Chart(document.getElementById('monthlyCollectionChart'), {

        type: 'bar',

        data: {

            labels: @json($monthlyLabels),

            datasets: [{

                label: 'Monthly Collection',

                data: @json($monthlyValues),

                borderWidth: 1

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false

        }

    });

    new Chart(document.getElementById('paymentMethodChart'), {

        type: 'doughnut',

        data: {

            labels: @json($methodLabels),

            datasets: [{

                data: @json($methodValues),

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

});

</script>

@endpush