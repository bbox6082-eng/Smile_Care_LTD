@extends('layouts.dashboard')

@section('title', 'Delivery Overdue Details')

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

                        <i class="fas fa-calendar-times me-2 text-danger"></i>

                        Delivery Overdue Details

                    </h3>

                    <p class="text-muted mb-0">

                        Detailed information about the overdue delivery cycle.

                    </p>

                </div>

                <div class="mt-3 mt-md-0">

                    <a
                        href="{{ route('admin.reports.delivery-overdue.index') }}"
                        class="btn btn-light border shadow-sm"
                    >

                        <i class="fas fa-arrow-left me-1"></i>

                        Back to Delivery Overdue

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        OVERDUE ALERT
    ========================================================== --}}

    <div class="alert alert-danger border-0 shadow-sm mb-4">

        <div class="d-flex align-items-center">

            <i class="fas fa-exclamation-triangle fs-3 me-3"></i>

            <div>

                <strong class="fs-5">

                    Delivery Overdue

                </strong>

                <div class="small mt-1">

                    This delivery is overdue by

                    <strong>

                        {{ number_format(
                            $payment->overdue_days
                        ) }}

                        day(s)

                    </strong>

                    .

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PATIENT & CASE INFORMATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-id-card me-2 text-info"></i>

                Patient & Case Information

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
        DELIVERY TIMELINE
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-calendar-alt me-2 text-danger"></i>

                Delivery Timeline

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4 text-center">

                {{-- Last Delivery --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Last Delivery
                    </small>

                    <h5 class="fw-bold mt-1 mb-0">

                        @if($payment->latest_delivery_date)

                            {{ \Carbon\Carbon::parse(
                                $payment->latest_delivery_date
                            )->format('d M Y') }}

                        @else

                            -

                        @endif

                    </h5>

                </div>


                {{-- Cycle --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Cycle Length
                    </small>

                    <h5 class="fw-bold mt-1 mb-0">

                        {{ number_format(
                            $payment->cycle_days
                        ) }}

                        <small class="text-muted">
                            days
                        </small>

                    </h5>

                </div>


                {{-- Due Date --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Scheduled Due Date
                    </small>

                    <h5 class="fw-bold text-danger mt-1 mb-0">

                        @if($payment->next_delivery_due_date)

                            {{ \Carbon\Carbon::parse(
                                $payment->next_delivery_due_date
                            )->format('d M Y') }}

                        @else

                            -

                        @endif

                    </h5>

                </div>


                {{-- Overdue --}}

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Overdue By
                    </small>

                    <h5 class="fw-bold text-danger mt-1 mb-0">

                        {{ number_format(
                            $payment->overdue_days
                        ) }}

                        <small class="text-muted">
                            days
                        </small>

                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DELIVERY CYCLE CALCULATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-calculator me-2 text-primary"></i>

                Delivery Cycle Calculation

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4 text-center">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Latest Upper Delivered
                    </small>

                    <h4 class="fw-bold text-primary mb-0 mt-1">

                        {{ number_format(
                            $payment->latest_upper_delivered
                        ) }}

                    </h4>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Latest Lower Delivered
                    </small>

                    <h4 class="fw-bold text-warning mb-0 mt-1">

                        {{ number_format(
                            $payment->latest_lower_delivered
                        ) }}

                    </h4>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Cases Per Cycle
                    </small>

                    <h4 class="fw-bold text-success mb-0 mt-1">

                        {{ number_format(
                            $payment->max_cases
                        ) }}

                    </h4>

                </div>

            </div>


            <hr>


            <div class="row g-4 text-center">

                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Days Per Aligner
                    </small>

                    <h4 class="fw-bold mb-0 mt-1">

                        {{ number_format(
                            $payment->days_per_aligner
                        ) }}

                    </h4>

                </div>


                <div class="col-md-6">

                    <small class="text-muted d-block">
                        Calculated Cycle
                    </small>

                    <h4 class="fw-bold text-primary mb-0 mt-1">

                        {{ number_format(
                            $payment->max_cases
                        ) }}

                        ×

                        {{ number_format(
                            $payment->days_per_aligner
                        ) }}

                        =

                        {{ number_format(
                            $payment->cycle_days
                        ) }}

                        days

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DELIVERY PROGRESS
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-truck-loading me-2 text-info"></i>

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
                                            (int) (
                                                $delivery->upper_delivered ?? 0
                                            )
                                        ) }}

                                    </span>

                                </td>


                                <td class="text-center">

                                    <span class="badge bg-warning text-dark">

                                        {{ number_format(
                                            (int) (
                                                $delivery->lower_delivered ?? 0
                                            )
                                        ) }}

                                    </span>

                                </td>


                                <td class="text-end fw-semibold">

                                    {{ number_format(
                                        (float) (
                                            $delivery->paid_amount ?? 0
                                        ),
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
            href="{{ route('admin.reports.delivery-overdue.index') }}"
            class="btn btn-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back to Delivery Overdue

        </a>

    </div>

</div>

@endsection