@extends('layouts.dashboard')

@section('title', 'Doctor Details - SmileCare')

@section('content')
@php
    $reg = $doctor->territory?->area?->region?->name;
    $ar = $doctor->territory?->area?->name;
    $ter = $doctor->territory?->name;
@endphp
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div class="page-title">
        <i class="fas fa-user-doctor me-3"></i>
        <h1>Doctor Details</h1>
    </div>
    <div>
        <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this doctor?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger me-2"><i class="fas fa-trash me-2"></i>Delete</button>
        </form>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Doctors
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Information</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start mb-4">
                    <div class="avatar-circle me-4" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 32px;">
                        {{ strtoupper(substr($doctor->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="mb-2">{{ $doctor->name }}</h3>
                        <p class="text-muted mb-0">
                            <i class="fas fa-user me-2"></i>Added by {{ $doctor->creator?->name ?? '—' }}
                            &middot; {{ $doctor->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>

                <h6 class="text-secondary border-bottom pb-2 mb-3">Location</h6>
                <div class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <strong><i class="fas fa-map me-2"></i>Region</strong>
                        <p class="mb-0">{{ $reg ?? '—' }}</p>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong><i class="fas fa-map-pin me-2"></i>Area</strong>
                        <p class="mb-0">{{ $ar ?? '—' }}</p>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong><i class="fas fa-location-dot me-2"></i>Territory</strong>
                        <p class="mb-0">{{ $ter ?? '—' }}</p>
                    </div>
                </div>

                <h6 class="text-secondary border-bottom pb-2 mb-3">Contact & chamber</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong><i class="fas fa-phone me-2"></i>Mobile</strong>
                        <p class="mb-0">{{ $doctor->mobile_number ?? '—' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong><i class="fas fa-envelope me-2"></i>Email</strong>
                        <p class="mb-0">{{ $doctor->email ?? '—' }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <strong><i class="fas fa-clinic-medical me-2"></i>Chamber address</strong>
                        <p class="mb-0">{{ $doctor->chamber_address ?? '—' }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <strong><i class="fas fa-map-marked-alt me-2"></i>Chamber map link</strong>
                        @if(!empty($doctor->chamber_map_link))
                            <p class="mb-0"><a href="{{ $doctor->chamber_map_link }}" target="_blank" rel="noopener noreferrer">{{ $doctor->chamber_map_link }}</a></p>
                        @else
                            <p class="mb-0">—</p>
                        @endif
                    </div>
                    <div class="col-12 mb-3">
                        <strong><i class="fas fa-sticky-note me-2"></i>Note</strong>
                        <p class="mb-0">{!! $doctor->note ? nl2br(e($doctor->note)) : '—' !!}</p>
                    </div>
                    <div class="col-12 mb-0">
                        <strong><i class="fas fa-bullhorn me-2"></i>Marketing representative name</strong>
                        <p class="mb-0">{{ $doctor->marketing_representative_name ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
