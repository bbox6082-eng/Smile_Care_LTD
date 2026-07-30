@extends('layouts.dashboard')

@section('title', 'Payment Report')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h2 class="fw-bold mb-1">
                        <i class="fas fa-money-check-dollar me-2 text-success"></i>
                        Payment Report
                    </h2>

                    <p class="text-muted mb-0">
                        Monitor payment plans, collections, dues and financial transactions.
                    </p>

                </div>

                <div class="mt-3 mt-md-0">

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb mb-0">

                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.reports.index') }}">
                                    Reports
                                </a>
                            </li>

                            <li class="breadcrumb-item active">
                                Payment Report
                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}

    <div class="row mb-4">

        <div class="col-xl-3 col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted text-uppercase">
                                Total Revenue
                            </small>

                            <h3 class="fw-bold mt-2 mb-0">

                                ৳ {{ number_format($summary['total_revenue'] ?? 0,2) }}

                            </h3>

                        </div>

                        <div class="summary-icon bg-success">

                            <i class="fas fa-wallet"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted text-uppercase">
                                Today's Collection
                            </small>

                            <h3 class="fw-bold mt-2 mb-0">

                                ৳ {{ number_format($summary['today_collection'] ?? 0,2) }}

                            </h3>

                        </div>

                        <div class="summary-icon bg-primary">

                            <i class="fas fa-coins"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted text-uppercase">
                                Pending Due
                            </small>

                            <h3 class="fw-bold mt-2 mb-0 text-danger">

                                ৳ {{ number_format($summary['pending_due'] ?? 0,2) }}

                            </h3>

                        </div>

                        <div class="summary-icon bg-danger">

                            <i class="fas fa-triangle-exclamation"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xl-3 col-md-6 mb-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted text-uppercase">
                                Completed Payment Plans
                            </small>

                            <h3 class="fw-bold mt-2 mb-0">

                                {{ number_format($summary['completed_plans'] ?? 0) }}

                            </h3>

                        </div>

                        <div class="summary-icon bg-warning">

                            <i class="fas fa-check-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================================================
        FILTER PANEL
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="fas fa-filter me-2 text-primary"></i>

                Payment Filters

            </h5>

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.reports.payment.index') }}">

                <div class="row g-3">

                    <div class="col-xl-2 col-md-4">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from"
                            class="form-control"
                            value="{{ request('from') }}">

                    </div>

                    <div class="col-xl-2 col-md-4">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to"
                            class="form-control"
                            value="{{ request('to') }}">

                    </div>

                    <div class="col-xl-2 col-md-4">

                        <label class="form-label">
                            Payment Method
                        </label>

                        <select
                            name="payment_method"
                            class="form-select">

                            <option value="">
                                All Methods
                            </option>

                            <option value="cash"
                                {{ request('payment_method')=='cash' ? 'selected' : '' }}>
                                Cash
                            </option>

                            <option value="card"
                                {{ request('payment_method')=='card' ? 'selected' : '' }}>
                                Card
                            </option>

                            <option value="bank_transfer"
                                {{ request('payment_method')=='bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer
                            </option>

                            <option value="mobile_banking"
                                {{ request('payment_method')=='mobile_banking' ? 'selected' : '' }}>
                                Mobile Banking
                            </option>

                        </select>

                    </div>

                    <div class="col-xl-2 col-md-4">

                        <label class="form-label">
                            Payment Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="Paid"
                                {{ request('status')=='Paid' ? 'selected' : '' }}>
                                Paid
                            </option>

                            <option value="Partial"
                                {{ request('status')=='Partial' ? 'selected' : '' }}>
                                Partial
                            </option>

                            <option value="Due"
                                {{ request('status')=='Due' ? 'selected' : '' }}>
                                Due
                            </option>

                        </select>

                    </div>

                    <div class="col-xl-2 col-md-4">

                        <label class="form-label">

                            Search Patient

                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Predict3D ID / Patient Name">

                    </div>

                    <div class="col-xl-2 col-md-4 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="fas fa-search me-2"></i>

                            Apply

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- =========================================================
        EXPORT TOOLBAR
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-end flex-wrap gap-2">

                <a
                    href="{{ route('admin.reports.payment.export.excel', request()->query()) }}"
                    class="btn btn-success">

                    <i class="fas fa-file-excel me-2"></i>
                    Export Excel
                </a>

                <a href="{{ route('admin.reports.payment.export.pdf', request()->query()) }}"
                    class="btn btn-danger">

                        <i class="fas fa-file-pdf"></i>

                        Export PDF

                </a>

                <button
                    type="button"
                    onclick="printReport()"
                    class="btn btn-dark">

                    <i class="fas fa-print me-2"></i>

                    Print Report

                </button>

            </div>

        </div>

    </div>

    {{-- =========================================================
    PAYMENT REPORT TABLE
========================================================== --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">

        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <h5 class="mb-0">

                <i class="fas fa-table me-2 text-success"></i>

                Payment Transactions

            </h5>

            <span class="badge bg-primary fs-6">

                Total Records :
                {{ $payments->total() }}

            </span>

        </div>

    </div>

    <div class="table-responsive">

        <table class="table table-hover table-bordered align-middle mb-0">

            <thead class="table-light">

                <tr>

                    <th width="60">#</th>
                    <th>Predict3D ID</th>
                    <th>Patient Name</th>
                    <th>Doctor</th>
                    <th>Total Amount</th>
                    <th>Paid</th>
                    <th>Due</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-center">Action</th>

                </tr>

            </thead>

            <tbody>

                @forelse($payments as $payment)

                @php

                    $paidAmount = $payment->payments->sum('amount');

                @endphp

                <tr>

                    <td>

                        {{ $payments->firstItem() + $loop->index }}

                    </td>

                    <td>

                        {{ $payment->predict3d_id }}

                    </td>

                    <td>

                        {{ optional($payment->patient)->FullName ?? '-' }}

                    </td>

                    <td>

                        {{ optional($payment->patient)->DoctorName ?? '-' }}

                    </td>

                    <td class="fw-bold">

                        ৳ {{ number_format($payment->total_amount,2) }}

                    </td>

                    <td class="text-success fw-bold">

                        ৳ {{ number_format($paidAmount,2) }}

                    </td>

                    <td class="text-danger fw-bold">

                        ৳ {{ number_format($payment->remaining_amount,2) }}

                    </td>

                    <td>

                        <span class="badge bg-info">

                            {{ ucwords(str_replace('_',' ',$payment->payment_method)) }}

                        </span>

                    </td>

                    <td>

                        @if($payment->remaining_amount<=0)

                            <span class="badge bg-success">

                                Paid

                            </span>

                        @elseif($paidAmount>0)

                            <span class="badge bg-warning text-dark">

                                Partial

                            </span>

                        @else

                            <span class="badge bg-danger">

                                Due

                            </span>

                        @endif

                    </td>

                    <td>

                        {{ optional($payment->created_at)->format('d M Y') }}

                    </td>

                    <td class="text-center">

                        <button
                            class="btn btn-sm btn-outline-primary">

                            <i class="fas fa-eye"></i>

                        </button>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="11" class="text-center py-5">

                        <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>

                        <h5 class="mt-3">

                            No Payment Records Found

                        </h5>

                        <p class="text-muted mb-0">

                            No records match your selected filters.

                        </p>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if($payments->hasPages())

    <div class="card-footer bg-white">

        <div class="d-flex justify-content-end">

            {{ $payments->links() }}

        </div>

    </div>

    @endif

</div>

{{-- =========================================================
    ANALYTICS SECTION
========================================================== --}}

<div class="row">

    <div class="col-lg-8 mb-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="fas fa-chart-line me-2 text-primary"></i>

                    Monthly Revenue Trend

                </h5>

            </div>

            <div class="card-body">

                <canvas
                    id="monthlyRevenueChart"
                    height="110">
                </canvas>

            </div>

        </div>

    </div>

    <div class="col-lg-4 mb-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="fas fa-chart-pie me-2 text-success"></i>

                    Payment Method Distribution

                </h5>

            </div>

            <div class="card-body">

                <canvas
                    id="paymentMethodChart"
                    height="220">
                </canvas>

            </div>

        </div>

    </div>

</div>

<div class="row">

    <div class="col-md-4 mb-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center">

                <i class="fas fa-money-bill-wave fa-2x text-success mb-3"></i>

                <h6>Total Revenue</h6>

                <h3>

                    ৳ {{ number_format($summary['total_revenue'] ?? 0,2) }}

                </h3>

            </div>

        </div>

    </div>

    <div class="col-md-4 mb-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center">

                <i class="fas fa-hourglass-half fa-2x text-warning mb-3"></i>

                <h6>Total Due</h6>

                <h3>

                    ৳ {{ number_format($summary['pending_due'] ?? 0,2) }}

                </h3>

            </div>

        </div>

    </div>

    <div class="col-md-4 mb-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center">

                <i class="fas fa-check-circle fa-2x text-primary mb-3"></i>

                <h6>Completed Payment Plans</h6>

                <h3>

                    {{ number_format($summary['completed_plans'] ?? 0) }}

                </h3>

            </div>

        </div>

    </div>

</div>

@endsection

@push('styles')

<style>

.summary-icon{
    width:60px;
    height:60px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:22px;
}

.card{
    border-radius:12px;
}

.card-header{
    font-weight:600;
}

.table thead th{
    white-space:nowrap;
    font-size:14px;
    vertical-align:middle;
}

.table tbody td{
    font-size:14px;
    vertical-align:middle;
}

.badge{
    font-size:.80rem;
    padding:.45rem .70rem;
}

.form-control,
.form-select{
    min-height:44px;
}

.btn{
    border-radius:8px;
}

canvas{
    width:100% !important;
}

.table-responsive{
    overflow-x:auto;
}

@media (max-width:768px){

    h2{
        font-size:1.5rem;
    }

    .summary-icon{
        width:45px;
        height:45px;
        font-size:18px;
    }

}

@media print{

    body{
        background:#fff !important;
    }

    .btn,
    .breadcrumb,
    form,
    .card-header{
        display:none !important;
    }

    .card{
        border:1px solid #ddd !important;
        box-shadow:none !important;
    }

}

</style>

@endpush



@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

function printReport()
{
    window.print();
}

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Monthly Revenue Chart
    |--------------------------------------------------------------------------
    */

    const revenueCanvas = document.getElementById('monthlyRevenueChart');

    if(revenueCanvas){

        new Chart(revenueCanvas,{

            type:'line',

            data:{

                labels:[
                            'Jan',
                            'Feb',
                            'Mar',
                            'Apr',
                            'May',
                            'Jun',
                            'Jul',
                            'Aug',
                            'Sep',
                            'Oct',
                            'Nov',
                            'Dec'
                        ],

                        datasets:[{

                            label:'Monthly Revenue',

                            data:@json($revenueChart),

                            borderWidth:3,

                            fill:false,

                            tension:.35

                        }],

            },

            options:{

                responsive:true,

                maintainAspectRatio:false,

                plugins:{

                    legend:{
                        display:true
                    }

                }

            }

        });

    }



    /*
    |--------------------------------------------------------------------------
    | Payment Method Chart
    |--------------------------------------------------------------------------
    */

    const paymentCanvas = document.getElementById('paymentMethodChart');

    if(paymentCanvas){

        new Chart(paymentCanvas,{

            type:'doughnut',

            data:{

                labels:[
                    'Cash',
                    'Card',
                    'Bank Transfer',
                    'Mobile Banking'
                ],

                datasets:[{

                            data:@json(array_values($methodChart)),

                            backgroundColor:[
                                '#198754',
                                '#0d6efd',
                                '#ffc107',
                                '#dc3545'
                            ],

                            borderWidth:1

                        }]

            },

            options:{

                responsive:true,

                maintainAspectRatio:false,

                plugins:{

                    legend:{
                        position:'bottom'
                    }

                }

            }

        });

    }

});

</script>

@endpush