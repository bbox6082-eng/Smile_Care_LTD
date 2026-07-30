@extends('layouts.dashboard')

@section('title', 'Collection Details')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Collection Details
            </h3>

            <p class="text-muted mb-0">
                View collection transaction details.
            </p>
        </div>

        <a href="{{ route('admin.reports.collection.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back

        </a>

    </div>

    @php
        $paymentPlan = $collection->paymentPlan;
        $patient = $paymentPlan ? $paymentPlan->patient : null;
    @endphp

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
                            <th width="40%">Predict3D ID</th>
                            <td>{{ $paymentPlan->predict3d_id ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Patient Name</th>
                            <td>{{ $patient->FullName ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Doctor Name</th>
                            <td>{{ $patient->DoctorName ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Payment Method</th>
                            <td>

                                <span class="badge bg-primary">

                                    {{ ucwords(str_replace('_',' ',$collection->payment_method)) }}

                                </span>

                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

        {{-- Collection Summary --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        Collection Summary

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tr>
                            <th width="40%">Collection Amount</th>

                            <td class="text-success fw-bold">

                                ৳ {{ number_format($collection->amount,2) }}

                            </td>
                        </tr>

                        <tr>
                            <th>Collection Date</th>

                            <td>

                                {{ $collection->payment_date ? $collection->payment_date->format('d M Y') : '-' }}

                            </td>
                        </tr>

                        <tr>
                            <th>Recorded By</th>

                            <td>

                                {{ optional($collection->creator)->name ?? 'System' }}

                            </td>
                        </tr>

                        <tr>
                            <th>Created At</th>

                            <td>

                                {{ $collection->created_at ? $collection->created_at->format('d M Y h:i A') : '-' }}

                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

        {{-- Payment Information --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        Payment Information

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tr>
                            <th width="40%">Payment Method</th>

                            <td>

                                {{ ucwords(str_replace('_',' ',$collection->payment_method)) }}

                            </td>
                        </tr>

                        <tr>
                            <th>Transaction ID</th>

                            <td>

                                {{ $collection->transaction_id ?: '-' }}

                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

        {{-- Banking Information --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        Banking Information

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        @if($collection->payment_method == 'bank_transfer')

                            <tr>
                                <th width="40%">Bank Name</th>
                                <td>{{ $collection->bank_name ?: '-' }}</td>
                            </tr>

                            <tr>
                                <th>Branch Name</th>
                                <td>{{ $collection->branch_name ?: '-' }}</td>
                            </tr>

                            <tr>
                                <th>Account Name</th>
                                <td>{{ $collection->account_name ?: '-' }}</td>
                            </tr>

                            <tr>
                                <th>Account Number</th>
                                <td>{{ $collection->account_number ?: '-' }}</td>
                            </tr>

                        @elseif($collection->payment_method == 'mobile_banking')

                            <tr>
                                <th width="40%">Provider</th>
                                <td>{{ $collection->mobile_provider ?: '-' }}</td>
                            </tr>

                            <tr>
                                <th>Transaction ID</th>
                                <td>{{ $collection->transaction_id ?: '-' }}</td>
                            </tr>

                        @else

                            <tr>
                                <td colspan="2" class="text-center text-muted">

                                    No additional payment information.

                                </td>
                            </tr>

                        @endif

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection