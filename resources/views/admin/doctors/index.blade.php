@extends('layouts.dashboard')

@section('title', 'Doctors - SmileCare')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div class="page-title">
        <i class="fas fa-user-doctor me-3"></i>
        <h1>Doctor Management</h1>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-filter me-2"></i>Shortlist
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.doctors.shortlist', ['days' => 15]) }}">
                        <i class="fas fa-calendar-day me-2"></i>Doctors last 15 days
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.doctors.shortlist', ['days' => 30]) }}">
                        <i class="fas fa-calendar-alt me-2"></i>Doctors last 30 days
                    </a>
                </li>
            </ul>
        </div>

        <a href="{{ route('admin.doctors.export.all') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel me-2"></i>Download Doctors List
        </a>

        <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Doctor
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header py-2">
        <h6 class="mb-0"><i class="fas fa-search-location me-2"></i>Find by location</h6>
        <small class="text-muted">Choose a region, then an area, then a territory to list doctors in that territory.</small>
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
                    <option value="">All areas{{ $filterRegionId ? ' in this region' : '' }}</option>
                    @foreach($areas as $a)
                        <option value="{{ $a->id }}" {{ (string)($filterAreaId ?? '') === (string)$a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter_territory" class="form-label mb-1">Territory</label>
                <select id="filter_territory" class="form-select" {{ !$filterAreaId ? 'disabled' : '' }}>
                    <option value="">All territories{{ $filterAreaId ? ' in this area' : '' }}</option>
                    @foreach($territories as $t)
                        <option value="{{ $t->id }}" {{ (string)($filterTerritoryId ?? '') === (string)$t->id ? 'selected' : '' }}>{{ $t->name }}</option>
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
        </div>
        @if($filterRegionId || $filterAreaId || $filterTerritoryId || $filterMrName)
            <div class="mt-2">
                <a href="{{ route('admin.doctors.index') }}" class="btn btn-sm btn-outline-secondary">Clear filters</a>
            </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>All Doctors</h5>
    </div>
    <div class="card-body">
        @if($doctors->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Region / Area / Territory</th>
                            <th>Chamber Address</th>
                            <th>Added By</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctors as $doctor)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3" style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                        {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $doctor->name }}</div>
                                        @if($doctor->email)
                                            <small class="text-muted"><i class="fas fa-envelope me-1"></i>{{ $doctor->email }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($doctor->mobile_number)
                                    <div><i class="fas fa-phone me-1"></i>{{ $doctor->mobile_number }}</div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $reg = $doctor->territory?->area?->region?->name;
                                    $ar = $doctor->territory?->area?->name;
                                    $ter = $doctor->territory?->name;
                                @endphp
                                @if($reg || $ar || $ter)
                                    <small>{{ $reg ?? '—' }}</small><br>
                                    <small class="text-muted">→ {{ $ar ?? '—' }}</small><br>
                                    <small class="text-muted">→ {{ $ter ?? '—' }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $doctor->chamber_address ? \Illuminate\Support\Str::limit($doctor->chamber_address, 50) : '—' }}</small>
                                <div class="mt-2">
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-user-tie me-1 text-primary"></i>{{ $doctor->marketing_representative_name ?: 'No MR' }}
                                    </span>
                                    @if($doctor->mr_assigned_at)
                                        <div class="text-muted mt-1" style="font-size: 0.75rem;">Since: {{ $doctor->mr_assigned_at->format('M d, Y') }}</div>
                                    @endif
                                </div>
                            </td>
                            <td><small class="text-muted">{{ $doctor->creator?->name ?? '—' }}</small></td>
                            <td>{{ $doctor->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.doctors.show', $doctor) }}" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" style="display: inline;"
                                          onsubmit="return confirm('Are you sure you want to delete this doctor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $doctors->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-user-doctor fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">No doctors found</h4>
                @if($filterRegionId || $filterAreaId || $filterTerritoryId || $filterMrName)
                    <p class="text-muted mb-4">Try changing the filters or add a new doctor.</p>
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary me-2">Clear filters</a>
                @else
                    <p class="text-muted mb-4">Add your first doctor to get started.</p>
                @endif
                <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Add New Doctor
                </a>
            </div>
        @endif
    </div>
</div>

<script>
(function () {
    var baseUrl = @json(route('admin.doctors.index'));
    var selRegion = document.getElementById('filter_region');
    var selArea = document.getElementById('filter_area');
    var selTerritory = document.getElementById('filter_territory');
    var selMr = document.getElementById('filter_mr');

    function go() {
        var rid = selRegion ? selRegion.value : '';
        var aid = selArea ? selArea.value : '';
        var tid = selTerritory ? selTerritory.value : '';
        var mrid = selMr ? selMr.value : '';

        var params = new URLSearchParams();
        if (rid) params.append('region_id', rid);
        if (aid) params.append('area_id', aid);
        if (tid) params.append('territory_id', tid);
        if (mrid) params.append('mr_name', mrid);

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
    if (selMr) selMr.addEventListener('change', go);
})();
</script>
@endsection
