@extends('layouts.dashboard')
@section('title', 'Add New MR - SmileCare')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title mb-0">
        <a href="{{ route('admin.mrs.index') }}" class="text-decoration-none text-muted me-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <i class="fas fa-user-plus me-2"></i>Add New MR
    </h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header py-3">
        <h5 class="mb-0 text-white"><i class="fas fa-info-circle me-2"></i>Marketing Representative Details</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.mrs.store') }}" method="POST">
            @csrf
            
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Enter full name">
                    </div>
                    @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Enter email address">
                    </div>
                    @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Enter phone number">
                    </div>
                    @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold">Blood Group</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-tint text-danger"></i></span>
                        <select name="blood_group" class="form-select @error('blood_group') is-invalid @enderror">
                            <option value="">Select Blood Group</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('blood_group') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold">Emergency Contact</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-star-of-life text-danger"></i></span>
                        <input type="text" name="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" value="{{ old('emergency_contact') }}" placeholder="Emergency contact number">
                    </div>
                    @error('emergency_contact') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-12">
                    <label class="form-label fw-bold">Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-map-marker-alt text-muted"></i></span>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" placeholder="Enter full address">{{ old('address') }}</textarea>
                    </div>
                    @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('admin.mrs.index') }}" class="btn btn-light border px-4">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i>Save MR
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
