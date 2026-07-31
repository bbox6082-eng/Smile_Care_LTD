@extends('layouts.dashboard')

@section('title', 'Payment Method Report')

@section('content')

<div class="container-fluid">

    {{-- ==========================================
        Page Header
    =========================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Payment Method Report
            </h2>

            <p class="text-muted mb-0">
                Analyze transactions by payment method, bank, mobile banking provider and date.
            </p>

        </div>

        <div>

            <a href="{{ route('admin.reports.payment-method.export.excel', request()->query()) }}"
               class="btn btn-success">

                <i class="fas fa-file-excel me-1"></i>

                Export Excel

            </a>

            <a href="{{ route('admin.reports.payment-method.export.pdf', request()->query()) }}"
               class="btn btn-danger">

                <i class="fas fa-file-pdf me-1"></i>

                Export PDF

            </a>

        </div>

    </div>


    {{-- ==========================================
        Summary Cards
    =========================================== --}}

    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Cash Collection
                    </small>

                    <h3 class="fw-bold text-success mt-2">

                        ৳ {{ number_format($summary['cash_total'],2) }}

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Card Collection
                    </small>

                    <h3 class="fw-bold text-primary mt-2">

                        ৳ {{ number_format($summary['card_total'],2) }}

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Bank Transfer
                    </small>

                    <h3 class="fw-bold text-warning mt-2">

                        ৳ {{ number_format($summary['bank_total'],2) }}

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Mobile Banking
                    </small>

                    <h3 class="fw-bold text-info mt-2">

                        ৳ {{ number_format($summary['mobile_total'],2) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================
        Filters
    =========================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header">

            <strong>
                Filter Report
            </strong>

        </div>

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Search

                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Patient / Doctor / Predict3D"
                            value="{{ request('search') }}">

                    </div>

                    <div class="col-md-2 mb-3">

                        <label class="form-label">

                            Payment Method

                        </label>

                        <select
                            name="payment_method"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="cash"
                                @selected(request('payment_method')=='cash')>

                                Cash

                            </option>

                            <option value="card"
                                @selected(request('payment_method')=='card')>

                                Card

                            </option>

                            <option value="bank_transfer"
                                @selected(request('payment_method')=='bank_transfer')>

                                Bank Transfer

                            </option>

                            <option value="mobile_banking"
                                @selected(request('payment_method')=='mobile_banking')>

                                Mobile Banking

                            </option>

                        </select>

                    </div>

                    <div class="col-md-2 mb-3">

                        <label class="form-label">

                            Bank

                        </label>

                        <input
                            type="text"
                            name="bank_name"
                            class="form-control"
                            value="{{ request('bank_name') }}">

                    </div>

                    <div class="col-md-2 mb-3">

                        <label class="form-label">

                            Mobile Provider

                        </label>

                        <input
                            type="text"
                            name="mobile_provider"
                            class="form-control"
                            value="{{ request('mobile_provider') }}">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Period

                        </label>

                        <select
                            name="period"
                            class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="today"
                                @selected(request('period')=='today')>

                                Today

                            </option>

                            <option value="weekly"
                                @selected(request('period')=='weekly')>

                                This Week

                            </option>

                            <option value="monthly"
                                @selected(request('period')=='monthly')>

                                This Month

                            </option>

                            <option value="yearly"
                                @selected(request('period')=='yearly')>

                                This Year

                            </option>

                        </select>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-3">

                        <label class="form-label">

                            Start Date

                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-control"
                            value="{{ request('start_date') }}">

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">

                            End Date

                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control"
                            value="{{ request('end_date') }}">

                    </div>

                    <div class="col-md-6 d-flex align-items-end">

                        <button
                            class="btn btn-primary me-2">

                            <i class="fas fa-search"></i>

                            Filter

                        </button>

                        <a
                            href="{{ route('admin.reports.payment-method.index') }}"
                            class="btn btn-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>
    {{-- ==========================================
    Transactions Table
========================================== --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <strong>
            Payment Transactions
        </strong>

        <span class="badge bg-primary">

            {{ $transactions->total() }} Transactions

        </span>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover table-striped align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="60">SL</th>

                        <th>Date</th>

                        <th>Predict3D ID</th>

                        <th>Patient</th>

                        <th>Doctor</th>

                        <th>Payment Method</th>

                        <th>Bank / Provider</th>

                        <th>Transaction ID</th>

                        <th class="text-end">Amount</th>

                        <th width="80" class="text-center">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($transactions as $transaction)

                        @php

                            $paymentPlan = $transaction->paymentPlan;
                            $patient = $paymentPlan?->patient;

                        @endphp

                        <tr>

                            <td>

                                {{ $transactions->firstItem() + $loop->index }}

                            </td>

                            <td>

                                {{ optional($transaction->payment_date)->format('d M Y') }}

                            </td>

                            <td>

                                {{ $paymentPlan?->predict3d_id ?? '-' }}

                            </td>

                            <td>

                                {{ $patient?->FullName ?? '-' }}

                            </td>

                            <td>

                                {{ $patient?->DoctorName ?? '-' }}

                            </td>

                            <td>

                                @switch($transaction->payment_method)

                                    @case('cash')

                                        <span class="badge bg-success">
                                            Cash
                                        </span>

                                        @break

                                    @case('card')

                                        <span class="badge bg-primary">
                                            Card
                                        </span>

                                        @break

                                    @case('bank_transfer')

                                        <span class="badge bg-warning text-dark">
                                            Bank Transfer
                                        </span>

                                        @break

                                    @case('mobile_banking')

                                        <span class="badge bg-info text-dark">
                                            Mobile Banking
                                        </span>

                                        @break

                                    @default

                                        <span class="badge bg-secondary">

                                            {{ ucfirst($transaction->payment_method) }}

                                        </span>

                                @endswitch

                            </td>

                            <td>

                                @if($transaction->payment_method == 'bank_transfer')

                                    {{ $transaction->bank_name ?: '-' }}

                                @elseif($transaction->payment_method == 'mobile_banking')

                                    {{ $transaction->mobile_provider ?: '-' }}

                                @else

                                    -

                                @endif

                            </td>

                            <td>

                                {{ $transaction->transaction_id ?: '-' }}

                            </td>

                            <td class="text-end fw-bold">

                                ৳ {{ number_format($transaction->amount,2) }}

                            </td>

                            <td class="text-center">

                                <a
                                    href="{{ route('admin.reports.payment-method.show',$transaction->id) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    <i class="fas fa-eye"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10" class="text-center py-5 text-muted">

                                No payment transactions found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    @if($transactions->hasPages())

        <div class="card-footer">

            {{ $transactions->links() }}

        </div>

    @endif

</div>

{{-- ==========================================
    Charts
========================================== --}}

<div class="row">

    <div class="col-lg-6 mb-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-header">

                <strong>
                    Payment Method Distribution
                </strong>

            </div>

            <div class="card-body">

                @if(count($methodChart))

                    <table class="table table-sm table-bordered align-middle">

                        <thead>

                            <tr>

                                <th>Payment Method</th>
                                <th class="text-end">Amount</th>

                            </tr>

                        </thead>

                        <tbody>

                        @foreach($methodChart as $item)

                            <tr>

                                <td>{{ $item['label'] }}</td>

                                <td class="text-end">

                                    ৳ {{ number_format($item['value'],2) }}

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                @else

                    <p class="text-muted mb-0">

                        No data available.

                    </p>

                @endif

            </div>

        </div>

    </div>

    <div class="col-lg-6 mb-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-header">

                <strong>
                    Monthly Collection Trend
                </strong>

            </div>

            <div class="card-body">

                @if(count($monthlyChart))

                    <table class="table table-sm table-bordered align-middle">

                        <thead>

                            <tr>

                                <th>Month</th>
                                <th class="text-end">Amount</th>

                            </tr>

                        </thead>

                        <tbody>

                        @foreach($monthlyChart as $item)

                            <tr>

                                <td>{{ $item['label'] }}</td>

                                <td class="text-end">

                                    ৳ {{ number_format($item['value'],2) }}

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                @else

                    <p class="text-muted mb-0">

                        No monthly data available.

                    </p>

                @endif

            </div>

        </div>

    </div>

</div>

<div class="row">

    <div class="col-lg-12 mb-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header">

                <strong>
                    Bank-wise Collection
                </strong>

            </div>

            <div class="card-body">

                @if(count($bankChart))

                    <table class="table table-bordered align-middle">

                        <thead>

                            <tr>

                                <th>Bank</th>
                                <th class="text-end">Collected Amount</th>

                            </tr>

                        </thead>

                        <tbody>

                        @foreach($bankChart as $item)

                            <tr>

                                <td>{{ $item['label'] }}</td>

                                <td class="text-end">

                                    ৳ {{ number_format($item['value'],2) }}

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                @else

                    <p class="text-muted mb-0">

                        No bank transfer data available.

                    </p>

                @endif

            </div>

        </div>

    </div>

</div>

{{-- ==========================================
    Report Summary
========================================== --}}

<div class="card border-0 shadow-sm">

    <div class="card-header">

        <strong>
            Report Summary
        </strong>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4 mb-3">

                <strong>Total Transactions</strong>

                <h4 class="mt-2">

                    {{ number_format($summary['total_transactions']) }}

                </h4>

            </div>

            <div class="col-md-4 mb-3">

                <strong>Total Cash Collection</strong>

                <h4 class="mt-2 text-success">

                    ৳ {{ number_format($summary['cash_total'],2) }}

                </h4>

            </div>

            <div class="col-md-4 mb-3">

                <strong>Total Card Collection</strong>

                <h4 class="mt-2 text-primary">

                    ৳ {{ number_format($summary['card_total'],2) }}

                </h4>

            </div>

            <div class="col-md-6 mb-3">

                <strong>Total Bank Transfer</strong>

                <h4 class="mt-2 text-warning">

                    ৳ {{ number_format($summary['bank_total'],2) }}

                </h4>

            </div>

            <div class="col-md-6 mb-3">

                <strong>Total Mobile Banking</strong>

                <h4 class="mt-2 text-info">

                    ৳ {{ number_format($summary['mobile_total'],2) }}

                </h4>

            </div>

        </div>

    </div>

</div>

</div>

@endsection