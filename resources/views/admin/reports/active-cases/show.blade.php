@extends('layouts.dashboard')

@section('title', 'Active Case Details')

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

                        <i class="fas fa-briefcase-medical me-2 text-primary"></i>

                        Active Case Details

                    </h3>

                    <p class="text-muted mb-0">

                        Detailed information about the active treatment case.

                    </p>

                </div>

                <div class="mt-3 mt-md-0">

                    <a
                        href="{{ route('admin.reports.active-cases.index') }}"
                        class="btn btn-light border shadow-sm"
                    >

                        <i class="fas fa-arrow-left me-1"></i>

                        Back to Active Cases

                    </a>

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

                <i class="fas fa-id-card me-2 text-primary"></i>

                Case Identification

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Predict3D ID
                    </small>

                    <h5 class="fw-bold mt-1 mb-0">

                        {{ $case->predict3d_id ?: '-' }}

                    </h5>

                </div>


                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Patient
                    </small>

                    <h5 class="fw-bold mt-1 mb-0">

                        {{ $case->patient?->FullName ?: '-' }}

                    </h5>

                </div>


                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Doctor
                    </small>

                    <h5 class="fw-bold mt-1 mb-0">

                        {{ $case->patient?->DoctorName ?: '-' }}

                    </h5>

                </div>


                <div class="col-lg-3 col-md-6">

                    <small class="text-muted d-block">
                        Case Status
                    </small>

                    <div class="mt-1">

                        <span class="badge bg-success">
                            Active
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">


        {{-- =====================================================
            PATIENT INFORMATION
        ====================================================== --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-user me-2 text-primary"></i>

                        Patient Information

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tbody>

                            <tr>

                                <th width="40%">
                                    Patient Name
                                </th>

                                <td>
                                    {{ $case->patient?->FullName ?: '-' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Predict3D ID
                                </th>

                                <td>
                                    {{ $case->predict3d_id ?: '-' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Doctor
                                </th>

                                <td>
                                    {{ $case->patient?->DoctorName ?: '-' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Phone
                                </th>

                                <td>
                                    {{ $case->patient?->PhoneNumber ?: '-' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Gender
                                </th>

                                <td>
                                    {{ $case->patient?->Gender ?: '-' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Chamber
                                </th>

                                <td>
                                    {{ $case->patient?->ChamberName ?: '-' }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- =====================================================
            PAYMENT INFORMATION
        ====================================================== --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-money-bill-wave me-2 text-success"></i>

                        Payment Information

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tbody>

                            <tr>

                                <th width="45%">
                                    Payment Method
                                </th>

                                <td>

                                    @if($case->payment_method === 'cash')

                                        <span class="badge bg-success">
                                            Cash
                                        </span>

                                    @elseif($case->payment_method === 'card')

                                        <span class="badge bg-primary">
                                            Card
                                        </span>

                                    @elseif($case->payment_method === 'bank_transfer')

                                        <span class="badge bg-info text-dark">
                                            Bank Transfer
                                        </span>

                                    @elseif($case->payment_method === 'mobile_banking')

                                        <span class="badge bg-warning text-dark">
                                            Mobile Banking
                                        </span>

                                    @else

                                        {{ $case->payment_method ?: '-' }}

                                    @endif

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Total Amount
                                </th>

                                <td class="fw-semibold">

                                    {{ number_format(
                                        (float) $case->total_amount,
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
                                        max(
                                            0,
                                            (float) $case->total_amount -
                                            (float) $case->remaining_amount
                                        ),
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Remaining Amount
                                </th>

                                <td
                                    class="{{ $case->remaining_amount > 0
                                        ? 'text-danger fw-semibold'
                                        : 'text-success fw-semibold' }}"
                                >

                                    {{ number_format(
                                        (float) $case->remaining_amount,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Payment Type
                                </th>

                                <td>

                                    @if($case->is_installment)

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
                                    Next Payment Date
                                </th>

                                <td>

                                    @if($case->next_payment_date)

                                        {{ $case->next_payment_date->format('d M Y') }}

                                    @else

                                        -

                                    @endif

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

                                @forelse($case->payments as $payment)

                                    <tr>

                                        <td class="text-center">

                                            {{ $loop->iteration }}

                                        </td>


                                        <td>

                                            {{ $payment->payment_date
                                                ? $payment->payment_date->format('d M Y')
                                                : '-' }}

                                        </td>


                                        <td>

                                            {{ $payment->payment_method
                                                ? ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $payment->payment_method
                                                    )
                                                )
                                                : '-' }}

                                        </td>


                                        <td>

                                            {{ $payment->transaction_id ?: '-' }}

                                        </td>


                                        <td class="text-end fw-semibold">

                                            {{ number_format(
                                                (float) $payment->amount,
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

                                @forelse($case->deliveries as $delivery)

                                    <tr>

                                        <td class="text-center">

                                            {{ $loop->iteration }}

                                        </td>


                                        <td>

                                            {{ $delivery->delivery_date
                                                ? $delivery->delivery_date->format('d M Y')
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


        {{-- =====================================================
            SYSTEM INFORMATION
        ====================================================== --}}

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-database me-2 text-secondary"></i>

                        Case System Information

                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-lg-3 col-md-6">

                            <small class="text-muted d-block">
                                Case Opened
                            </small>

                            <strong>

                                {{ $case->created_at
                                    ? $case->created_at->format('d M Y h:i A')
                                    : '-' }}

                            </strong>

                        </div>


                        <div class="col-lg-3 col-md-6">

                            <small class="text-muted d-block">
                                Last Updated
                            </small>

                            <strong>

                                {{ $case->updated_at
                                    ? $case->updated_at->format('d M Y h:i A')
                                    : '-' }}

                            </strong>

                        </div>


                        <div class="col-lg-3 col-md-6">

                            <small class="text-muted d-block">
                                Case Closed
                            </small>

                            <span class="badge bg-success">
                                No
                            </span>

                        </div>


                        <div class="col-lg-3 col-md-6">

                            <small class="text-muted d-block">
                                Case ID
                            </small>

                            <strong>

                                #{{ $case->id }}

                            </strong>

                        </div>

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
            href="{{ route('admin.reports.active-cases.index') }}"
            class="btn btn-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back to Active Cases

        </a>

    </div>

</div>

@endsection