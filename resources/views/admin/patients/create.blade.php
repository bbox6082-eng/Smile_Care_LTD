@extends('layouts.dashboard')

@section('title', 'Add New Patient')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>Add New Patient
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.patients.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <h6 class="text-secondary border-bottom pb-2 mb-3">Patient Details</h6>

                                <div class="mb-3">
                                    <label for="Predict3DId" class="form-label">3D Predict ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="Predict3DId" name="Predict3DId" value="{{ old('Predict3DId') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Scanning For <span class="text-danger">*</span></label>
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="ScanningFor" id="aligner" value="Aligner" {{ old('ScanningFor') == 'Aligner' ? 'checked' : '' }} onchange="toggleOthersField()">
                                            <label class="form-check-label" for="aligner">Aligner</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="ScanningFor" id="zirconia" value="Zirconia" {{ old('ScanningFor') == 'Zirconia' ? 'checked' : '' }} onchange="toggleOthersField()">
                                            <label class="form-check-label" for="zirconia">Zirconia</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="ScanningFor" id="others" value="Others" {{ old('ScanningFor') == 'Others' ? 'checked' : '' }} onchange="toggleOthersField()">
                                            <label class="form-check-label" for="others">Others</label>
                                        </div>
                                    </div>
                                    <div id="othersField" class="mt-2 d-none">
                                        <input type="text" class="form-control" name="ScanningForOthers" placeholder="Please specify..." value="{{ old('ScanningForOthers') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="case_type" class="form-label">Case Type <span class="text-danger">*</span></label>
                                    <select id="case_type" name="case_type" class="form-select @error('case_type') is-invalid @enderror" required>
                                        <option value="">Select Case Type</option>
                                        <option value="Deep CBCD" {{ old('case_type')=='Deep CBCD' ? 'selected' : '' }}>Deep CBCD</option>
                                        <option value="Full Case" {{ old('case_type')=='Full Case' ? 'selected' : '' }}>Full Case</option>
                                        <option value="Short Case" {{ old('case_type')=='Short Case' ? 'selected' : '' }}>Short Case</option>
                                        <option value="Single ARC" {{ old('case_type')=='Single ARC' ? 'selected' : '' }}>Single ARC</option>
                                        <option value="Retainer" {{ old('case_type')=='Retainer' ? 'selected' : '' }}>Retainer</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="FullName" class="form-label">Patient Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="FullName" name="FullName" value="{{ old('FullName') }}" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="PhoneNumber" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="PhoneNumber" name="PhoneNumber" value="{{ old('PhoneNumber') }}" maxlength="13" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="EmergencyContact" class="form-label">Emergency Contact <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="EmergencyContact" name="EmergencyContact" value="{{ old('EmergencyContact') }}" maxlength="13" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="Gender" id="male" value="Male" {{ old('Gender') == 'Male' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="male">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="Gender" id="female" value="Female" {{ old('Gender') == 'Female' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="female">Female</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="Gender" id="custom" value="Custom" {{ old('Gender') == 'Custom' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="custom">Custom</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="DateOfBirth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="DateOfBirth" name="DateOfBirth" value="{{ old('DateOfBirth') }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="Address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="Address" name="Address" rows="3" required>{{ old('Address') }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="UpperCases" class="form-label">Aligners of upper jaw <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="UpperCases" name="UpperCases" value="{{ old('UpperCases', 0) }}" min="0" step="1" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="LowerCases" class="form-label">Aligners of lower jaw <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="LowerCases" name="LowerCases" value="{{ old('LowerCases', 0) }}" min="0" step="1" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <h6 class="text-secondary border-bottom pb-2 mb-3">Doctor Details</h6>

                                <div class="mb-3">
                                    <label for="region_id" class="form-label">Region <span class="text-danger">*</span></label>
                                    <select class="form-select" id="region_id" name="region_id" required>
                                        <option value="">Select region</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="area_id" class="form-label">Area <span class="text-danger">*</span></label>
                                    <select class="form-select" id="area_id" name="area_id" required disabled>
                                        <option value="">Select area</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="territory_id" class="form-label">Territory <span class="text-danger">*</span></label>
                                    <select class="form-select" id="territory_id" name="territory_id" required disabled>
                                        <option value="">Select territory</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="doctor_id" class="form-label">Doctor Name <span class="text-danger">*</span></label>
                                    <select class="form-select" id="doctor_id" name="doctor_id" required disabled>
                                        <option value="">Select doctor</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="doctorPreviewEmail" class="form-label">Doctor Email</label>
                                    <input type="text" class="form-control" id="doctorPreviewEmail" placeholder="Auto-filled from selected doctor" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="doctorPreviewMR" class="form-label">MR Name</label>
                                    <input type="text" class="form-control" id="doctorPreviewMR" placeholder="Auto-filled from selected doctor" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="doctorPreviewChamber" class="form-label">Chamber Address</label>
                                    <textarea class="form-control" id="doctorPreviewChamber" rows="3" placeholder="Auto-filled from selected doctor" readonly></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="Address" class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="Address" name="Address" rows="3" required>{{ old('Address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back to Patients
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Patient
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleOthersField() {
    const othersRadio = document.getElementById('others');
    const othersField = document.getElementById('othersField');
    
    if (othersRadio.checked) {
        othersField.classList.remove('d-none');
        othersField.querySelector('input').required = true;
    } else {
        othersField.classList.add('d-none');
        othersField.querySelector('input').required = false;
        othersField.querySelector('input').value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const regionsTree = @json($regionsTree);
    const oldRegionId = @json(old('region_id'));
    const oldAreaId = @json(old('area_id'));
    const oldTerritoryId = @json(old('territory_id'));
    const oldDoctorId = @json(old('doctor_id'));

    const regionSelect = document.getElementById('region_id');
    const areaSelect = document.getElementById('area_id');
    const territorySelect = document.getElementById('territory_id');
    const doctorSelect = document.getElementById('doctor_id');
    const doctorPreviewEmail = document.getElementById('doctorPreviewEmail');
    const doctorPreviewMR = document.getElementById('doctorPreviewMR');
    const doctorPreviewChamber = document.getElementById('doctorPreviewChamber');

    const toIdString = (v) => (v === null || v === undefined) ? '' : String(v);

    function setOptions(select, items, placeholder) {
        select.innerHTML = '';
        const first = document.createElement('option');
        first.value = '';
        first.textContent = placeholder;
        select.appendChild(first);
        items.forEach((item) => {
            const opt = document.createElement('option');
            opt.value = toIdString(item.id);
            opt.textContent = item.name;
            if (item.email) opt.dataset.email = item.email;
            if (item.chamber_address) opt.dataset.chamber = item.chamber_address;
            if (item.mr_name) opt.dataset.mr = item.mr_name;
            select.appendChild(opt);
        });
    }

    function currentRegion() {
        return regionsTree.find(r => toIdString(r.id) === regionSelect.value) || null;
    }

    function currentArea() {
        const r = currentRegion();
        if (!r) return null;
        return (r.areas || []).find(a => toIdString(a.id) === areaSelect.value) || null;
    }

    function currentTerritory() {
        const a = currentArea();
        if (!a) return null;
        return (a.territories || []).find(t => toIdString(t.id) === territorySelect.value) || null;
    }

    function populateRegions() {
        setOptions(regionSelect, regionsTree, 'Select region');
        regionSelect.disabled = false;
    }

    function populateAreas() {
        const region = currentRegion();
        setOptions(areaSelect, region ? (region.areas || []) : [], 'Select area');
        areaSelect.disabled = !region;
        areaSelect.value = '';
        populateTerritories();
    }

    function populateTerritories() {
        const area = currentArea();
        setOptions(territorySelect, area ? (area.territories || []) : [], 'Select territory');
        territorySelect.disabled = !area;
        territorySelect.value = '';
        populateDoctors();
    }

    function populateDoctors() {
        const territory = currentTerritory();
        setOptions(doctorSelect, territory ? (territory.doctors || []) : [], 'Select doctor');
        doctorSelect.disabled = !territory;
        doctorSelect.value = '';
        doctorPreviewEmail.value = '';
        doctorPreviewMR.value = '';
        doctorPreviewChamber.value = '';
    }

    function updateDoctorPreview() {
        const selected = doctorSelect.options[doctorSelect.selectedIndex];
        if (!selected || !selected.value) {
            doctorPreviewEmail.value = '';
            doctorPreviewMR.value = '';
            doctorPreviewChamber.value = '';
            return;
        }
        doctorPreviewEmail.value = selected.dataset.email || '-';
        doctorPreviewMR.value = selected.dataset.mr || '-';
        doctorPreviewChamber.value = selected.dataset.chamber || '-';
    }

    regionSelect.addEventListener('change', populateAreas);
    areaSelect.addEventListener('change', populateTerritories);
    territorySelect.addEventListener('change', populateDoctors);
    doctorSelect.addEventListener('change', updateDoctorPreview);

    populateRegions();
    if (oldRegionId) {
        regionSelect.value = toIdString(oldRegionId);
        populateAreas();
        if (oldAreaId) {
            areaSelect.value = toIdString(oldAreaId);
            populateTerritories();
            if (oldTerritoryId) {
                territorySelect.value = toIdString(oldTerritoryId);
                populateDoctors();
                if (oldDoctorId) {
                    doctorSelect.value = toIdString(oldDoctorId);
                    updateDoctorPreview();
                }
            }
        }
    }

    toggleOthersField();
});
</script>
@endsection
