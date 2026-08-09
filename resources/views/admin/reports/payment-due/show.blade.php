@extends('layouts.dashboard')

@section('title', 'Payment Due Details')

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

                        <i class="fas fa-wallet me-2 text-warning"></i>

                        Payment Due Details

                    </h3>

                    <p class="text-muted mb-0">

                        Detailed payment information and payment history.

                    </p>

                </div>

                <div class="mt-3 mt-md-0">

                    <a
                        href="{{ route('admin.reports.payment-due.index') }}"
                        class="btn btn-light border shadow-sm"
                    >

                        <i class="fas fa-arrow-left me-1"></i>

                        Back to Payment Due

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DUE STATUS
    ========================================================== --}}

    <div class="alert
        @if($payment->due_status === 'overdue')
            alert-danger
        @elseif($payment->due_status === 'upcoming')
            alert-primary
        @else
            alert-warning
        @endif
        border-0 shadow-sm mb-4"
    >

        <div class="d-flex align-items-center">

            <i
                class="fas
                @if($payment->due_status === 'overdue')
                    fa-exclamation-circle
                @elseif($payment->due_status === 'upcoming')
                    fa-calendar-check
                @else
                    fa-clock
                @endif
                fs-4 me-3"
            ></i>

            <div>

                <strong>

                    @if($payment->due_status === 'overdue')

                        Payment Overdue

                    @elseif($payment->due_status === 'upcoming')

                        Upcoming Payment

                    @else

                        Payment Pending

                    @endif

                </strong>

                <div class="small mt-1">

                    Outstanding amount:

                    <strong>

                        {{ number_format(
                            (float) $payment->remaining_amount,
                            2
                        ) }}

                    </strong>

                    @if($payment->next_payment_date)

                        &nbsp;|&nbsp;

                        Next payment:

                        <strong>

                            {{ \Carbon\Carbon::parse(
                                $payment->next_payment_date
                            )->format('d M Y') }}

                        </strong>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CASE IDENTIFICATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-id-card me-2 text-warning"></i>

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


    <div class="row g-4">


        {{-- =====================================================
            PAYMENT SUMMARY
        ====================================================== --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-money-bill-wave me-2 text-success"></i>

                        Payment Summary

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tbody>

                            <tr>

                                <th width="45%">
                                    Total Amount
                                </th>

                                <td class="fw-semibold">

                                    {{ number_format(
                                        (float) $payment->total_amount,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Paid Amount
                                </th>

                                <td class="text-success fw-semibold">

                                    {{ number_format(
                                        (float) $payment->total_paid,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Remaining Amount
                                </th>

                                <td class="text-danger fw-bold">

                                    {{ number_format(
                                        (float) $payment->remaining_amount,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Payment Method
                                </th>

                                <td>

                                    @if($payment->payment_method === 'cash')

                                        <span class="badge bg-success">
                                            Cash
                                        </span>

                                    @elseif($payment->payment_method === 'card')

                                        <span class="badge bg-primary">
                                            Card
                                        </span>

                                    @elseif($payment->payment_method === 'bank_transfer')

                                        <span class="badge bg-info text-dark">
                                            Bank Transfer
                                        </span>

                                    @elseif($payment->payment_method === 'mobile_banking')

                                        <span class="badge bg-warning text-dark">
                                            Mobile Banking
                                        </span>

                                    @else

                                        {{ $payment->payment_method ?: '-' }}

                                    @endif

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Payment Type
                                </th>

                                <td>

                                    @if($payment->is_installment)

                                        <span class="badge bg-warning text-dark">
                                            Installment
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Full Payment
                                        </span>

                                    @endif

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Completion
                                </th>

                                <td class="fw-semibold">

                                    {{ number_format(
                                        $payment->completion,
                                        2
                                    ) }}%

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- =====================================================
            DUE INFORMATION
        ====================================================== --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-calendar-alt me-2 text-danger"></i>

                        Due Information

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tbody>

                            <tr>

                                <th width="45%">
                                    Due Status
                                </th>

                                <td>

                                    @if($payment->due_status === 'overdue')

                                        <span class="badge bg-danger">

                                            <i class="fas fa-exclamation-circle me-1"></i>

                                            Overdue

                                        </span>

                                    @elseif($payment->due_status === 'upcoming')

                                        <span class="badge bg-primary">

                                            <i class="fas fa-calendar-check me-1"></i>

                                            Upcoming

                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">

                                            Pending

                                        </span>

                                    @endif

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Next Payment Date
                                </th>

                                <td>

                                    @if($payment->next_payment_date)

                                        {{ \Carbon\Carbon::parse(
                                            $payment->next_payment_date
                                        )->format('d M Y') }}

                                    @else

                                        -

                                    @endif

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Case Opened
                                </th>

                                <td>

                                    {{ $payment->created_at
                                        ? $payment->created_at->format('d M Y h:i A')
                                        : '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Last Updated
                                </th>

                                <td>

                                    {{ $payment->updated_at
                                        ? $payment->updated_at->format('d M Y h:i A')
                                        : '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Case ID
                                </th>

                                <td>

                                    #{{ $payment->id }}

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- =====================================================
            PAYMENT HISTORY
        ====================================================== --}}

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-receipt me-2 text-primary"></i>

                        Payment History

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
                                        Payment Date
                                    </th>

                                    <th>
                                        Payment Method
                                    </th>

                                    <th>
                                        Transaction ID
                                    </th>

                                    <th class="text-end">
                                        Amount
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($payment->payments as $paymentRecord)

                                    <tr>

                                        <td class="text-center">

                                            {{ $loop->iteration }}

                                        </td>


                                        <td>

                                            {{ $paymentRecord->payment_date
                                                ? \Carbon\Carbon::parse(
                                                    $paymentRecord->payment_date
                                                )->format('d M Y')
                                                : '-' }}

                                        </td>


                                        <td>

                                            {{ $paymentRecord->payment_method
                                                ? ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $paymentRecord->payment_method
                                                    )
                                                )
                                                : '-' }}

                                        </td>


                                        <td>

                                            {{ $paymentRecord->transaction_id ?: '-' }}

                                        </td>


                                        <td class="text-end fw-semibold">

                                            {{ number_format(
                                                (float) $paymentRecord->amount,
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

                                            No payment records found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            DELIVERY INFORMATION
        ====================================================== --}}

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-truck me-2 text-info"></i>

                        Delivery Information

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

                                    <th>
                                        Upper Delivered
                                    </th>

                                    <th>
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


                                        <td>

                                            @if($delivery->upper_delivered)

                                                <span class="badge bg-success">
                                                    Yes
                                                </span>

                                            @else

                                                <span class="badge bg-secondary">
                                                    No
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            @if($delivery->lower_delivered)

                                                <span class="badge bg-success">
                                                    Yes
                                                </span>

                                            @else

                                                <span class="badge bg-secondary">
                                                    No
                                                </span>

                                            @endif

                                        </td>


                                        <td class="text-end">

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

        </div>

    </div>


    {{-- =========================================================
        FOOTER ACTION
    ========================================================== --}}

    <div class="d-flex justify-content-end mt-4 mb-4">

        <a
            href="{{ route('admin.reports.payment-due.index') }}"
            class="btn btn-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back to Payment Due

        </a>

    </div>

</div>

@endsection