@extends('layouts.dashboard')

@section('title', 'Payment Transaction Details')

@section('content')

<div class="container-fluid">

    {{-- ==========================================
        Page Header
    =========================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Payment Transaction Details
            </h2>

            <p class="text-muted mb-0">
                Complete information about the selected payment transaction.
            </p>

        </div>

        <a href="{{ route('admin.reports.payment-method.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back to Report

        </a>

    </div>

    @php

        $paymentPlan = $transaction->paymentPlan;
        $patient = $paymentPlan?->patient;

    @endphp

    <div class="row">

        {{-- ==========================================
            Payment Information
        =========================================== --}}

        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header">

                    <strong>
                        Payment Information
                    </strong>

                </div>

                <div class="card-body">

                    <table class="table table-borderless">

                        <tr>
                            <th width="40%">Payment Date</th>
                            <td>{{ optional($transaction->payment_date)->format('d M Y') }}</td>
                        </tr>

                        <tr>
                            <th>Payment Method</th>
                            <td>

                                <span class="badge bg-primary">

                                    {{ ucwords(str_replace('_',' ',$transaction->payment_method)) }}

                                </span>

                            </td>
                        </tr>

                        <tr>
                            <th>Amount</th>
                            <td>

                                <strong class="text-success">

                                    ৳ {{ number_format($transaction->amount,2) }}

                                </strong>

                            </td>
                        </tr>

                        <tr>
                            <th>Transaction ID</th>
                            <td>

                                {{ $transaction->transaction_id ?: '-' }}

                            </td>
                        </tr>

                        <tr>
                            <th>Collected By</th>
                            <td>

                                {{ $transaction->creator?->name ?? '-' }}

                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

        {{-- ==========================================
            Patient Information
        =========================================== --}}

        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header">

                    <strong>
                        Patient Information
                    </strong>

                </div>

                <div class="card-body">

                    <table class="table table-borderless">

                        <tr>
                            <th width="40%">Predict3D ID</th>
                            <td>{{ $paymentPlan?->predict3d_id ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Patient Name</th>
                            <td>{{ $patient?->FullName ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Doctor</th>
                            <td>{{ $patient?->DoctorName ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Phone</th>
                            <td>{{ $patient?->MobileNumber ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>{{ $patient?->Email ?? '-' }}</td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>

    {{-- ==========================================
        Payment Method Details
    =========================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header">

            <strong>
                Payment Method Details
            </strong>

        </div>

        <div class="card-body">

            @if($transaction->payment_method == 'bank_transfer')

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label class="fw-bold">
                            Bank Name
                        </label>

                        <div>

                            {{ $transaction->bank_name ?: '-' }}

                        </div>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="fw-bold">
                            Branch Name
                        </label>

                        <div>

                            {{ $transaction->branch_name ?: '-' }}

                        </div>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="fw-bold">
                            Account Name
                        </label>

                        <div>

                            {{ $transaction->account_name ?: '-' }}

                        </div>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="fw-bold">
                            Account Number
                        </label>

                        <div>

                            {{ $transaction->account_number ?: '-' }}

                        </div>

                    </div>

                </div>

            @elseif($transaction->payment_method == 'mobile_banking')

                <div class="row">

                    <div class="col-md-6">

                        <label class="fw-bold">
                            Mobile Banking Provider
                        </label>

                        <div>

                            {{ $transaction->mobile_provider ?: '-' }}

                        </div>

                    </div>

                    <div class="col-md-6">

                        <label class="fw-bold">
                            Transaction ID
                        </label>

                        <div>

                            {{ $transaction->transaction_id ?: '-' }}

                        </div>

                    </div>

                </div>

            @else

                <div class="alert alert-info mb-0">

                    No additional payment method information is available for this transaction.

                </div>

            @endif

        </div>

    </div>

</div>

@endsection