@extends('layouts.dashboard')

@section('title', 'Payment Details')

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="row mb-4">

        <div class="col-lg-8">

            <h2 class="fw-bold mb-1">

                <i class="fas fa-file-invoice-dollar text-primary me-2"></i>

                Payment Details

            </h2>

            <p class="text-muted mb-0">

                View payment plan, payment history and delivery information.

            </p>

        </div>

        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

            <div class="btn-group shadow-sm">

                <a href="{{ route('admin.reports.payment.index') }}"
                   class="btn btn-outline-secondary">

                    <i class="fas fa-arrow-left me-2"></i>

                    Back

                </a>

                <button onclick="window.print()"
                        class="btn btn-outline-dark">

                    <i class="fas fa-print me-2"></i>

                    Print

                </button>

                <a href="{{ route('admin.reports.payment.export.pdf', request()->query()) }}"
                   class="btn btn-danger">

                    <i class="fas fa-file-pdf me-2"></i>

                    PDF

                </a>

            </div>

        </div>

    </div>

    <!-- Summary Cards -->

    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-lg h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                TOTAL AMOUNT

                            </small>

                            <h3 class="fw-bold text-primary mt-2">

                                ৳ {{ number_format($payment->total_amount,2) }}

                            </h3>

                        </div>

                        <div>

                            <span class="bg-primary bg-opacity-10 rounded-circle p-3">

                                <i class="fas fa-wallet fa-lg text-primary"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-lg h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                TOTAL PAID

                            </small>

                            <h3 class="fw-bold text-success mt-2">

                                ৳ {{ number_format($payment->total_paid,2) }}

                            </h3>

                        </div>

                        <div>

                            <span class="bg-success bg-opacity-10 rounded-circle p-3">

                                <i class="fas fa-money-check-alt fa-lg text-success"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-lg h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                REMAINING DUE

                            </small>

                            <h3 class="fw-bold text-danger mt-2">

                                ৳ {{ number_format($payment->remaining_amount,2) }}

                            </h3>

                        </div>

                        <div>

                            <span class="bg-danger bg-opacity-10 rounded-circle p-3">

                                <i class="fas fa-exclamation-circle fa-lg text-danger"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-lg h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                COMPLETION

                            </small>

                            <h3 class="fw-bold text-info">

                                {{ $payment->completion }}%

                            </h3>

                        </div>

                        <div>

                            <span class="bg-info bg-opacity-10 rounded-circle p-3">

                                <i class="fas fa-chart-line fa-lg text-info"></i>

                            </span>

                        </div>

                    </div>

                    <div class="progress mt-3"
                         style="height:10px;">

                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                             role="progressbar"
                             style="width:{{ $payment->completion }}%">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Patient & Payment Plan -->

    <div class="row">

        <div class="col-lg-6">

            <div class="card shadow-lg border-0 mb-4">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">

                        <i class="fas fa-user me-2"></i>

                        Patient Information

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless align-middle mb-0">

                        <tr>

                            <th width="40%">

                                Predict3D ID

                            </th>

                            <td>

                                {{ optional($payment->patient)->Predict3DId ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Patient Name

                            </th>

                            <td>

                                {{ optional($payment->patient)->FullName ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Doctor Name

                            </th>

                            <td>

                                {{ optional($payment->patient)->DoctorName ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Total Paid

                            </th>

                            <td class="fw-bold text-success">

                                ৳ {{ number_format($payment->total_paid,2) }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>
                <!-- Payment Plan Information -->

        <div class="col-lg-6">

            <div class="card shadow-lg border-0 mb-4">

                <div class="card-header bg-success text-white">

                    <h5 class="mb-0">

                        <i class="fas fa-credit-card me-2"></i>

                        Payment Plan Information

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless align-middle mb-0">

                        <tr>

                            <th width="40%">
                                Payment Method
                            </th>

                            <td>

                                @php
                                    $method = strtolower($payment->payment_method);
                                @endphp

                                @switch($method)

                                    @case('cash')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-money-bill-wave me-1"></i>
                                            Cash
                                        </span>
                                        @break

                                    @case('card')
                                        <span class="badge bg-info px-3 py-2">
                                            <i class="fas fa-credit-card me-1"></i>
                                            Card
                                        </span>
                                        @break

                                    @case('bank_transfer')
                                        <span class="badge bg-primary px-3 py-2">
                                            <i class="fas fa-university me-1"></i>
                                            Bank Transfer
                                        </span>
                                        @break

                                    @case('mobile_banking')
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="fas fa-mobile-alt me-1"></i>
                                            Mobile Banking
                                        </span>
                                        @break

                                    @default

                                        <span class="badge bg-secondary px-3 py-2">

                                            {{ ucwords(str_replace('_',' ',$payment->payment_method)) }}

                                        </span>

                                @endswitch

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Payment Type

                            </th>

                            <td>

                                @if($payment->is_installment)

                                    <span class="badge bg-success px-3 py-2">

                                        Installment

                                    </span>

                                @else

                                    <span class="badge bg-dark px-3 py-2">

                                        Full Payment

                                    </span>

                                @endif

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Next Payment

                            </th>

                            <td>

                                <i class="far fa-calendar-alt text-primary me-2"></i>

                                {{ $payment->next_payment_date ? \Carbon\Carbon::parse($payment->next_payment_date)->format('d M Y') : '-' }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Created

                            </th>

                            <td>

                                <i class="far fa-clock text-success me-2"></i>

                                {{ $payment->created_at ? $payment->created_at->format('d M Y') : '-' }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <!-- Payment Statistics -->

    <div class="row mb-4">

        <div class="col-lg-12">

            <div class="card shadow-lg border-0">

                <div class="card-header bg-dark text-white">

                    <h5 class="mb-0">

                        <i class="fas fa-chart-pie me-2"></i>

                        Payment Overview

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row text-center">

                        <div class="col-md-3">

                            <h6 class="text-muted">

                                Total Amount

                            </h6>

                            <h3 class="fw-bold text-primary">

                                ৳ {{ number_format($payment->total_amount,2) }}

                            </h3>

                        </div>

                        <div class="col-md-3">

                            <h6 class="text-muted">

                                Paid Amount

                            </h6>

                            <h3 class="fw-bold text-success">

                                ৳ {{ number_format($payment->total_paid,2) }}

                            </h3>

                        </div>

                        <div class="col-md-3">

                            <h6 class="text-muted">

                                Remaining Due

                            </h6>

                            <h3 class="fw-bold text-danger">

                                ৳ {{ number_format($payment->remaining_amount,2) }}

                            </h3>

                        </div>

                        <div class="col-md-3">

                            <h6 class="text-muted">

                                Completion

                            </h6>

                            <h3 class="fw-bold text-info">

                                {{ $payment->completion }}%

                            </h3>

                        </div>

                    </div>

                    <hr>

                    <div class="progress"
                         style="height:16px;">

                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                             role="progressbar"
                             style="width: {{ $payment->completion }}%;">

                            {{ $payment->completion }}%

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
        <!-- Payment History -->

    <div class="card shadow-lg border-0 mb-4">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="fas fa-money-check-alt me-2"></i>

                Payment History

            </h5>

            <span class="badge bg-light text-primary rounded-pill px-3">

                {{ $payment->payments->count() }} Payment(s)

            </span>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                    <tr>

                        <th width="70">#</th>

                        <th>Date</th>

                        <th>Amount</th>

                        <th>Method</th>

                        <th>Reference</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($payment->payments as $index => $pay)

                        <tr>

                            <td>

                                <span class="badge bg-secondary">

                                    {{ $index+1 }}

                                </span>

                            </td>

                            <td>

                                {{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') : '-' }}

                            </td>

                            <td class="fw-bold text-success">

                                ৳ {{ number_format($pay->amount,2) }}

                            </td>

                            <td>

                                @php
                                    $method = strtolower($pay->payment_method);
                                @endphp

                                @switch($method)

                                    @case('cash')

                                        <span class="badge bg-success">

                                            Cash

                                        </span>

                                        @break

                                    @case('card')

                                        <span class="badge bg-info">

                                            Card

                                        </span>

                                        @break

                                    @case('bank_transfer')

                                        <span class="badge bg-primary">

                                            Bank Transfer

                                        </span>

                                        @break

                                    @case('mobile_banking')

                                        <span class="badge bg-warning text-dark">

                                            Mobile Banking

                                        </span>

                                        @break

                                    @default

                                        <span class="badge bg-secondary">

                                            {{ ucwords(str_replace('_',' ',$pay->payment_method)) }}

                                        </span>

                                @endswitch

                            </td>

                            <td>

                                {{ $pay->reference ?? '-' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="text-center py-5">

                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>

                                <br>

                                <strong>No payment history available.</strong>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- Delivery History -->

    <div class="card shadow-lg border-0 mb-4">

        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="fas fa-truck me-2"></i>

                Delivery History

            </h5>

            <span class="badge bg-light text-success rounded-pill px-3">

                {{ $payment->deliveries->count() }} Delivery(s)

            </span>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                    <tr>

                        <th width="70">#</th>

                        <th>Date</th>

                        <th>Amount</th>

                        <th>Status</th>

                        <th>Remarks</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($payment->deliveries as $index=>$delivery)

                        <tr>

                            <td>

                                <span class="badge bg-secondary">

                                    {{ $index+1 }}

                                </span>

                            </td>

                            <td>

                                {{ $delivery->delivery_date ? \Carbon\Carbon::parse($delivery->delivery_date)->format('d M Y') : '-' }}

                            </td>

                            <td class="fw-bold text-primary">

                                ৳ {{ number_format($delivery->amount,2) }}

                            </td>

                            <td>

                                @php
                                    $status=strtolower($delivery->status ?? '');
                                @endphp

                                @if($status=='completed')

                                    <span class="badge bg-success">

                                        Completed

                                    </span>

                                @elseif($status=='pending')

                                    <span class="badge bg-warning text-dark">

                                        Pending

                                    </span>

                                @elseif($status=='cancelled')

                                    <span class="badge bg-danger">

                                        Cancelled

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        {{ ucfirst($delivery->status ?? 'N/A') }}

                                    </span>

                                @endif

                            </td>

                            <td>

                                {{ $delivery->remarks ?? '-' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="text-center py-5">

                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>

                                <br>

                                <strong>No delivery history available.</strong>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<style>

@media print{

.btn,
.card-header,
.sidebar,
.navbar,
footer{

display:none !important;

}

.card{

box-shadow:none !important;

border:1px solid #ddd !important;

}

body{

background:#fff !important;

}

}

.table td,
.table th{

vertical-align:middle;

}

.card{

border-radius:12px;

}

.badge{

font-size:.85rem;

}

.progress{

border-radius:20px;

}

</style>

@endsection