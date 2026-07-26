@extends('layouts.dashboard')
@section('title', 'MR Management - SmileCare')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0"><i class="fas fa-user-tie me-2"></i>MR Management</h2>
    @if($mrs->count() > 0)
        <a href="{{ route('admin.mrs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Add New
        </a>
    @endif
</div>

@if($mrs->count() === 0)
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="fas fa-user-tie fa-4x text-muted opacity-50"></i>
            </div>
            <h4 class="text-muted mb-3">No Marketing Representatives Found</h4>
            <p class="text-muted mb-4">Get started by adding your first Marketing Representative.</p>
            <a href="{{ route('admin.mrs.create') }}" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-plus-circle me-2"></i>Add New MR
            </a>
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-header py-3">
            <h5 class="mb-0 text-white"><i class="fas fa-list me-2"></i>All Marketing Representatives</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="py-3">Contact Info</th>
                            <th class="py-3">Blood Group</th>
                            <th class="py-3">Emergency Contact</th>
                            <th class="py-3 text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mrs as $mr)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $mr->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                @if($mr->phone)
                                    <div class="text-muted small"><i class="fas fa-phone me-1"></i>{{ $mr->phone }}</div>
                                @endif
                                @if($mr->email)
                                    <div class="text-muted small"><i class="fas fa-envelope me-1"></i>{{ $mr->email }}</div>
                                @endif
                                @if(!$mr->phone && !$mr->email)
                                    <span class="text-muted fst-italic">N/A</span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($mr->blood_group)
                                    <span class="badge bg-danger">{{ $mr->blood_group }}</span>
                                @else
                                    <span class="text-muted fst-italic">N/A</span>
                                @endif
                            </td>
                            <td class="py-3">
                                {{ $mr->emergency_contact ?: 'N/A' }}
                            </td>
                            <td class="py-3 text-end px-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.mrs.show', $mr->id) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.mrs.edit', $mr->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.mrs.destroy', $mr->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this Marketing Representative? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
