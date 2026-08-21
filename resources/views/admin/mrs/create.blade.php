@extends('layouts.dashboard')

@section('title', 'Add New MR - SmileCare')

@section('content')

<style>
    /* ================================
       MR FORM - PROFESSIONAL STYLING
       ================================ */

    .mr-page-title {
        color: #294867;
        font-weight: 600;
        font-size: 2rem;
    }

    .mr-form-card {
        border-radius: 16px;
        overflow: hidden;
    }

    .mr-section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 16px 22px;
    }

    .mr-section-header h5 {
        font-size: 1.15rem;
        font-weight: 500;
    }

    .mr-section-title {
        color: #294867;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e9ecef;
    }

    .mr-field {
        margin-bottom: 22px;
    }

    .mr-field .form-label {
        color: #243447;
        font-size: 0.92rem;
        margin-bottom: 8px;
    }

    .mr-field .input-group-text {
        min-width: 48px;
        justify-content: center;
        background: #f8f9fa;
        border-color: #dfe3e8;
    }

    .mr-field .form-control,
    .mr-field .form-select {
        min-height: 46px;
        border-color: #dfe3e8;
        border-radius: 0 8px 8px 0;
    }

    .mr-field .form-control:focus,
    .mr-field .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.15rem rgba(102, 126, 234, 0.12);
    }

    .mr-field textarea.form-control {
        min-height: 105px;
        resize: vertical;
    }

    /* ================================
       PROFILE PHOTO CARD
       ================================ */

    .photo-card {
        height: 100%;
        min-height: 430px;
        background: #fafbfc;
        border: 1px solid #e4e8ed;
        border-radius: 14px;
        padding: 24px;
        display: flex;
        flex-direction: column;
    }

    .photo-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 6px;
    }

    .photo-card-header i {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(102, 126, 234, 0.12);
        color: #667eea;
    }

    .photo-card-title {
        color: #294867;
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }

    .photo-card-description {
        color: #7b8794;
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .photo-upload-area {
        flex: 1;
        min-height: 280px;
        border: 2px dashed #cfd6df;
        border-radius: 14px;
        background: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .photo-upload-area:hover {
        border-color: #667eea;
        background: #f9faff;
    }

    .photo-upload-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #eef0ff;
        color: #667eea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 14px;
    }

    .photo-upload-title {
        color: #34495e;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .photo-upload-text {
        color: #8a94a6;
        font-size: 0.82rem;
        margin-bottom: 15px;
    }

    .photo-browse-btn {
        border: 1px solid #667eea;
        color: #667eea;
        background: #fff;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 0.88rem;
        font-weight: 500;
    }

    .photo-browse-btn:hover {
        background: #667eea;
        color: #fff;
    }

    #photo {
        display: none;
    }

    .photo-preview-container {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    .photo-preview {
        width: 180px;
        height: 180px;
        object-fit: cover;
        border-radius: 14px;
        border: 3px solid #fff;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        margin-bottom: 15px;
    }

    .photo-file-name {
        color: #596579;
        font-size: 0.82rem;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .photo-change-btn {
        margin-top: 10px;
        border: 0;
        background: transparent;
        color: #667eea;
        font-size: 0.82rem;
        cursor: pointer;
    }

    .photo-change-btn:hover {
        text-decoration: underline;
    }

    /* ================================
       FORM FOOTER
       ================================ */

    .mr-form-footer {
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .mr-cancel-btn,
    .mr-save-btn {
        min-width: 125px;
        min-height: 44px;
        border-radius: 8px;
        font-weight: 500;
    }

    .mr-save-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .mr-save-btn:hover {
        opacity: 0.92;
    }

    /* ================================
       RESPONSIVE
       ================================ */

    @media (max-width: 991.98px) {
        .photo-card {
            min-height: 360px;
        }

        .photo-upload-area {
            min-height: 260px;
        }
    }
</style>


<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="page-title mr-page-title mb-0">
        <a href="{{ route('admin.mrs.index') }}"
           class="text-decoration-none text-muted me-2"
           title="Back to Marketing Representatives">
            <i class="fas fa-arrow-left"></i>
        </a>

        <i class="fas fa-user-plus me-2"></i>
        Add New MR
    </h2>

</div>


<div class="card border-0 shadow-sm mr-form-card">

    <!-- Header -->
    <div class="card-header mr-section-header border-0">

        <h5 class="mb-0 text-white">
            <i class="fas fa-id-card me-2"></i>
            Marketing Representative Details
        </h5>

    </div>


    <div class="card-body p-4 p-lg-5">

        <form
            action="{{ route('admin.mrs.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            <div class="row g-4">

                <!-- ==========================================
                     LEFT SIDE — MR INFORMATION
                     ========================================== -->

                <div class="col-lg-8">

                    <div class="mr-section-title">
                        <i class="fas fa-user-circle me-2 text-primary"></i>
                        Personal & Contact Information
                    </div>


                    <div class="row g-4">

                        <!-- Full Name -->
                        <div class="col-md-6">

                            <div class="mr-field">

                                <label class="form-label fw-bold">
                                    MR Name
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>

                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        required
                                        placeholder="Enter full name"
                                    >

                                </div>

                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        <!-- Email -->
                        <div class="col-md-6">

                            <div class="mr-field">

                                <label class="form-label fw-bold">
                                    Email Address
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>

                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        placeholder="Enter email address"
                                    >

                                </div>

                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        <!-- Phone -->
                        <div class="col-md-6">

                            <div class="mr-field">

                                <label class="form-label fw-bold">
                                    Phone Number
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-phone text-muted"></i>
                                    </span>

                                    <input
                                        type="text"
                                        name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}"
                                        placeholder="Enter phone number"
                                    >

                                </div>

                                @error('phone')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        <!-- Blood Group -->
                        <div class="col-md-6">

                            <div class="mr-field">

                                <label class="form-label fw-bold">
                                    Blood Group
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-tint text-danger"></i>
                                    </span>

                                    <select
                                        name="blood_group"
                                        class="form-select @error('blood_group') is-invalid @enderror"
                                    >

                                        <option value="">
                                            Select Blood Group
                                        </option>

                                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)

                                            <option
                                                value="{{ $bg }}"
                                                {{ old('blood_group') == $bg ? 'selected' : '' }}
                                            >
                                                {{ $bg }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                @error('blood_group')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        <!-- Emergency Contact -->
                        <div class="col-md-6">

                            <div class="mr-field">

                                <label class="form-label fw-bold">
                                    Emergency Contact
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-star-of-life text-danger"></i>
                                    </span>

                                    <input
                                        type="text"
                                        name="emergency_contact"
                                        class="form-control @error('emergency_contact') is-invalid @enderror"
                                        value="{{ old('emergency_contact') }}"
                                        placeholder="Emergency contact number"
                                    >

                                </div>

                                @error('emergency_contact')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        <!-- Address -->
                        <div class="col-12">

                            <div class="mr-field mb-0">

                                <label class="form-label fw-bold">
                                    Address
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text align-items-start pt-3">
                                        <i class="fas fa-map-marker-alt text-muted"></i>
                                    </span>

                                    <textarea
                                        name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        rows="4"
                                        placeholder="Enter full address"
                                    >{{ old('address') }}</textarea>

                                </div>

                                @error('address')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ==========================================
                     RIGHT SIDE — PROFILE PICTURE
                     ========================================== -->

                <div class="col-lg-4">

                    <div class="photo-card">

                        <div class="photo-card-header">

                            <i class="fas fa-camera"></i>

                            <h6 class="photo-card-title">
                                MR Profile Picture
                            </h6>

                        </div>


                        <p class="photo-card-description">
                            Add a clear profile picture to help identify
                            the Marketing Representative quickly,
                            especially when multiple MRs have similar names.
                        </p>


                        <label
                            for="photo"
                            class="photo-upload-area"
                            id="photoUploadArea"
                        >

                            <!-- Upload State -->
                            <div id="photoUploadContent">

                                <div class="photo-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>

                                <div class="photo-upload-title">
                                    Upload MR Picture
                                </div>

                                <div class="photo-upload-text">
                                    JPG, PNG or WebP<br>
                                    Maximum size: 2 MB
                                </div>

                                <span class="photo-browse-btn">
                                    <i class="fas fa-folder-open me-2"></i>
                                    Choose Picture
                                </span>

                            </div>


                            <!-- Preview State -->
                            <div
                                id="photoPreviewContainer"
                                class="photo-preview-container"
                            >

                                <img
                                    id="photoPreview"
                                    class="photo-preview"
                                    src=""
                                    alt="MR Picture Preview"
                                >

                                <div
                                    id="photoFileName"
                                    class="photo-file-name"
                                ></div>

                                <button
                                    type="button"
                                    class="photo-change-btn"
                                    id="changePhotoBtn"
                                >
                                    <i class="fas fa-sync-alt me-1"></i>
                                    Change Picture
                                </button>

                            </div>

                        </label>


                        <input
                            type="file"
                            name="photo"
                            id="photo"
                            accept="image/jpeg,image/png,image/webp"
                        >


                        @error('photo')

                            <div class="text-danger small mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            <!-- ==========================================
                 FORM ACTIONS
                 ========================================== -->

            <div class="d-flex justify-content-end align-items-center gap-2 mr-form-footer">

                <a
                    href="{{ route('admin.mrs.index') }}"
                    class="btn btn-light border mr-cancel-btn"
                >
                    <i class="fas fa-times me-2"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary mr-save-btn"
                >
                    <i class="fas fa-save me-2"></i>
                    Save MR
                </button>

            </div>

        </form>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const photoInput = document.getElementById('photo');
    const uploadArea = document.getElementById('photoUploadArea');

    const uploadContent = document.getElementById('photoUploadContent');
    const previewContainer = document.getElementById('photoPreviewContainer');

    const preview = document.getElementById('photoPreview');
    const fileName = document.getElementById('photoFileName');

    const changeButton = document.getElementById('changePhotoBtn');


    if (!photoInput) {
        return;
    }


    /* ==========================================
       FILE SELECTION
       ========================================== */

    photoInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {
            resetPhotoPreview();
            return;
        }


        /* Validate file type */

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!allowedTypes.includes(file.type)) {

            alert('Please select a JPG, PNG or WebP image.');

            this.value = '';

            resetPhotoPreview();

            return;
        }


        /* Validate file size */

        const maxSize = 2 * 1024 * 1024;

        if (file.size > maxSize) {

            alert('The image size must not exceed 2 MB.');

            this.value = '';

            resetPhotoPreview();

            return;
        }


        /* Create preview */

        const reader = new FileReader();

        reader.onload = function (event) {

            preview.src = event.target.result;

            fileName.textContent = file.name;

            uploadContent.style.display = 'none';

            previewContainer.style.display = 'flex';

        };

        reader.readAsDataURL(file);

    });


    /* ==========================================
       CHANGE PHOTO
       ========================================== */

    if (changeButton) {

        changeButton.addEventListener('click', function (event) {

            event.preventDefault();
            event.stopPropagation();

            photoInput.click();

        });

    }


    /* ==========================================
       RESET PREVIEW
       ========================================== */

    function resetPhotoPreview() {

        preview.src = '';
        fileName.textContent = '';

        uploadContent.style.display = 'block';
        previewContainer.style.display = 'none';

    }

});
</script>

@endsection