@extends('layouts.dashboard')

@section('title', 'Delivery Pending Details')

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

                        <i class="fas fa-truck me-2 text-info"></i>

                        Delivery Pending Details

                    </h3>

                    <p class="text-muted mb-0">

                        Detailed delivery status and case information.

                    </p>

                </div>

                <div class="mt-3 mt-md-0">

                    <a
                        href="{{ route('admin.reports.delivery-pending.index') }}"
                        class="btn btn-light border shadow-sm"
                    >

                        <i class="fas fa-arrow-left me-1"></i>

                        Back to Delivery Pending

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DELIVERY STATUS ALERT
    ========================================================== --}}

    <div class="alert
        @if($payment->delivery_status === 'both_pending')
            alert-danger
        @elseif($payment->delivery_status === 'upper_pending')
            alert-primary
        @elseif($payment->delivery_status === 'lower_pending')
            alert-warning
        @else
            alert-success
        @endif
        border-0 shadow-sm mb-4"
    >

        <div class="d-flex align-items-center">

            <i
                class="fas
                @if($payment->delivery_status === 'both_pending')
                    fa-exclamation-circle
                @elseif($payment->delivery_status === 'upper_pending')
                    fa-arrow-up
                @elseif($payment->delivery_status === 'lower_pending')
                    fa-arrow-down
                @else
                    fa-check-circle
                @endif
                fs-4 me-3"
            ></i>

            <div>

                <strong>

                    @if($payment->delivery_status === 'both_pending')

                        Upper & Lower Delivery Pending

                    @elseif($payment->delivery_status === 'upper_pending')

                        Upper Delivery Pending

                    @elseif($payment->delivery_status === 'lower_pending')

                        Lower Delivery Pending

                    @else

                        Delivery Completed

                    @endif

                </strong>

                <div class="small mt-1">

                    Remaining cases:

                    <strong>
                        {{ number_format($payment->remaining_cases) }}
                    </strong>

                    &nbsp;|&nbsp;

                    Delivery progress:

                    <strong>
                        {{ number_format(
                            $payment->delivery_percentage,
                            2
                        ) }}%
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CASE & PATIENT INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-id-card me-2 text-info"></i>

                Case & Patient Information

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Predict3D ID
                    </small>

                    <h5 class="fw-bold mt-1 mb-0">

                        {{ $payment->predict3d_id ?: '-' }}

                    </h5>

                </div>


                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Patient
                    </small>

                    <h5 class="fw-bold mt-1 mb-0">

                        {{ $payment->patient?->FullName ?: '-' }}

                    </h5>

                </div>


                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Doctor
                    </small>

                    <h5 class="fw-bold mt-1 mb-0">

                        {{ $payment->patient?->DoctorName ?: '-' }}

                    </h5>

                </div>


                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Phone
                    </small>

                    <h5 class="fw-bold mt-1 mb-0">

                        {{ $payment->patient?->PhoneNumber ?: '-' }}

                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DELIVERY SUMMARY
    ========================================================== --}}

    <div class="row g-4 mb-4">


        {{-- Upper --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-arrow-up me-2 text-primary"></i>

                        Upper Case Delivery

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-3 text-center">

                        <div class="col-4">

                            <small class="text-muted d-block">
                                Total
                            </small>

                            <h4 class="fw-bold mb-0">

                                {{ number_format(
                                    $payment->total_upper
                                ) }}

                            </h4>

                        </div>


                        <div class="col-4">

                            <small class="text-muted d-block">
                                Delivered
                            </small>

                            <h4 class="fw-bold text-success mb-0">

                                {{ number_format(
                                    $payment->delivered_upper
                                ) }}

                            </h4>

                        </div>


                        <div class="col-4">

                            <small class="text-muted d-block">
                                Pending
                            </small>

                            <h4 class="fw-bold text-danger mb-0">

                                {{ number_format(
                                    $payment->remaining_upper
                                ) }}

                            </h4>

                        </div>

                    </div>


                    @php

                        $upperPercentage =
                            $payment->total_upper > 0
                                ? (
                                    $payment->delivered_upper /
                                    $payment->total_upper
                                ) * 100
                                : 0;

                    @endphp


                    <div class="mt-4">

                        <div class="d-flex justify-content-between mb-1">

                            <small class="text-muted">
                                Progress
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
                            style="height: 9px;"
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

                    </div>

                </div>

            </div>

        </div>


        {{-- Lower --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-arrow-down me-2 text-warning"></i>

                        Lower Case Delivery

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-3 text-center">

                        <div class="col-4">

                            <small class="text-muted d-block">
                                Total
                            </small>

                            <h4 class="fw-bold mb-0">

                                {{ number_format(
                                    $payment->total_lower
                                ) }}

                            </h4>

                        </div>


                        <div class="col-4">

                            <small class="text-muted d-block">
                                Delivered
                            </small>

                            <h4 class="fw-bold text-success mb-0">

                                {{ number_format(
                                    $payment->delivered_lower
                                ) }}

                            </h4>

                        </div>


                        <div class="col-4">

                            <small class="text-muted d-block">
                                Pending
                            </small>

                            <h4 class="fw-bold text-danger mb-0">

                                {{ number_format(
                                    $payment->remaining_lower
                                ) }}

                            </h4>

                        </div>

                    </div>


                    @php

                        $lowerPercentage =
                            $payment->total_lower > 0
                                ? (
                                    $payment->delivered_lower /
                                    $payment->total_lower
                                ) * 100
                                : 0;

                    @endphp


                    <div class="mt-4">

                        <div class="d-flex justify-content-between mb-1">

                            <small class="text-muted">
                                Progress
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
                            style="height: 9px;"
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

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        OVERALL DELIVERY SUMMARY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-chart-line me-2 text-success"></i>

                Overall Delivery Progress

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4 text-center mb-4">

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Total Cases
                    </small>

                    <h3 class="fw-bold mb-0">

                        {{ number_format(
                            $payment->total_cases
                        ) }}

                    </h3>

                </div>


                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Delivered
                    </small>

                    <h3 class="fw-bold text-success mb-0">

                        {{ number_format(
                            $payment->delivered_cases
                        ) }}

                    </h3>

                </div>


                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Remaining
                    </small>

                    <h3 class="fw-bold text-danger mb-0">

                        {{ number_format(
                            $payment->remaining_cases
                        ) }}

                    </h3>

                </div>


                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Progress
                    </small>

                    <h3 class="fw-bold text-primary mb-0">

                        {{ number_format(
                            $payment->delivery_percentage,
                            2
                        ) }}%

                    </h3>

                </div>

            </div>


            <div class="d-flex justify-content-between mb-1">

                <small class="text-muted">
                    Overall Delivery Progress
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
                style="height: 12px;"
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

        </div>

    </div>


    {{-- =========================================================
        DELIVERY HISTORY
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-history me-2 text-info"></i>

                Delivery History

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
                                Delivery Date
                            </th>

                            <th class="text-center">
                                Upper Delivered
                            </th>

                            <th class="text-center">
                                Lower Delivered
                            </th>

                            <th class="text-end">
                                Paid Amount
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($payment->deliveries as $delivery)

                            <tr>

                                <td class="text-center">

                                    {{ $loop->iteration }}

                                </td>


                                <td>

                                    {{ $delivery->delivery_date
                                        ? \Carbon\Carbon::parse(
                                            $delivery->delivery_date
                                        )->format('d M Y')
                                        : '-' }}

                                </td>


                                <td class="text-center">

                                    <span class="badge bg-primary">

                                        {{ number_format(
                                            (int) $delivery->upper_delivered
                                        ) }}

                                    </span>

                                </td>


                                <td class="text-center">

                                    <span class="badge bg-warning text-dark">

                                        {{ number_format(
                                            (int) $delivery->lower_delivered
                                        ) }}

                                    </span>

                                </td>


                                <td class="text-end fw-semibold">

                                    {{ number_format(
                                        (float) $delivery->paid_amount,
                                        2
                                    ) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted py-4"
                                >

                                    No delivery records found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PAYMENT INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-money-bill-wave me-2 text-success"></i>

                Payment Information

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Total Amount
                    </small>

                    <h5 class="fw-bold mb-0">

                        {{ number_format(
                            (float) $payment->total_amount,
                            2
                        ) }}

                    </h5>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Remaining Amount
                    </small>

                    <h5 class="fw-bold text-danger mb-0">

                        {{ number_format(
                            (float) $payment->remaining_amount,
                            2
                        ) }}

                    </h5>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Payment Method
                    </small>

                    <h5 class="fw-bold mb-0">

                        {{ $payment->payment_method
                            ? ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $payment->payment_method
                                )
                            )
                            : '-' }}

                    </h5>

                </div>


                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Next Payment
                    </small>

                    <h5 class="fw-bold mb-0">

                        {{ $payment->next_payment_date
                            ? \Carbon\Carbon::parse(
                                $payment->next_payment_date
                            )->format('d M Y')
                            : '-' }}

                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FOOTER ACTION
    ========================================================== --}}

    <div class="d-flex justify-content-end mt-4 mb-4">

        <a
            href="{{ route('admin.reports.delivery-pending.index') }}"
            class="btn btn-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back to Delivery Pending

        </a>

    </div>

</div>

@endsection