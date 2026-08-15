@extends('layouts.dashboard')

@section('title', 'Add Doctor - SmileCare')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>Add New Doctor
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

                    <form action="{{ route('admin.doctors.store') }}" method="POST">
                        @csrf

                        <h6 class="text-secondary border-bottom pb-2 mb-3">Doctor details</h6>
                        <div class="mb-3">
                            <label for="name" class="form-label">Dr. Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="chamber_name" class="form-label">Chamber Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="chamber_name" name="chamber_name" value="{{ old('chamber_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="chamber_address" class="form-label">Chamber address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="chamber_address" name="chamber_address" rows="3" required>{{ old('chamber_address') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="chamber_map_link" class="form-label">Chamber Google Maps link <span class="text-muted">(optional)</span></label>
                            <input type="url" class="form-control" id="chamber_map_link" name="chamber_map_link" value="{{ old('chamber_map_link') }}" placeholder="https://maps.google.com/...">
                            <small class="text-muted">Paste the full Google Maps location link for this chamber.</small>
                        </div>
                        <div class="mb-3">
                            <label for="mobile_number" class="form-label">Mobile number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}" required placeholder="e.g. 01XXXXXXXXX">
                        </div>

                        <h6 class="text-secondary border-bottom pb-2 mb-3 mt-4">Location (hierarchy)</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="region_select" class="form-label">Region <span class="text-danger">*</span></label>
                                <select id="region_select" class="form-select">
                                    <option value="">Select region</option>
                                </select>
                                <input type="text" class="form-control mt-2 d-none" id="region_new" placeholder="Enter new region">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="area_select" class="form-label">Area <span class="text-danger">*</span></label>
                                <select id="area_select" class="form-select" disabled>
                                    <option value="">Select area</option>
                                </select>
                                <input type="text" class="form-control mt-2 d-none" id="area_new" placeholder="Enter new area">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="territory_select" class="form-label">Territory <span class="text-danger">*</span></label>
                                <select id="territory_select" class="form-select" disabled>
                                    <option value="">Select territory</option>
                                </select>
                                <input type="text" class="form-control mt-2 d-none" id="territory_new" placeholder="Enter new territory">
                            </div>
                        </div>
                        <input type="hidden" name="region_name" id="region_name" value="{{ old('region_name') }}">
                        <input type="hidden" name="area_name" id="area_name" value="{{ old('area_name') }}">
                        <input type="hidden" name="territory_name" id="territory_name" value="{{ old('territory_name') }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-muted">(optional)</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="doctor@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control" id="note" name="note" rows="3" placeholder="Doctor description — e.g. specialization, practice focus, or other relevant details">{{ old('note') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="marketing_representative_name" class="form-label">Marketing Representative <span class="text-danger">*</span></label>
                            <select class="form-select" id="marketing_representative_name" name="marketing_representative_name" required>
                                <option value="">Select Marketing Representative</option>
                                @foreach($mrOptions as $mr)
                                    <option value="{{ $mr }}" @selected(old('marketing_representative_name') === $mr)>{{ $mr }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Doctor
                            </button>
                            <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(() => {
    const regionsData = @json($regionsTree);

    const ADD_NEW = '__add_new__';
    const initialRegion = @json(old('region_name'));
    const initialArea = @json(old('area_name'));
    const initialTerritory = @json(old('territory_name'));
    const regionSelect = document.getElementById('region_select');
    const areaSelect = document.getElementById('area_select');
    const territorySelect = document.getElementById('territory_select');
    const regionNew = document.getElementById('region_new');
    const areaNew = document.getElementById('area_new');
    const territoryNew = document.getElementById('territory_new');
    const hRegion = document.getElementById('region_name');
    const hArea = document.getElementById('area_name');
    const hTerritory = document.getElementById('territory_name');
    const form = document.querySelector('form[action="{{ route('admin.doctors.store') }}"]');

    function setOptions(select, items, placeholder) {
        select.innerHTML = '';
        const base = document.createElement('option');
        base.value = '';
        base.textContent = placeholder;
        select.appendChild(base);
        items.forEach(item => {
            const o = document.createElement('option');
            o.value = item;
            o.textContent = item;
            select.appendChild(o);
        });
        const add = document.createElement('option');
        add.value = ADD_NEW;
        add.textContent = '+ Add new';
        select.appendChild(add);
    }

    function getSelectedRegionObj() {
        return regionsData.find(r => r.name === regionSelect.value) || null;
    }
    function getSelectedAreaObj() {
        const r = getSelectedRegionObj();
        if (!r) return null;
        return r.areas.find(a => a.name === areaSelect.value) || null;
    }

    function refreshAreaOptions() {
        if (regionSelect.value && regionSelect.value !== ADD_NEW) {
            const r = getSelectedRegionObj();
            setOptions(areaSelect, (r?.areas || []).map(a => a.name), 'Select area');
            areaSelect.disabled = false;
            areaSelect.value = '';
            refreshTerritoryOptions();
        } else if (regionSelect.value === ADD_NEW) {
            setOptions(areaSelect, [], 'Select area');
            areaSelect.disabled = false;
            areaSelect.value = ADD_NEW;
            refreshTerritoryOptions(true);
        } else {
            setOptions(areaSelect, [], 'Select area');
            areaSelect.disabled = true;
            areaSelect.value = '';
            refreshTerritoryOptions();
        }
    }

    function refreshTerritoryOptions(forceAddNew = false) {
        if (forceAddNew) {
            setOptions(territorySelect, [], 'Select territory');
            territorySelect.disabled = false;
            territorySelect.value = ADD_NEW;
        } else if (areaSelect.value && areaSelect.value !== ADD_NEW) {
            const a = getSelectedAreaObj();
            setOptions(territorySelect, (a?.territories || []), 'Select territory');
            territorySelect.disabled = false;
            territorySelect.value = '';
        } else if (areaSelect.value === ADD_NEW) {
            setOptions(territorySelect, [], 'Select territory');
            territorySelect.disabled = false;
            territorySelect.value = ADD_NEW;
        } else {
            setOptions(territorySelect, [], 'Select territory');
            territorySelect.disabled = true;
            territorySelect.value = '';
        }
    }

    function toggleNewInputs() {
        const regionAdd = regionSelect.value === ADD_NEW;
        const areaAdd = areaSelect.value === ADD_NEW || regionAdd;
        const territoryAdd = territorySelect.value === ADD_NEW || areaAdd;

        regionNew.classList.toggle('d-none', !regionAdd);
        areaNew.classList.toggle('d-none', !areaAdd);
        territoryNew.classList.toggle('d-none', !territoryAdd);
    }

    function init() {
        setOptions(regionSelect, regionsData.map(r => r.name), 'Select region');
        setOptions(areaSelect, [], 'Select area');
        setOptions(territorySelect, [], 'Select territory');
        areaSelect.disabled = true;
        territorySelect.disabled = true;

        if (initialRegion) {
            const regionExists = regionsData.some(r => r.name === initialRegion);
            if (regionExists) {
                regionSelect.value = initialRegion;
                refreshAreaOptions();
                const regionObj = getSelectedRegionObj();
                const areaExists = (regionObj?.areas || []).some(a => a.name === initialArea);
                if (areaExists) {
                    areaSelect.value = initialArea;
                    refreshTerritoryOptions();
                    const areaObj = getSelectedAreaObj();
                    const territoryExists = (areaObj?.territories || []).includes(initialTerritory);
                    if (territoryExists) {
                        territorySelect.value = initialTerritory;
                    } else if (initialTerritory) {
                        territorySelect.value = ADD_NEW;
                        territoryNew.value = initialTerritory;
                    }
                } else {
                    areaSelect.value = ADD_NEW;
                    areaNew.value = initialArea || '';
                    refreshTerritoryOptions();
                    territorySelect.value = ADD_NEW;
                    territoryNew.value = initialTerritory || '';
                }
            } else {
                regionSelect.value = ADD_NEW;
                regionNew.value = initialRegion;
                refreshAreaOptions();
                areaSelect.value = ADD_NEW;
                areaNew.value = initialArea || '';
                refreshTerritoryOptions();
                territorySelect.value = ADD_NEW;
                territoryNew.value = initialTerritory || '';
            }
        }
        toggleNewInputs();
    }

    regionSelect.addEventListener('change', () => {
        refreshAreaOptions();
        toggleNewInputs();
    });
    areaSelect.addEventListener('change', () => {
        refreshTerritoryOptions();
        toggleNewInputs();
    });
    territorySelect.addEventListener('change', toggleNewInputs);

    form.addEventListener('submit', (e) => {
        const regionValue = regionSelect.value === ADD_NEW ? regionNew.value.trim() : regionSelect.value.trim();
        const areaValue = (regionSelect.value === ADD_NEW || areaSelect.value === ADD_NEW)
            ? areaNew.value.trim() : areaSelect.value.trim();
        const territoryValue = (regionSelect.value === ADD_NEW || areaSelect.value === ADD_NEW || territorySelect.value === ADD_NEW)
            ? territoryNew.value.trim() : territorySelect.value.trim();

        if (!regionValue || !areaValue || !territoryValue) {
            e.preventDefault();
            alert('Please select or add Region, Area and Territory.');
            return;
        }
        hRegion.value = regionValue;
        hArea.value = areaValue;
        hTerritory.value = territoryValue;
    });

    init();
})();
</script>
@endpush
@endsection
