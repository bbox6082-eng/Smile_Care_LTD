@extends('layouts.dashboard')

@section('title', 'Doctors Shortlist - SmileCare')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div class="page-title">
        <i class="fas fa-filter me-3"></i>
        <h1>Doctors Shortlist</h1>
        <small class="text-muted ms-2 d-block">
            Doctors added in the last {{ $days }} days (since {{ optional($since)->format('M d, Y') }})
        </small>
    </div>
    <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Doctors
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-user-doctor me-2"></i>Last {{ $days }} Days</h5>
    </div>
    <div class="card-body">
        @if($doctors->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Dr. Name</th>
                            <th>Mobile</th>
                            <th>Region / Area / Territory</th>
                            <th>Chamber</th>
                            <th>Added By</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctors as $d)
                            <tr>
                                <td class="fw-bold">{{ $d->name }}</td>
                                <td>{{ $d->mobile_number ?? '—' }}</td>
                                <td>
                                    <small>{{ $d->territory?->area?->region?->name ?? '—' }}</small><br>
                                    <small class="text-muted">→ {{ $d->territory?->area?->name ?? '—' }}</small><br>
                                    <small class="text-muted">→ {{ $d->territory?->name ?? '—' }}</small>
                                </td>
                                <td><small>{{ $d->chamber_address ? \Illuminate\Support\Str::limit($d->chamber_address, 40) : '—' }}</small></td>
                                <td>{{ $d->creator?->name ?? '—' }}</td>
                                <td>{{ $d->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i>
                <p class="mb-0">No doctors in this period.</p>
            </div>
        @endif
    </div>
</div>
@endsection
