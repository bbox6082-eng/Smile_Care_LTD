@extends('layouts.dashboard')

@section('title', 'Inactive Patient Details')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h3 class="fw-bold mb-1">

                        <i class="fas fa-user-slash me-2 text-danger"></i>

                        Inactive Patient Details

                    </h3>

                    <p class="text-muted mb-0">

                        View complete information about this inactive patient.

                    </p>

                </div>


                <div class="mt-3 mt-md-0">

                    <a
                        href="{{ route('admin.reports.inactive-patients.index') }}"
                        class="btn btn-light border shadow-sm"
                    >

                        <i class="fas fa-arrow-left me-1"></i>

                        Back to Inactive Patients

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PATIENT IDENTIFICATION
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-semibold">

                <i class="fas fa-id-card me-2 text-primary"></i>

                Patient Identification

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Predict3D ID --}}

                <div class="col-lg-4 col-md-6">

                    <small class="text-muted d-block">
                        Predict3D ID
                    </small>

                    <h5 class="fw-bold mt-1 mb-0">

                        {{ $patient->Predict3DId ?: '-' }}

                    </h5>

                </div>


                {{-- Patient Name --}}

                <div class="col-lg-4 col-md-6">

                    <small class="text-muted d-block">
                        Patient Name
                    </small>

                    <h5 class="fw-bold mt-1 mb-0">

                        {{ $patient->FullName ?: '-' }}

                    </h5>

                </div>


                {{-- Status --}}

                <div class="col-lg-4 col-md-6">

                    <small class="text-muted d-block">
                        Status
                    </small>

                    <div class="mt-2">

                        <span class="badge bg-danger fs-6">

                            <i class="fas fa-times-circle me-1"></i>

                            Inactive

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">


        {{-- =====================================================
            PERSONAL INFORMATION
        ====================================================== --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-user me-2 text-primary"></i>

                        Personal Information

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tbody>

                            <tr>

                                <th width="40%">
                                    Full Name
                                </th>

                                <td>
                                    {{ $patient->FullName ?: '-' }}
                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Gender
                                </th>

                                <td>

                                    @if($patient->Gender === 'Male')

                                        <span class="badge bg-primary">
                                            Male
                                        </span>

                                    @elseif($patient->Gender === 'Female')

                                        <span class="badge bg-danger">
                                            Female
                                        </span>

                                    @elseif($patient->Gender === 'Custom')

                                        <span class="badge bg-secondary">
                                            Custom
                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Date of Birth
                                </th>

                                <td>

                                    {{ $patient->DateOfBirth
                                        ? $patient->DateOfBirth->format('d M Y')
                                        : '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Phone Number
                                </th>

                                <td>

                                    {{ $patient->PhoneNumber ?: '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Emergency Contact
                                </th>

                                <td>

                                    {{ $patient->EmergencyContact ?: '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Address
                                </th>

                                <td>

                                    {{ $patient->Address ?: '-' }}

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- =====================================================
            TREATMENT INFORMATION
        ====================================================== --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-tooth me-2 text-info"></i>

                        Treatment Information

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tbody>

                            <tr>

                                <th width="40%">
                                    Scanning For
                                </th>

                                <td>

                                    @if($patient->ScanningFor === 'Aligner')

                                        <span class="badge bg-info text-dark">
                                            Aligner
                                        </span>

                                    @elseif($patient->ScanningFor === 'Zirconia')

                                        <span class="badge bg-warning text-dark">
                                            Zirconia
                                        </span>

                                    @elseif($patient->ScanningFor === 'Others')

                                        <span class="badge bg-secondary">
                                            Others
                                        </span>

                                    @else

                                        {{ $patient->ScanningFor ?: '-' }}

                                    @endif

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Other Scanning Type
                                </th>

                                <td>

                                    {{ $patient->ScanningForOthers ?: '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Case Type
                                </th>

                                <td>

                                    {{ $patient->case_type ?: '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Upper Cases
                                </th>

                                <td>

                                    {{ $patient->UpperCases ?? 0 }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Lower Cases
                                </th>

                                <td>

                                    {{ $patient->LowerCases ?? 0 }}

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- =====================================================
            DOCTOR & LOCATION
        ====================================================== --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-user-doctor me-2 text-success"></i>

                        Doctor & Location

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tbody>

                            <tr>

                                <th width="40%">
                                    Doctor
                                </th>

                                <td>

                                    {{ $patient->DoctorName ?: '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Doctor Email
                                </th>

                                <td>

                                    {{ $patient->doctor_email ?: '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Chamber
                                </th>

                                <td>

                                    {{ $patient->ChamberName ?: '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Region
                                </th>

                                <td>

                                    {{ $patient->RegionalName ?: '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Territory
                                </th>

                                <td>

                                    {{ $patient->TerritoryName ?: '-' }}

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- =====================================================
            SYSTEM INFORMATION
        ====================================================== --}}

        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0 fw-semibold">

                        <i class="fas fa-database me-2 text-secondary"></i>

                        System Information

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless mb-0">

                        <tbody>

                            <tr>

                                <th width="40%">
                                    Created By
                                </th>

                                <td>

                                    {{ $patient->creator?->name ?? '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Registered At
                                </th>

                                <td>

                                    {{ $patient->created_at
                                        ? $patient->created_at->format('d M Y h:i A')
                                        : '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Last Updated
                                </th>

                                <td>

                                    {{ $patient->updated_at
                                        ? $patient->updated_at->format('d M Y h:i A')
                                        : '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Patient Page Deleted
                                </th>

                                <td>

                                    @if($patient->is_patient_page_deleted)

                                        <span class="badge bg-danger">
                                            Yes
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            No
                                        </span>

                                    @endif

                                </td>

                            </tr>


                            <tr>

                                <th>
                                    Current Status
                                </th>

                                <td>

                                    <span class="badge bg-danger">

                                        Inactive

                                    </span>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FOOTER ACTION
    ========================================================== --}}

    <div class="d-flex justify-content-end mt-4 mb-4">

        <a
            href="{{ route('admin.reports.inactive-patients.index') }}"
            class="btn btn-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            Back to Inactive Patients

        </a>

    </div>

</div>

@endsection