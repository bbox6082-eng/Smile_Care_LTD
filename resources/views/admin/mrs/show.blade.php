@extends('layouts.dashboard')
@section('title', 'MR Details - SmileCare')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0"><i class="fas fa-user-tie me-2"></i>Marketing Representative Details</h2>
    <a href="{{ route('admin.mrs.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to List
    </a>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>MR Information</h5>
                <a href="{{ route('admin.mrs.edit', $mr->id) }}" class="btn btn-sm btn-light text-primary">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-12 text-center mb-4">
                        @if(!empty($mr->photo))

                            <img
                                src="{{ asset('storage/' . $mr->photo) }}"
                                alt="{{ $mr->name }}"
                                class="rounded-circle mb-3"
                                style="
                                    width: 120px;
                                    height: 120px;
                                    object-fit: cover;
                                    border: 4px solid #ffffff;
                                    box-shadow: 0 5px 18px rgba(0,0,0,0.12);
                                "
                            >

                        @else

                            <div
                                class="bg-primary bg-opacity-10 text-primary rounded-circle
                                    d-inline-flex align-items-center justify-content-center mb-3"
                                style="
                                    width: 120px;
                                    height: 120px;
                                    font-size: 2.7rem;
                                "
                            >
                                <i class="fas fa-user"></i>
                            </div>

                        @endif
                        <h3 class="fw-bold mb-1">{{ $mr->name }}</h3>
                        <p class="text-muted">Marketing Representative</p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-muted mb-1 small text-uppercase fw-bold">Email Address</h6>
                            <div class="fs-6">{{ $mr->email ?: 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-muted mb-1 small text-uppercase fw-bold">Phone Number</h6>
                            <div class="fs-6">{{ $mr->phone ?: 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-muted mb-1 small text-uppercase fw-bold">Blood Group</h6>
                            <div class="fs-6">
                                @if($mr->blood_group)
                                    <span class="badge bg-danger">{{ $mr->blood_group }}</span>
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-muted mb-1 small text-uppercase fw-bold">Emergency Contact</h6>
                            <div class="fs-6">{{ $mr->emergency_contact ?: 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-muted mb-1 small text-uppercase fw-bold">Address</h6>
                            <div class="fs-6">{{ $mr->address ?: 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-muted mb-1 small text-uppercase fw-bold">Date Added</h6>
                            <div class="fs-6">{{ $mr->created_at->format('F d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white text-end py-3">
                <form action="{{ route('admin.mrs.destroy', $mr->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this MR? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-2"></i>Delete Representative
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
