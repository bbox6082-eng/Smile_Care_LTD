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
                            <th>Payment Method</th>
                            <th>Payment Type</th>
                            <th>Last Payment Date</th>
                            <th>Remaining Balance</th>
                            <th>Total Paid Amount</th>
                            <th>Total Quantity Delivered</th>
                            <th>Reminder</th>
                            <th>Case Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr class="clickable-row"
                            data-href="{{ route('admin.payments.plan.index') }}?predict3d_id={{ urlencode($payment->predict3d_id) }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                        {{ strtoupper(substr($payment->patient_full_name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">
                                            {{ $payment->patient_full_name ?? 'Unknown' }}
                                            @php $st = strtolower($payment->patient_status ?? ''); @endphp
                                            <span class="badge ms-2 {{ $st === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($payment->patient_status ?? 'unknown') }}</span>
                                        </div>
                                        <small class="text-muted">ID: {{ $payment->predict3d_id }} {{ $payment->patient_phone ? '· '.$payment->patient_phone : '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><strong class="text-success fs-6">BDT {{ number_format($payment->total_amount ?? 0, 2) }}</strong></td>
                            <td>
                                @php
                                    $methodIcons = [
                                        'cash' => 'money-bill-wave',
                                        'card' => 'credit-card',
                                        'bank_transfer' => 'university',
                                        'check' => 'money-check'
                                    ];
                                    $methodColors = [
                                        'cash' => 'success',
                                        'card' => 'primary',
                                        'bank_transfer' => 'info',
                                        'check' => 'warning'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $methodColors[$payment->payment_method] ?? 'secondary' }}">
                                    <i class="fas fa-{{ $methodIcons[$payment->payment_method] ?? 'question' }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </span>
                            </td>
                            <td>
                                @if($payment->is_installment)
                                    <span class="badge bg-info">Installment</span>
                                    @if($payment->next_payment_date)
                                        <div class="small text-muted">Next: {{ \Carbon\Carbon::parse($payment->next_payment_date)->format('M d, Y') }}</div>
                                    @endif
                                @else
                                    <span class="badge bg-success">Full</span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($payment->payment_date))
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                                @else
                                    <span class="text-muted">No payment yet</span>
                                @endif
                            </td>
                            <td><strong>BDT {{ number_format($payment->remaining_amount ?? 0, 2) }}</strong></td>
                            <td><strong class="text-primary">BDT {{ number_format($payment->total_paid ?? 0, 2) }}</strong></td>
                            <td>
                                <span class="badge bg-primary">
                                    U: {{ $payment->total_upper_delivered }}
                                </span>

                                <span class="badge bg-success ms-1">
                                    L: {{ $payment->total_lower_delivered }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $lvl = $payment->reminder_level ?? 'normal';
                                    $cls = $lvl === 'critical_unpaid' ? 'bg-danger'
                                        : ($lvl === 'critical' ? 'bg-danger'
                                        : ($lvl === 'warning' ? 'bg-warning text-dark'
                                        : ($lvl === 'closed' ? 'bg-success' : 'bg-secondary')));
                                @endphp
                                <span class="badge {{ $cls }} px-3 py-2" style="font-size:.78rem; font-weight:700; letter-spacing:.2px;">
                                    {{ $payment->reminder_text ?? 'No reminder' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $caseCls = match($payment->case_status_level) {
                                        'critical' => 'bg-danger',
                                        'warning' => 'bg-warning text-dark',
                                        'closed' => 'bg-secondary',
                                        default => 'bg-success',
                                    };
                                @endphp

                                <span class="badge {{ $caseCls }}">
                                    {{ $payment->case_status_text }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary Stats -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="alert alert-success">
                        <strong>Grand Total: BDT {{ number_format($grandTotal ?? 0, 2) }}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-warning">
                        <strong>This Month: BDT {{ number_format($totalThisMonth ?? 0, 2) }}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-primary">
                        <strong>Today: BDT {{ number_format($totalToday ?? 0, 2) }}</strong>
                    </div>
                </div>
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
