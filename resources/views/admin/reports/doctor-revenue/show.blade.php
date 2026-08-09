@extends('layouts.dashboard')

@section('title', 'Doctor Revenue Details')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h3 class="fw-bold mb-1">

                        <i class="fas fa-user-md me-2 text-info"></i>

                        Doctor Revenue Details

                    </h3>

                    <p class="text-muted mb-0">

                        Detailed payment and revenue information for
                        <strong>{{ $doctor }}</strong>.

                    </p>

                </div>

                <div class="mt-3 mt-md-0">

                    <a
                        href="{{ route(
                            'admin.reports.doctor-revenue.index'
                        ) }}"
                        class="btn btn-light border shadow-sm"
                    >

                        <i class="fas fa-arrow-left me-1"></i>

                        Back to Doctor Revenue

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DOCTOR REVENUE SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Total Revenue --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Total Revenue
                    </small>

                    <h3 class="fw-bold text-success mb-0 mt-1">

                        {{ number_format(
                            $totalRevenue,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        {{-- Transactions --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Transactions
                    </small>

                    <h3 class="fw-bold text-primary mb-0 mt-1">

                        {{ number_format(
                            $totalTransactions
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        {{-- Payment Plans --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Payment Plans
                    </small>

                    <h3 class="fw-bold text-info mb-0 mt-1">

                        {{ number_format(
                            $totalPlans
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        {{-- Pending Due --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted d-block">
                        Pending Due
                    </small>

                    <h3 class="fw-bold text-danger mb-0 mt-1">

                        {{ number_format(
                            $pendingDue,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DOCTOR INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-user-md me-2 text-info"></i>

                Doctor Information

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4 text-center">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Doctor
                    </small>

                    <h4 class="fw-bold text-info mb-0 mt-1">

                        {{ $doctor }}

                    </h4>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Average Transaction
                    </small>

                    <h4 class="fw-bold text-primary mb-0 mt-1">

                        {{ number_format(
                            $totalTransactions > 0
                                ? $totalRevenue / $totalTransactions
                                : 0,
                            2
                        ) }}

                    </h4>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Revenue / Payment Plan
                    </small>

                    <h4 class="fw-bold text-success mb-0 mt-1">

                        {{ number_format(
                            $totalPlans > 0
                                ? $totalRevenue / $totalPlans
                                : 0,
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PAYMENT PLAN DETAILS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">

                    <i class="fas fa-file-invoice-dollar me-2 text-success"></i>

                    Payment Plan Details

                </h5>


                <span class="badge bg-info">

                    {{ number_format($totalPlans) }}

                    Plans

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

                            <th class="text-end">
                                Plan Amount
                            </th>

                            <th class="text-end">
                                Collected
                            </th>

                            <th class="text-end">
                                Remaining
                            </th>

                            <th>
                                Payment Method
                            </th>

                            <th class="text-center">
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($paymentPlans as $paymentPlan)

                            @php

                                $collected = $paymentPlan->payments
                                    ->sum('amount');

                                $remaining =
                                    (float) $paymentPlan->remaining_amount;

                            @endphp


                            <tr>

                                {{-- Serial --}}

                                <td class="text-center">

                                    {{ $loop->iteration }}

                                </td>


                                {{-- Predict3D ID --}}

                                <td>

                                    <span class="fw-semibold">

                                        {{ $paymentPlan->predict3d_id ?: '-' }}

                                    </span>

                                </td>


                                {{-- Patient --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $paymentPlan->patient?->FullName ?: '-' }}

                                    </div>

                                </td>


                                {{-- Plan Amount --}}

                                <td class="text-end">

                                    {{ number_format(
                                        (float) $paymentPlan->total_amount,
                                        2
                                    ) }}

                                </td>


                                {{-- Collected --}}

                                <td class="text-end">

                                    <span class="fw-bold text-success">

                                        {{ number_format(
                                            $collected,
                                            2
                                        ) }}

                                    </span>

                                </td>


                                {{-- Remaining --}}

                                <td class="text-end">

                                    @if($remaining > 0)

                                        <span class="fw-bold text-danger">

                                            {{ number_format(
                                                $remaining,
                                                2
                                            ) }}

                                        </span>

                                    @else

                                        <span class="text-success">

                                            0.00

                                        </span>

                                    @endif

                                </td>


                                {{-- Payment Method --}}

                                <td>

                                    {{ $paymentPlan->payment_method
                                        ? ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $paymentPlan->payment_method
                                            )
                                        )
                                        : '-'
                                    }}

                                </td>


                                {{-- Status --}}

                                <td class="text-center">

                                    @if($remaining <= 0)

                                        <span class="badge bg-success">

                                            <i class="fas fa-check-circle me-1"></i>

                                            Paid

                                        </span>

                                    @elseif(
                                        $remaining <
                                        (float) $paymentPlan->total_amount
                                    )

                                        <span class="badge bg-warning text-dark">

                                            <i class="fas fa-clock me-1"></i>

                                            Partial

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            <i class="fas fa-exclamation-circle me-1"></i>

                                            Due

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        <i class="fas fa-file-invoice-dollar fa-2x mb-3"></i>

                                        <p class="mb-0 fw-semibold">

                                            No payment plans found.

                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PAYMENT TRANSACTIONS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-receipt me-2 text-primary"></i>

                Payment Transactions

            </h5>

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
                                Patient
                            </th>

                            <th>
                                Payment Date
                            </th>

                            <th class="text-end">
                                Amount
                            </th>

                            <th>
                                Method
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @php
                            $transactionNumber = 0;
                        @endphp


                        @forelse($paymentPlans as $paymentPlan)

                            @foreach($paymentPlan->payments as $payment)

                                <tr>

                                    <td class="text-center">

                                        {{ ++$transactionNumber }}

                                    </td>


                                    <td>

                                        {{ $paymentPlan->patient?->FullName ?: '-' }}

                                    </td>


                                    <td>

                                        {{ $payment->payment_date
                                            ? \Carbon\Carbon::parse(
                                                $payment->payment_date
                                            )->format('d M Y')
                                            : '-'
                                        }}

                                    </td>


                                    <td class="text-end fw-bold text-success">

                                        {{ number_format(
                                            (float) $payment->amount,
                                            2
                                        ) }}

                                    </td>


                                    <td>

                                        {{ $paymentPlan->payment_method
                                            ? ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $paymentPlan->payment_method
                                                )
                                            )
                                            : '-'
                                        }}

                                    </td>

                                </tr>

                            @endforeach

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-4 text-muted"
                                >

                                    No payment transactions found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <div class="d-flex justify-content-end mb-4">

        <a
            href="{{ route(
                'admin.reports.doctor-revenue.index'
            ) }}"
            class="btn btn-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back to Doctor Revenue

        </a>

    </div>

</div>

@endsection