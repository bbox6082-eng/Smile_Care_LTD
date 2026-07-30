@extends('layouts.dashboard')

@section('title', 'Due Details')

@section('content')

<div class="container-fluid">

    {{-- ==========================================
        Page Header
    =========================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Due Details
            </h3>

            <p class="text-muted mb-0">
                Payment plan information and outstanding balance.
            </p>

        </div>

        <a href="{{ route('admin.reports.due.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back

        </a>

    </div>

    <div class="row">

        {{-- Patient Information --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        Patient Information

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tr>

                            <th width="35%">
                                Predict3D ID
                            </th>

                            <td>
                                {{ $payment->predict3d_id }}
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
                                Doctor
                            </th>

                            <td>
                                {{ optional($payment->patient)->DoctorName ?? '-' }}
                            </td>

                        </tr>

                        <tr>

                            <th>
                                Payment Method
                            </th>

                            <td>

                                {{ ucwords(str_replace('_',' ',$payment->payment_method)) }}

                            </td>

                        </tr>

                        <tr>

                            <th>
                                Installment
                            </th>

                            <td>

                                @if($payment->is_installment)

                                    <span class="badge bg-success">

                                        Yes

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        No

                                    </span>

                                @endif

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

        {{-- Payment Summary --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        Payment Summary

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tr>

                            <th width="40%">
                                Total Amount
                            </th>

                            <td>

                                ৳ {{ number_format($payment->total_amount,2) }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Paid Amount

                            </th>

                            <td class="text-success fw-bold">

                                ৳ {{ number_format($payment->total_paid,2) }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Remaining Amount

                            </th>

                            <td class="text-danger fw-bold">

                                ৳ {{ number_format($payment->remaining_amount,2) }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Next Payment

                            </th>

                            <td>

                                @if($payment->next_payment_date)

                                    {{ \Carbon\Carbon::parse($payment->next_payment_date)->format('d M Y') }}

                                @else

                                    -

                                @endif

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Completion

                            </th>

                            <td>

                                {{ $payment->completion }}%

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

                {{-- Progress --}}
        <div class="col-12 mb-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">
                        Payment Progress
                    </h5>

                </div>

                <div class="card-body">

                    <div class="progress" style="height:25px;">

                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width: {{ $payment->completion }}%;"
                             aria-valuenow="{{ $payment->completion }}"
                             aria-valuemin="0"
                             aria-valuemax="100">

                            {{ $payment->completion }}%

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Payment History --}}
        <div class="col-lg-8 mb-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">
                        Payment History
                    </h5>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>#</th>

                                    <th>Date</th>

                                    <th>Method</th>

                                    <th class="text-end">
                                        Amount
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($payment->payments as $history)

                                    <tr>

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>

                                            {{ optional($history->created_at)->format('d M Y') }}

                                        </td>

                                        <td>

                                            {{ ucwords(str_replace('_',' ',$history->payment_method ?? '-')) }}

                                        </td>

                                        <td class="text-end text-success fw-bold">

                                            ৳ {{ number_format($history->amount,2) }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="text-center py-4">

                                            No payment history available.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        {{-- Due Status --}}
        <div class="col-lg-4 mb-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        Due Status

                    </h5>

                </div>

                <div class="card-body">

                    @php

                        $isOverdue = $payment->next_payment_date &&
                            \Carbon\Carbon::parse($payment->next_payment_date)->isPast();

                    @endphp

                    <div class="mb-4">

                        @if($isOverdue)

                            <span class="badge bg-danger fs-6">

                                Overdue

                            </span>

                        @else

                            <span class="badge bg-success fs-6">

                                Current

                            </span>

                        @endif

                    </div>

                    <table class="table table-borderless">

                        <tr>

                            <th>
                                Remaining Due
                            </th>

                            <td class="text-end text-danger fw-bold">

                                ৳ {{ number_format($payment->remaining_amount,2) }}

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Completion

                            </th>

                            <td class="text-end">

                                {{ $payment->completion }}%

                            </td>

                        </tr>

                        <tr>

                            <th>

                                Next Payment

                            </th>

                            <td class="text-end">

                                @if($payment->next_payment_date)

                                    {{ \Carbon\Carbon::parse($payment->next_payment_date)->format('d M Y') }}

                                @else

                                    -

                                @endif

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

        {{-- Delivery Information --}}
        @if($payment->deliveries && $payment->deliveries->count())

            <div class="col-12 mb-4">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <h5 class="mb-0 fw-semibold">

                            Delivery Information

                        </h5>

                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th>#</th>

                                        <th>Delivery Date</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($payment->deliveries as $delivery)

                                        <tr>

                                            <td>

                                                {{ $loop->iteration }}

                                            </td>

                                            <td>

                                                {{ optional($delivery->created_at)->format('d M Y') }}

                                            </td>

                                            <td>

                                                {{ ucfirst($delivery->status ?? '-') }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection