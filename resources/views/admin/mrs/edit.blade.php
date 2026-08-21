@extends('layouts.dashboard')

@section('title', 'Edit MR - SmileCare')

@section('content')

<style>
    /* =========================================
       EDIT MR - PROFESSIONAL STYLING
       ========================================= */

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

    /* =========================================
       PHOTO CARD
       ========================================= */

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
        margin-bottom: 18px;
    }

    /* Current photo */

    .current-photo-wrapper {
        text-align: center;
        margin-bottom: 18px;
    }

    .current-photo {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 14px;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .current-photo-label {
        margin-top: 9px;
        color: #6c757d;
        font-size: 0.8rem;
    }

    /* Upload */

    .photo-upload-area {
        flex: 1;
        min-height: 180px;
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
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: #eef0ff;
        color: #667eea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 12px;
    }

    .photo-upload-title {
        color: #34495e;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .photo-upload-text {
        color: #8a94a6;
        font-size: 0.8rem;
        margin-bottom: 13px;
    }

    .photo-browse-btn {
        border: 1px solid #667eea;
        color: #667eea;
        background: #fff;
        border-radius: 8px;
        padding: 7px 16px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .photo-browse-btn:hover {
        background: #667eea;
        color: #fff;
    }

    #photo {
        display: none;
    }

    /* New preview */

    .photo-preview-container {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    .photo-preview {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 14px;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        margin-bottom: 12px;
    }

    .photo-file-name {
        color: #596579;
        font-size: 0.8rem;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .photo-change-btn {
        margin-top: 8px;
        border: 0;
        background: transparent;
        color: #667eea;
        font-size: 0.8rem;
        cursor: pointer;
    }

    .photo-change-btn:hover {
        text-decoration: underline;
    }

    /* =========================================
       FORM FOOTER
       ========================================= */

    .mr-form-footer {
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .mr-cancel-btn,
    .mr-update-btn {
        min-width: 125px;
        min-height: 44px;
        border-radius: 8px;
        font-weight: 500;
    }

    .mr-update-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .mr-update-btn:hover {
        opacity: 0.92;
    }

    /* =========================================
       RESPONSIVE
       ========================================= */

    @media (max-width: 991.98px) {

        .photo-card {
            min-height: 380px;
        }

        .photo-upload-area {
            min-height: 220px;
        }
    }
</style>


<!-- ==========================================
     PAGE TITLE
     ========================================== -->

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2 class="page-title mr-page-title mb-0">

        <a
            href="{{ route('admin.mrs.index') }}"
            class="text-decoration-none text-muted me-2"
            title="Back to Marketing Representatives"
        >
            <i class="fas fa-arrow-left"></i>
        </a>

        <i class="fas fa-user-edit me-2"></i>
        Edit Marketing Representative

    </h2>

</div>


<!-- ==========================================
     MAIN CARD
     ========================================== -->

<div class="card border-0 shadow-sm mr-form-card">


    <!-- HEADER -->

    <div class="card-header mr-section-header border-0">

        <h5 class="mb-0 text-white">

            <i class="fas fa-user-edit me-2"></i>

            Update Marketing Representative

        </h5>

    </div>


    <div class="card-body p-4 p-lg-5">


        <!-- IMPORTANT:
             enctype is required for image upload -->

        <form
            action="{{ route('admin.mrs.update', $mr->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            @method('PUT')


            <div class="row g-4">


                <!-- ======================================
                     LEFT SIDE
                     PERSONAL INFORMATION
                     ====================================== -->

                <div class="col-lg-8">

                    <div class="mr-section-title">

                        <i class="fas fa-user-circle me-2 text-primary"></i>

                        Personal & Contact Information

                    </div>


                    <div class="row g-4">


                        <!-- FULL NAME -->

                        <div class="col-md-6">

                            <div class="mr-field">

                                <label class="form-label fw-bold">

                                    Full Name

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
                                        value="{{ old('name', $mr->name) }}"
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


                        <!-- EMAIL -->

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
                                        value="{{ old('email', $mr->email) }}"
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


                        <!-- PHONE -->

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
                                        value="{{ old('phone', $mr->phone) }}"
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


                        <!-- BLOOD GROUP -->

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


                                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)

                                            <option
                                                value="{{ $bg }}"
                                                {{ old('blood_group', $mr->blood_group) == $bg ? 'selected' : '' }}
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


                        <!-- EMERGENCY CONTACT -->

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
                                        value="{{ old('emergency_contact', $mr->emergency_contact) }}"
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


                        <!-- ADDRESS -->

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
                                        rows="4"
                                        class="form-control @error('address') is-invalid @enderror"
                                        placeholder="Enter full address"
                                    >{{ old('address', $mr->address) }}</textarea>

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


                <!-- ======================================
                     RIGHT SIDE
                     PROFILE PHOTO
                     ====================================== -->

                <div class="col-lg-4">

                    <div class="photo-card">


                        <!-- PHOTO HEADER -->

                        <div class="photo-card-header">

                            <i class="fas fa-camera"></i>

                            <h6 class="photo-card-title">
                                MR Profile Picture
                            </h6>

                        </div>


                        <p class="photo-card-description">

                            Use a clear profile picture to easily
                            identify this Marketing Representative.

                            This is especially useful when multiple
                            MRs have the same name.

                        </p>


                        <!-- =================================
                             CURRENT PHOTO
                             ================================= -->

                        @if(!empty($mr->photo))

                            <div
                                class="current-photo-wrapper"
                                id="currentPhotoWrapper"
                            >

                                <img
                                    src="{{ asset('storage/' . $mr->photo) }}"
                                    alt="{{ $mr->name }}"
                                    class="current-photo"
                                >

                                <div class="current-photo-label">

                                    <i class="fas fa-check-circle text-success me-1"></i>

                                    Current Profile Picture

                                </div>

                            </div>

                        @endif


                        <!-- =================================
                             UPLOAD / PREVIEW AREA
                             ================================= -->

                        <label
                            for="photo"
                            class="photo-upload-area"
                            id="photoUploadArea"
                        >


                            <!-- DEFAULT UPLOAD CONTENT -->

                            <div id="photoUploadContent">

                                <div class="photo-upload-icon">

                                    <i class="fas fa-camera"></i>

                                </div>


                                <div class="photo-upload-title">

                                    {{ !empty($mr->photo) ? 'Replace Profile Picture' : 'Upload MR Picture' }}

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


                            <!-- =================================
                                 NEW PHOTO PREVIEW
                                 ================================= -->

                            <div
                                id="photoPreviewContainer"
                                class="photo-preview-container"
                            >

                                <img
                                    id="photoPreview"
                                    class="photo-preview"
                                    src=""
                                    alt="New MR Picture Preview"
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


                        <!-- FILE INPUT -->

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
                 ACTION BUTTONS
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
                    class="btn btn-primary mr-update-btn"
                >

                    <i class="fas fa-save me-2"></i>

                    Update MR

                </button>

            </div>


        </form>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const photoInput = document.getElementById('photo');

    const uploadContent =
        document.getElementById('photoUploadContent');

    const previewContainer =
        document.getElementById('photoPreviewContainer');

    const preview =
        document.getElementById('photoPreview');

    const fileName =
        document.getElementById('photoFileName');

    const changeButton =
        document.getElementById('changePhotoBtn');


    if (!photoInput) {
        return;
    }


    /* ==========================================
       SELECT NEW PHOTO
       ========================================== */

    photoInput.addEventListener('change', function () {

        const file = this.files[0];


        if (!file) {
            resetPreview();
            return;
        }


        /* Allowed file types */

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];


        if (!allowedTypes.includes(file.type)) {

            alert('Please select a JPG, PNG or WebP image.');

            this.value = '';

            resetPreview();

            return;
        }


        /* Maximum 2 MB */

        const maxSize = 2 * 1024 * 1024;


        if (file.size > maxSize) {

            alert('The image size must not exceed 2 MB.');

            this.value = '';

            resetPreview();

            return;
        }


        /* ======================================
           GENERATE PREVIEW
           ====================================== */

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
       RESET NEW PHOTO PREVIEW
       ========================================== */

    function resetPreview() {

        preview.src = '';

        fileName.textContent = '';

        uploadContent.style.display = 'block';

        previewContainer.style.display = 'none';

    }

});
</script>

@endsection