@extends('layouts.dashboard')

@section('title', 'Case Status - SmileCare')

@section('content')
<style>
    .clickable-row { cursor: pointer; }
    /* Stronger full-row reminder shades for quick scanning */
    .table tbody tr.table-reminder-yellow > td { background-color: #ffefb8 !important; }
    .table tbody tr.table-reminder-red > td { background-color: #ffcfd2 !important; }
    .table tbody tr.table-reminder-critical > td { background-color: #ffb7bf !important; }
    .table tbody tr.table-reminder-closed > td {
        background-color: #dcfce7 !important; /* light green */
        color: #166534 !important;
    }
    .table tbody tr.table-reminder-yellow > td:first-child,
    .table tbody tr.table-reminder-red > td:first-child,
    .table tbody tr.table-reminder-critical > td:first-child,
    .table tbody tr.table-reminder-closed > td:first-child {
        box-shadow: inset 6px 0 0 0 rgba(0, 0, 0, 0.28);
    }
    .table tbody tr.table-reminder-yellow > td:first-child { box-shadow: inset 6px 0 0 0 #b88700; }
    .table tbody tr.table-reminder-red > td:first-child { box-shadow: inset 6px 0 0 0 #dc2626; }
    .table tbody tr.table-reminder-critical > td:first-child { box-shadow: inset 6px 0 0 0 #991b1b; }
    .table tbody tr.table-reminder-closed > td:first-child { box-shadow: inset 6px 0 0 0 #16a34a; }
</style>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title">
        <i class="fas fa-credit-card me-3"></i>
        <h1>Case Status</h1>
    </div>

        <a href="{{ route('admin.payments.export.filtered', request()->query()) }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel me-2"></i>Download Excel
        </a>
        <a href="{{ route('admin.payments.plan.index') }}" class="btn btn-primary">
            <i class="fas fa-file-invoice-dollar me-2"></i>Record Payment by 3D Predict ID
        </a>
    </div>
</div>

<div class="card mb-4 border-primary">
  <div class="card-body py-3">
    <form action="{{ route('admin.payments.settings.days') }}" method="POST" class="d-flex align-items-center justify-content-between flex-wrap gap-3" onsubmit="return confirm('Are you sure you want to change the days per aligner? This will recalculate all delivery reminders.')">
      @csrf
      <div>
        <h6 class="mb-0 text-primary"><i class="fas fa-cog me-2"></i>Global Calculation Setting</h6>
        <small class="text-muted">Set the default days per aligner used for next delivery reminder calculations.</small>
      </div>
      <div class="input-group input-group-sm" style="width: 250px;">
        <span class="input-group-text bg-light fw-bold">Days per aligner:</span>
        <input type="number" name="days" class="form-control text-center fw-bold" value="{{ cache('days_per_aligner', 15) }}" min="1" required>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
      </div>
    </form>
  </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Cases</h5>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="filter_region" class="form-label mb-1">Region</label>
                <select id="filter_region" class="form-select">
                    <option value="">All regions</option>
                    @foreach($regions as $r)
                        <option value="{{ $r->id }}" {{ (string)($filterRegionId ?? '') === (string)$r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter_area" class="form-label mb-1">Area</label>
                <select id="filter_area" class="form-select" {{ !$filterRegionId ? 'disabled' : '' }}>
                    <option value="">All areas</option>
                    @foreach($areas as $a)
                        <option value="{{ $a->id }}" {{ (string)($filterAreaId ?? '') === (string)$a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter_territory" class="form-label mb-1">Territory</label>
                <select id="filter_territory" class="form-select" {{ !$filterAreaId ? 'disabled' : '' }}>
                    <option value="">All territories</option>
                    @foreach($territories as $t)
                        <option value="{{ $t->id }}" {{ (string)($filterTerritoryId ?? '') === (string)$t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter_doctor" class="form-label mb-1">Doctor Name</label>
                <select id="filter_doctor" class="form-select">
                    <option value="">All Doctors</option>
                    @foreach($doctorOptions as $doc)
                        <option value="{{ $doc }}" {{ ($filterDoctorName ?? '') === $doc ? 'selected' : '' }}>{{ $doc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter_mr" class="form-label mb-1">Marketing Rep</label>
                <select id="filter_mr" class="form-select">
                    <option value="">All Marketing Reps</option>
                    @foreach($mrOptions as $mr)
                        <option value="{{ $mr }}" {{ ($filterMrName ?? '') === $mr ? 'selected' : '' }}>{{ $mr }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter_status" class="form-label mb-1">Status</label>
                <select id="filter_status" class="form-select">
                    <option value="All" {{ ($filterStatus ?? 'All') === 'All' ? 'selected' : '' }}>All Cases</option>
                    <option value="Active" {{ ($filterStatus ?? '') === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Completed" {{ ($filterStatus ?? '') === 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="filter_from" class="form-label mb-1">From Date</label>
                <input type="date" id="filter_from" class="form-control" value="{{ $filterFromDate ?? '' }}">
            </div>
            <div class="col-md-2">
                <label for="filter_to" class="form-label mb-1">To Date</label>
                <input type="date" id="filter_to" class="form-control" value="{{ $filterToDate ?? '' }}">
            </div>
            <div class="col-md-2 d-grid">
                <button type="button" id="btn_apply_filters" class="btn btn-primary">Apply Filters</button>
            </div>
        </div>
        @if(request()->except('page'))
            <div class="mt-3">
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary">Clear all filters</a>
            </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>All Cases</h5>
    </div>
    <div class="card-body">
        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Total Amount</th>
                                <th>Total Paid Amount</th>
                                <th>Remaining Balance</th>
                                <th>Payment Status</th>
                                <th>Last Payment Date</th>
                                <th>Payment Method</th>
                                <th>Payment Type</th>
                                <th>Total Quantity Delivered</th>
                                <th>Reminder</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($payments as $payment)

                                @php
                                    /*
                                    |--------------------------------------------------------------------------
                                    | Payment Calculations
                                    |--------------------------------------------------------------------------
                                    */

                                    $totalAmount = (float) ($payment->total_amount ?? 0);
                                    $totalPaid = (float) ($payment->total_paid ?? 0);
                                    $remainingBalance = max(
                                        0,
                                        (float) ($payment->remaining_amount ?? ($totalAmount - $totalPaid))
                                    );

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Payment Status
                                    |--------------------------------------------------------------------------
                                    */

                                    if ($remainingBalance <= 0) {
                                        $paymentStatus = 'Paid';
                                        $paymentStatusClass = 'bg-success';
                                    } elseif ($totalPaid > 0) {
                                        $paymentStatus = 'Payment Due';
                                        $paymentStatusClass = 'bg-warning text-dark';
                                    } else {
                                        $paymentStatus = 'Unpaid';
                                        $paymentStatusClass = 'bg-danger';
                                    }

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Payment Method
                                    |--------------------------------------------------------------------------
                                    */

                                    $method = strtolower($payment->payment_method ?? '');

                                    $methodIcons = [
                                        'cash' => 'money-bill-wave',
                                        'card' => 'credit-card',
                                        'bank_transfer' => 'university',
                                        'mobile_banking' => 'mobile-screen-button',
                                        'check' => 'money-check',
                                    ];

                                    $methodColors = [
                                        'cash' => 'success',
                                        'card' => 'primary',
                                        'bank_transfer' => 'info',
                                        'mobile_banking' => 'warning text-dark',
                                        'check' => 'secondary',
                                    ];

                                    $methodLabel = ucfirst(
                                        str_replace('_', ' ', $method ?: 'Not specified')
                                    );

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Payment Type
                                    |--------------------------------------------------------------------------
                                    */

                                    $isInstallment = (bool) ($payment->is_installment ?? false);

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Delivered Quantity
                                    |--------------------------------------------------------------------------
                                    */

                                    $upperDelivered = (int) ($payment->total_upper_delivered ?? 0);
                                    $lowerDelivered = (int) ($payment->total_lower_delivered ?? 0);

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Reminder
                                    |--------------------------------------------------------------------------
                                    */

                                    $lvl = $payment->reminder_level ?? 'normal';

                                    $reminderClass = match ($lvl) {
                                        'critical_unpaid',
                                        'critical' => 'bg-danger',

                                        'warning' => 'bg-warning text-dark',

                                        'closed' => 'bg-success',

                                        default => 'bg-secondary',
                                    };

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Patient Status
                                    |--------------------------------------------------------------------------
                                    */

                                    $patientStatus = strtolower(
                                        $payment->patient_status ?? 'unknown'
                                    );

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Case Row Class
                                    |--------------------------------------------------------------------------
                                    */

                                    $rowClass = '';

                                    if ($lvl === 'critical_unpaid') {
                                        $rowClass = 'table-reminder-critical';
                                    } elseif ($lvl === 'critical') {
                                        $rowClass = 'table-reminder-red';
                                    } elseif ($lvl === 'warning') {
                                        $rowClass = 'table-reminder-yellow';
                                    } elseif ($lvl === 'closed') {
                                        $rowClass = 'table-reminder-closed';
                                    }
                                @endphp

                                <tr
                                    class="clickable-row {{ $rowClass }}"
                                    data-href="{{ route('admin.payments.plan.index') }}?predict3d_id={{ urlencode($payment->predict3d_id) }}"
                                >

                                    {{-- ==========================================================
                                        1. PATIENT
                                    =========================================================== --}}
                                    <td>
                                        <div class="d-flex align-items-center">

                                            <div
                                                class="avatar-circle me-3"
                                                style="
                                                    width:40px;
                                                    height:40px;
                                                    background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
                                                    border-radius:50%;
                                                    display:flex;
                                                    align-items:center;
                                                    justify-content:center;
                                                    color:white;
                                                    font-weight:bold;
                                                "
                                            >
                                                {{ strtoupper(substr($payment->patient_full_name ?? 'P', 0, 1)) }}
                                            </div>

                                            <div>
                                                <div class="fw-bold">

                                                    {{ $payment->patient_full_name ?? 'Unknown' }}

                                                    <span class="badge ms-2
                                                        {{ $patientStatus === 'active'
                                                            ? 'bg-success'
                                                            : 'bg-secondary' }}">
                                                        {{ ucfirst($payment->patient_status ?? 'Unknown') }}
                                                    </span>

                                                </div>

                                                <small class="text-muted">
                                                    ID: {{ $payment->predict3d_id }}

                                                    @if($payment->patient_phone)
                                                        · {{ $payment->patient_phone }}
                                                    @endif
                                                </small>
                                            </div>

                                        </div>
                                    </td>


                                    {{-- ==========================================================
                                        2. TOTAL AMOUNT
                                    =========================================================== --}}
                                    <td>
                                        <strong class="text-success fs-6">
                                            BDT {{ number_format($totalAmount, 2) }}
                                        </strong>
                                    </td>


                                    {{-- ==========================================================
                                        3. TOTAL PAID AMOUNT
                                    =========================================================== --}}
                                    <td>
                                        <strong class="text-primary">
                                            BDT {{ number_format($totalPaid, 2) }}
                                        </strong>
                                    </td>


                                    {{-- ==========================================================
                                        4. REMAINING BALANCE
                                    =========================================================== --}}
                                    <td>
                                        <strong class="{{ $remainingBalance > 0 ? 'text-danger' : 'text-success' }}">
                                            BDT {{ number_format($remainingBalance, 2) }}
                                        </strong>
                                    </td>


                                    {{-- ==========================================================
                                        5. PAYMENT STATUS
                                    =========================================================== --}}
                                    <td>
                                        <span class="badge {{ $paymentStatusClass }} px-3 py-2">
                                            <i class="fas
                                                {{ $remainingBalance <= 0
                                                    ? 'fa-check-circle'
                                                    : 'fa-exclamation-circle' }}
                                                me-1">
                                            </i>

                                            {{ $paymentStatus }}
                                        </span>
                                    </td>


                                    {{-- ==========================================================
                                        6. LAST PAYMENT DATE
                                    =========================================================== --}}
                                    <td>
                                        @if(!empty($payment->payment_date))

                                            <strong>
                                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                                            </strong>

                                        @else

                                            <span class="text-muted">
                                                No payment yet
                                            </span>

                                        @endif
                                    </td>


                                    {{-- ==========================================================
                                        7. PAYMENT METHOD
                                    =========================================================== --}}
                                    <td>
                                        <span
                                            class="badge bg-{{ $methodColors[$method] ?? 'secondary' }}"
                                        >
                                            <i class="fas fa-{{ $methodIcons[$method] ?? 'question-circle' }} me-1"></i>

                                            {{ $methodLabel }}
                                        </span>
                                    </td>


                                    {{-- ==========================================================
                                        8. PAYMENT TYPE
                                    =========================================================== --}}
                                    <td>

                                        @if($isInstallment)

                                            <span class="badge bg-info">
                                                <i class="fas fa-layer-group me-1"></i>
                                                Installment
                                            </span>

                                            @if($payment->next_payment_date)
                                                <div class="small text-muted mt-1">
                                                    Next:
                                                    {{ \Carbon\Carbon::parse($payment->next_payment_date)->format('M d, Y') }}
                                                </div>
                                            @endif

                                        @else

                                            <span class="badge bg-success">
                                                <i class="fas fa-money-check-alt me-1"></i>
                                                Full
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ==========================================================
                                        9. TOTAL QUANTITY DELIVERED
                                    =========================================================== --}}
                                    <td>

                                        <span class="badge bg-primary">
                                            U: {{ $upperDelivered }}
                                        </span>

                                        <span class="badge bg-success ms-1">
                                            L: {{ $lowerDelivered }}
                                        </span>

                                        <div class="small text-muted mt-1">
                                            Total:
                                            {{ $upperDelivered + $lowerDelivered }}
                                        </div>

                                    </td>


                                    {{-- ==========================================================
                                        10. REMINDER
                                    =========================================================== --}}
                                    <td>

                                        <span
                                            class="badge {{ $reminderClass }} px-3 py-2"
                                            style="
                                                font-size:.78rem;
                                                font-weight:700;
                                                letter-spacing:.2px;
                                            "
                                        >
                                            {{ $payment->reminder_text ?? 'No reminder' }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach
                        </tbody>
                </table>
            </div>

           

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-credit-card fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">No payments recorded</h4>
                <p class="text-muted mb-4">Start tracking payments by recording the first transaction.</p>
                <a href="{{ route('admin.payments.plan.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Record First Payment
                </a>
            </div>
        @endif
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('click', function (e) {
    const row = e.target.closest('tr.clickable-row');
    if (!row) return;
    if (e.target.closest('a, button, input, select, textarea, label')) return;
    const href = row.getAttribute('data-href');
    if (href) window.location.href = href;
});
</script>

<script>
(function () {
    var baseUrl = @json(route('admin.payments.index'));
    var selRegion = document.getElementById('filter_region');
    var selArea = document.getElementById('filter_area');
    var selTerritory = document.getElementById('filter_territory');
    var selDoctor = document.getElementById('filter_doctor');
    var selMr = document.getElementById('filter_mr');
    var selStatus = document.getElementById('filter_status');
    var inpFrom = document.getElementById('filter_from');
    var inpTo = document.getElementById('filter_to');
    var btnApply = document.getElementById('btn_apply_filters');

    function go() {
        var params = new URLSearchParams();
        if (selRegion && selRegion.value) params.append('region_id', selRegion.value);
        if (selArea && selArea.value) params.append('area_id', selArea.value);
        if (selTerritory && selTerritory.value) params.append('territory_id', selTerritory.value);
        if (selDoctor && selDoctor.value) params.append('doctor_name', selDoctor.value);
        if (selMr && selMr.value) params.append('mr_name', selMr.value);
        if (selStatus && selStatus.value) params.append('status', selStatus.value);
        if (inpFrom && inpFrom.value) params.append('from_date', inpFrom.value);
        if (inpTo && inpTo.value) params.append('to_date', inpTo.value);

        var qs = params.toString();
        window.location.href = baseUrl + (qs ? '?' + qs : '');
    }

    if (selRegion) selRegion.addEventListener('change', function() {
        if (selArea) selArea.value = '';
        if (selTerritory) selTerritory.value = '';
        go();
    });
    if (selArea) selArea.addEventListener('change', function() {
        if (selTerritory) selTerritory.value = '';
        go();
    });
    if (selTerritory) selTerritory.addEventListener('change', go);
    
    if (btnApply) {
        btnApply.addEventListener('click', go);
    }
})();
</script>
@endpush
@endsection
