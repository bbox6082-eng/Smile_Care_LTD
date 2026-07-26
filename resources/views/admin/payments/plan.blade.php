@extends('layouts.dashboard')

@section('title', 'Payments by 3D Predict ID - SmileCare')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div class="page-title">
    <i class="fas fa-file-invoice-dollar me-3"></i>
    <h1>Payments by 3D Predict ID</h1>
    <small class="text-muted ms-2">Search by Predict3DId, set total, and record payments</small>
  </div>
</div>

<div class="card mb-3">
  <div class="card-body">
    <div class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label">3D Predict ID</label>
        <input id="predictId" type="text" class="form-control" placeholder="Enter Predict3DId">
      </div>
      <div class="col-md-2">
        <button id="btnFetch" type="button" class="btn w-100 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"><i class="fas fa-search me-2"></i>Search</button>
      </div>
      <div class="col-md-7 d-flex justify-content-end">
      <div class="d-flex justify-content-end gap-2">
           <a href="{{ route('admin.patients.index') }}"
            class="btn btn-primary">
              <i class="fas fa-list me-2"></i>
              Patient List
          </a>

          <a href="{{ route('admin.payments.index') }}"
            class="btn btn-primary">
              <i class="fas fa-list me-2"></i>
              Case Management List
          </a>
      </div>
      </div>
     </div>
  </div>
</div>

<!-- Patient Information (hidden until fetched) -->
<div id="patientInfoSection" class="mb-3 d-none">
  <div class="rounded-top px-3 py-2 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <strong>Patient Information</strong>
  </div>
  <div class="border rounded-bottom p-3 bg-white">
    <div id="patientInfoBody"></div>
  </div>
</div>

<div id="planCard" class="card d-none">
  <div class="card-header">
    <h5 class="mb-0">Payment Plan</h5>
  </div>
  <div class="card-body">
    <style>
      .plan-metric {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,.08);
        background: #fff;
        box-shadow: 0 4px 14px rgba(0,0,0,.06);
        min-width: 220px;
      }
      .plan-metric-label {
        font-size: .85rem;
        font-weight: 600;
        letter-spacing: .2px;
        color: #6b7280;
        text-transform: uppercase;
      }
      .plan-metric-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: #111827;
        line-height: 1;
        white-space: nowrap;
      }
      .plan-metric-total { border-left: 6px solid #4f46e5; }
      .plan-metric-paid { border-left: 6px solid #16a34a; }
      .plan-metric-due  { border-left: 6px solid #dc2626; background: #fff5f5; }
      .plan-metric-due .plan-metric-label { color: #991b1b; }
      .plan-metric-due .plan-metric-value { color: #b91c1c; }
    </style>
    <!-- Summary Row -->
    <div class="row g-3 mb-3">
      <div class="col-12">
        <div class="d-flex flex-wrap gap-3">
          <div class="plan-metric plan-metric-total">
            <div class="plan-metric-label">Total Amount</div>
            <div class="plan-metric-value">BDT <span id="sumTotal">0.00</span></div>
          </div>
          <div class="plan-metric plan-metric-paid">
            <div class="plan-metric-label">Paid Amount</div>
            <div class="plan-metric-value">BDT <span id="sumPaid">0.00</span></div>
          </div>
          <div class="plan-metric plan-metric-due">
            <div class="plan-metric-label">Due Amount</div>
            <div class="plan-metric-value">BDT <span id="sumRemaining">0.00</span></div>
          </div>
        </div>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Payment Method</label>
        <div class="d-flex gap-3 mt-1">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="paymentMethod" id="pmCash" value="cash" checked>
            <label class="form-check-label" for="pmCash">Cash</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="paymentMethod" id="pmCard" value="card">
            <label class="form-check-label" for="pmCard">Card</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="paymentMethod" id="pmBank" value="bank_transfer">
            <label class="form-check-label" for="pmBank">Bank Transfer</label>
          </div>
          <div class="form-check">
          <input class="form-check-input"
                type="radio"
                name="paymentMethod"
                id="pmMobile"
                value="mobile_banking">

          <label class="form-check-label" for="pmMobile">
              Mobile Banking
          </label>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label">Total Amount</label>
        <div class="input-group">
          <span class="input-group-text">BDT </span>
          <input id="totalAmount" type="number" min="0" step="0.01" class="form-control" placeholder="0.00">
          @if(auth()->check() && auth()->user()->role === 'admin')
            <button id="btnEditTotal" class="btn btn-outline-secondary" type="button"><i class="fas fa-pen"></i></button>
            <button id="btnSaveTotal" class="btn btn-outline-success d-none" type="button"><i class="fas fa-save"></i></button>
          @endif
        </div>
        <small class="text-muted">Total is locked after save. Admins can click edit to update later.</small>
      </div>
      <div class="col-md-4">
        <label class="form-label">Plan Type</label>
        <div class="d-flex gap-3 mt-1">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="planType" id="ptFull" value="full" checked>
            <label class="form-check-label" for="ptFull">Full Payment</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="planType" id="ptInstallment" value="installment">
            <label class="form-check-label" for="ptInstallment">Installment</label>
          </div>
        </div>
      </div>
    </div>

  <div id="bankTransferSection" class="row g-3 mt-3 d-none">

    <div class="col-md-3">
        <label class="form-label d-flex justify-content-between align-items-center">
            <span>Bank Name</span>

            <button type="button"
                    class="btn btn-sm text-white d-flex align-items-center justify-content-center"
                    style="
                        width:25px;
                        height:25px;
                        padding:0;
                        background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
                        border:none;
                    "
                    data-bs-toggle="modal"
                    data-bs-target="#addBankModal">
                <i class="fas fa-plus"></i>
            </button>
        </label>

        <select id="bankName" name="bank_name" class="form-select">
            <option value="">Select Bank</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label d-flex justify-content-between align-items-center">
            <span>Branch Name</span>

            <button type="button"
                    class="btn btn-sm text-white d-flex align-items-center justify-content-center"
                    style="
                        width:25px;
                        height:25px;
                        padding:0;
                        background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
                        border:none;
                    "
                    data-bs-toggle="modal"
                    data-bs-target="#addBranchModal">
                <i class="fas fa-plus"></i>
            </button>
        </label>

        <select id="branchName"
                name="branch_name"
                class="form-select"
                disabled>
            <option value="">Select Branch</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Account Name</label>

        <input
            type="text"
            id="accountName"
            name="account_name"
            class="form-control"
            placeholder="Enter Account Name"
            disabled>
    </div>

    <div class="col-md-3">
        <label class="form-label">Account Number</label>

        <input
            type="text"
            id="accountNumber"
            name="account_number"
            class="form-control"
            placeholder="Enter Account Number"
            disabled>
    </div>

    </div>

    <!-- Add Bank Modal -->
    <div class="modal fade" id="addBankModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-university me-2"></i>
                        Add New Bank
                    </h5>

                    <button class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <label class="form-label">
                        Bank Name
                    </label>

                    <input
                        type="text"
                        id="newBankName"
                        class="form-control"
                        placeholder="Enter Bank Name">

                </div>

                <div class="modal-footer">

                    <button class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button
                        id="btnSaveBank"
                        class="btn btn-primary">
                        Save
                    </button>

                </div>

            </div>
        </div>
    </div>

        <!-- Add Branch Modal -->
    <div class="modal fade" id="addBranchModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-code-branch me-2"></i>
                        Add New Branch
                    </h5>

                    <button class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <label class="form-label">
                        Branch Name
                    </label>

                    <input
                        type="text"
                        id="newBranchName"
                        class="form-control"
                        placeholder="Enter Branch Name">

                </div>

                <div class="modal-footer">

                    <button class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button
                        id="btnSaveBranch"
                        class="btn btn-primary">
                        Save
                    </button>

                </div>

            </div>
        </div>
    </div>
    

    <div id="mobileBankingSection" class="row g-3 mt-3 d-none">

    <div class="col-md-4">

        <label class="form-label">
            Mobile Banking
        </label>

        <select id="mobileBank"
                name="mobile_provider"
                class="form-select">

            <option value="">Select Mobile Banking</option>

            <option value="bkash">bKash</option>

            <option value="nagad">Nagad</option>

            <option value="rocket">Rocket</option>

            <option value="upay">Upay</option>

        </select>

    </div>

    <div class="col-md-4">

        <label class="form-label">
            Account Number
        </label>

        <input
            type="text"
            id="mobileNumber"
            name="mobile_number"
            class="form-control"
            placeholder="+8801XXXXXXXXX">

    </div>

    <div class="col-md-4">

        <label class="form-label">
            Transaction ID
        </label>

        <input
            type="text"
            id="transactionId"
            name="transaction_id"
            class="form-control">

    </div>

    </div>
    

    <hr>

    <!-- Cases Summary (from patient Upper/Lower cases) -->
    <div class="row g-3 mb-3">
      <div class="col-12">
        <div class="d-flex flex-wrap gap-3">
          <div class="plan-metric plan-metric-total">
            <div class="plan-metric-label">Total Upper Cases</div>
            <div class="plan-metric-value"><span id="casesUpperTotal">0</span></div>
          </div>
          <div class="plan-metric plan-metric-total">
            <div class="plan-metric-label">Total Lower Cases</div>
            <div class="plan-metric-value"><span id="casesLowerTotal">0</span></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Deliveries -->
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
      <h6 class="mb-0">Delivery</h6>
      <div class="d-flex gap-2">
        <span id="caseClosedBadge" class="badge bg-dark d-none align-self-center">Case Closed</span>
        <button id="btnNewDelivery" type="button" class="btn btn-outline-primary btn-sm">
          <i class="fas fa-plus me-1"></i>New Delivery
        </button>
        <button id="btnDone" type="button" class="btn btn-success btn-sm d-none">
          <i class="fas fa-check me-1"></i>Done
        </button>
      </div>
    </div>
    <div id="deliveriesContainer" class="mb-3"></div>

    <!-- Remaining cases + Due -->
    <div class="row g-3">
      <div class="col-md-4">
        <div class="plan-metric plan-metric-total">
          <div class="plan-metric-label">Remaining Upper Cases</div>
          <div class="plan-metric-value"><span id="casesUpperRemaining">0</span></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="plan-metric plan-metric-total">
          <div class="plan-metric-label">Remaining Lower Cases</div>
          <div class="plan-metric-value"><span id="casesLowerRemaining">0</span></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="plan-metric plan-metric-due">
          <div class="plan-metric-label">Due Amount</div>
          <div class="plan-metric-value">BDT <span id="remaining">0.00</span></div>
        </div>
      </div>
    </div>

    <hr>

    <h6>Payment History</h6>
    <div class="table-responsive">
      <table class="table table-bordered align-middle" id="historyTable">
        <thead>
        <tr>
          <th style="width: 150px">Date</th>
          <th style="width: 150px">Method</th>
          <th>Amount</th>
        </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function(){
  const routeFind = "{{ route('admin.payments.plan.find-patient', ['predict3dId' => 'PREDICT_ID']) }}";
  const routeGet = "{{ route('admin.payments.plan.get', ['predict3dId' => 'PREDICT_ID']) }}";
  const routeSave = "{{ route('admin.payments.plan.save', ['predict3dId' => 'PREDICT_ID']) }}";
  const routeSaveInstallment = "{{ route('admin.payments.plan.add-installment', ['predict3dId' => 'PREDICT_ID']) }}";
  const routeAddDelivery = "{{ route('admin.payments.plan.add-delivery', ['predict3dId' => 'PREDICT_ID']) }}";
  const routeDone = "{{ route('admin.payments.plan.done', ['predict3dId' => 'PREDICT_ID']) }}";
  const routeUpdateTotal = "{{ route('admin.payments.plan.update-total', ['predict3dId' => 'PREDICT_ID']) }}";

  const $predict = document.getElementById('predictId');
  const $btnFetch = document.getElementById('btnFetch');
  const urlParams = new URLSearchParams(window.location.search);

  const predictId = urlParams.get("predict3d_id");

  if (predictId) {

      $predict.value = predictId;

      window.addEventListener("load", function () {

          $btnFetch.click();

      });

  }
  const $summary = document.getElementById('patientSummary'); // legacy inline summary (kept but unused)
  const $infoSection = document.getElementById('patientInfoSection');
  const $infoBody = document.getElementById('patientInfoBody');
  const $planCard = document.getElementById('planCard');
  const $remaining = document.getElementById('remaining');
  const $history = document.querySelector('#historyTable tbody');
  const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Summary badges
  const $sumTotal = document.getElementById('sumTotal');
  const $sumPaid = document.getElementById('sumPaid');
  const $sumRemaining = document.getElementById('sumRemaining');

  // Cases + deliveries
  const $casesUpperTotal = document.getElementById('casesUpperTotal');
  const $casesLowerTotal = document.getElementById('casesLowerTotal');
  const $casesUpperRemaining = document.getElementById('casesUpperRemaining');
  const $casesLowerRemaining = document.getElementById('casesLowerRemaining');
  const $deliveriesContainer = document.getElementById('deliveriesContainer');
  const $btnNewDelivery = document.getElementById('btnNewDelivery');
  const $btnDone = document.getElementById('btnDone');
  const $caseClosedBadge = document.getElementById('caseClosedBadge');

  const $pmRadios = document.querySelectorAll('input[name="paymentMethod"]');
  const $ptFull = document.getElementById('ptFull');
  const $ptInstall = document.getElementById('ptInstallment');
  const $totalAmount = document.getElementById('totalAmount');
  const $btnEditTotal = document.getElementById('btnEditTotal');
  const $btnSaveTotal = document.getElementById('btnSaveTotal');

  const bankSection = document.getElementById("bankTransferSection");

  const bankSelect = document.getElementById("bankName");

  const branchSelect = document.getElementById("branchName");

  const accountName = document.getElementById("accountName");

  const accountNumber = document.getElementById("accountNumber");
  let currentPredict = '';
  let totalLocked = false;
  let caseClosed = false;
  let casesState = { total_upper: 0, total_lower: 0, delivered_upper: 0, delivered_lower: 0, remaining_upper: 0, remaining_lower: 0 };

  function selectedMethod(){
    for(const r of $pmRadios){ if(r.checked) return r.value; }
    return 'cash';
  }

  function toggleBankTransfer() {

    if (selectedMethod() === "bank_transfer") {

        bankSection.classList.remove("d-none");

        mobileSection.classList.add("d-none");

        bankSelect.disabled = false;

    } else {

        bankSection.classList.add("d-none");

        bankSelect.value = "";

        branchSelect.innerHTML =
        '<option value="">Select Branch</option>';

        branchSelect.disabled = true;

        accountName.value = "";
        accountNumber.value = "";

        accountName.disabled = true;
        accountNumber.disabled = true;

    }

}



// Bank Transfer
const btnSaveBank = document.getElementById('btnSaveBank');

async function loadBanks(selectedBankId = null) {

    const response = await fetch("{{ route('admin.banks.index') }}");

    const banks = await response.json();

    const bankSelect = document.getElementById('bankName');

    bankSelect.innerHTML = '<option value="">Select Bank</option>';

    banks.forEach(function(bank){

        const option = document.createElement('option');

        option.value = bank.id;
        option.text = bank.bank_name;

        if(selectedBankId && bank.id == selectedBankId){
            option.selected = true;
        }

        bankSelect.appendChild(option);

    });

}

btnSaveBank.addEventListener('click', async function () {

    const bankName = document.getElementById('newBankName').value.trim();

    if(bankName === ''){
        alert('Please enter Bank Name');
        return;
    }

    try{

        const response = await fetch("{{ route('admin.banks.store') }}",{

            method:'POST',

            headers:{
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },

            body:JSON.stringify({
                bank_name:bankName
            })

        });

        const bank = await response.json();
        console.log(bank);

        const option = document.createElement('option');

        option.value = bank.id;
        option.text = bank.bank_name;
        option.selected = true;

        document.getElementById('bankName').appendChild(option);
        await loadBanks(bank.id);

        bootstrap.Modal.getInstance(document.getElementById('addBankModal')).hide();

        document.getElementById('newBankName').value='';

    }catch(e){

        alert('Failed to save bank');

        console.log(e);

    }

});

loadBanks();

// Save Branch
const btnSaveBranch = document.getElementById('btnSaveBranch');

btnSaveBranch.addEventListener('click', async function () {

    const bankId = document.getElementById('bankName').value;
    const branchName = document.getElementById('newBranchName').value.trim();

    if (bankId === '') {
        alert('Please select a bank first.');
        return;
    }

    if (branchName === '') {
        alert('Please enter Branch Name.');
        return;
    }

    try {

        const response = await fetch("{{ route('admin.bank-branches.store') }}", {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },

            body: JSON.stringify({
                bank_id: bankId,
                branch_name: branchName
            })

        });

        const branch = await response.json();

        console.log(branch);

        const option = document.createElement('option');

        option.value = branch.id;
        option.text = branch.branch_name;
        option.selected = true;

        document.getElementById('branchName').appendChild(option);

        document.getElementById('branchName').disabled = false;

        bootstrap.Modal.getInstance(
            document.getElementById('addBranchModal')
        ).hide();

        document.getElementById('newBranchName').value = '';

        accountName.disabled = false;
        accountNumber.disabled = false;

    } catch (e) {

        console.log(e);

        alert('Failed to save branch.');

    }

});



  const mobileSection = document.getElementById("mobileBankingSection");

function toggleMobileBanking() {

    if (selectedMethod() === "mobile_banking") {

        mobileSection.classList.remove("d-none");

        bankSection.classList.add("d-none");

    } else {

        mobileSection.classList.add("d-none");

    }

  }

  function recalcSummaryFromInput() {
    const total = parseFloat($totalAmount.value || '0') || 0;
    const paid = parseFloat($sumPaid.textContent || '0') || 0;
    const due = Math.max(0, total - paid);
    $sumTotal.textContent = total.toFixed(2);
    $sumRemaining.textContent = due.toFixed(2);
    $remaining.textContent = due.toFixed(2);
    renderCases();
  }

  function togglePlanType(){ /* deliveries UI is used for both types */ }
  document.querySelectorAll('input[name="planType"]').forEach(r => r.addEventListener('change', togglePlanType));

 $pmRadios.forEach(r => {

    r.addEventListener("change", () => {

        toggleBankTransfer();
        toggleMobileBanking();

    });

  });

  bankSelect.addEventListener("change", async function () {

    branchSelect.innerHTML =
        '<option value="">Select Branch</option>';

    accountName.value = '';
    accountNumber.value = '';

    accountName.disabled = true;
    accountNumber.disabled = true;

    if (this.value === '') {

        branchSelect.disabled = true;
        return;

    }

    try {

        const response = await fetch(
            '/admin/banks/' + this.value + '/branches'
        );

        const branches = await response.json();

        branches.forEach(function (branch) {

            const option = document.createElement('option');

            option.value = branch.id;
            option.text = branch.branch_name;

            branchSelect.appendChild(option);

        });

        branchSelect.disabled = false;

    } catch (e) {

        console.log(e);

        alert('Failed to load branches.');

    }

});

  branchSelect.addEventListener("change", function(){

    if(this.value===""){

        accountName.disabled = true;

        accountNumber.disabled = true;

        accountName.value = "";

        accountNumber.value = "";

    }else{

        accountName.disabled = false;

        accountNumber.disabled = false;

    }

  });

  function renderCases() {
    if ($casesUpperTotal) $casesUpperTotal.textContent = String(casesState.total_upper ?? 0);
    if ($casesLowerTotal) $casesLowerTotal.textContent = String(casesState.total_lower ?? 0);
    if ($casesUpperRemaining) $casesUpperRemaining.textContent = String(casesState.remaining_upper ?? 0);
    if ($casesLowerRemaining) $casesLowerRemaining.textContent = String(casesState.remaining_lower ?? 0);

    const due = parseFloat($sumRemaining.textContent || '0') || 0;
    const done = (casesState.remaining_upper === 0 && casesState.remaining_lower === 0 && due === 0);
    if ($btnDone) $btnDone.classList.toggle('d-none', caseClosed);
  }

  function applyLockState(isClosed) {
    caseClosed = !!isClosed;
    if ($caseClosedBadge) $caseClosedBadge.classList.toggle('d-none', !caseClosed);

    // Disable editing controls
    if ($totalAmount) $totalAmount.disabled = caseClosed || $totalAmount.disabled;
    if ($btnEditTotal) $btnEditTotal.classList.toggle('d-none', caseClosed);
    if ($btnSaveTotal) $btnSaveTotal.classList.add('d-none');
    if ($btnNewDelivery) $btnNewDelivery.classList.toggle('d-none', caseClosed);

    if ($pmRadios) $pmRadios.forEach(r => r.disabled = caseClosed);
    if ($ptFull) $ptFull.disabled = caseClosed;
    if ($ptInstall) $ptInstall.disabled = caseClosed;

    if (caseClosed && $deliveriesContainer) {
      $deliveriesContainer.querySelectorAll('[data-delivery-draft="1"]').forEach(el => el.remove());
    }
    renderCases();
  }

  function deliveryTemplate(idx) {
    const today = "{{ date('Y-m-d') }}";
    return `
      <div class="card mb-2" data-delivery-draft="1">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
            <div class="fw-semibold">Delivered</div>
            <div class="text-muted small">Delivery #${idx}</div>
          </div>
          <div class="row g-3 align-items-end">
            <div class="col-md-2">
              <label class="form-label">Upper cases</label>
              <input type="number" min="0" step="1" class="form-control js-upper" value="0">
            </div>
            <div class="col-md-2">
              <label class="form-label">Lower cases</label>
              <input type="number" min="0" step="1" class="form-control js-lower" value="0">
            </div>
            <div class="col-md-3">
              <label class="form-label">Paid amount</label>
              <div class="input-group">
                <span class="input-group-text">BDT </span>
                <input type="number" min="0" step="0.01" class="form-control js-paid" value="0.00">
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label">Delivery date</label>
              <input type="date" class="form-control js-date" value="${today}">
            </div>
            <div class="col-md-2 d-flex gap-2">
              <button type="button" class="btn btn-success w-100 js-save">
                <i class="fas fa-save me-1"></i>Save
              </button>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  function addNewDeliveryDraft() {
    if (!$deliveriesContainer) return;
    const count = $deliveriesContainer.querySelectorAll('[data-delivery-draft="1"]').length + 1;
    $deliveriesContainer.insertAdjacentHTML('beforeend', deliveryTemplate(count));
  }

  if ($btnNewDelivery) {
    $btnNewDelivery.addEventListener('click', () => {
      if (!currentPredict) { alert('Fetch a patient first'); return; }
      addNewDeliveryDraft();
    });
  }

  if ($btnDone) {
    $btnDone.addEventListener('click', async () => {
      if (!currentPredict) { alert('Fetch a patient first'); return; }
      if (!confirm('Are you sure you want to mark this case as done?')) return;
      $btnDone.disabled = true;
      try {
        const url = routeDone.replace('PREDICT_ID', encodeURIComponent(currentPredict));
        const res = await fetch(url, {
          method: 'POST',
          headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        });
        const contentType = res.headers.get('content-type') || '';
        if (!res.ok) {
          let msg = 'Failed to mark case done';
          if (contentType.includes('application/json')) {
            const j = await res.json();
            msg = j.message || msg;
          } else {
            msg = await res.text() || msg;
          }
          throw new Error(msg);
        }
        alert('Case marked as done successfully.');
        applyLockState(true);
      } catch (err) {
        alert(err.message || 'Error marking done');
      } finally {
        $btnDone.disabled = false;
      }
    });
  }

  function computeAge(iso){
    if(!iso) return '';
    const d = new Date(iso); if(Number.isNaN(d.getTime())) return '';
    const years = Math.floor((Date.now()-d.getTime())/(365.25*24*60*60*1000));
    return years>0 ? years+" years" : '';
  }

  function fmtDate(val){
    if(!val) return '';
    const d = new Date(val);
    if(Number.isNaN(d.getTime())) return String(val);
    const y = d.getFullYear();
    const m = String(d.getMonth()+1).padStart(2,'0');
    const day = String(d.getDate()).padStart(2,'0');
    return `${y}-${m}-${day}`;
  }

  function fmtMethod(val){
    if(!val) return '';
    return String(val).replace(/_/g,' ').replace(/^./, c => c.toUpperCase());
  }

  $btnFetch.addEventListener('click', async () => {
    const id = ($predict.value||'').trim();
    if(!id){ alert('Enter Predict3DId'); return; }
    $summary && $summary.classList.add('d-none');
    $infoSection.classList.add('d-none');
    $history.innerHTML='';
    try{
      const urlP = routeFind.replace('PREDICT_ID', encodeURIComponent(id));
      const resP = await fetch(urlP, { headers: { 'Accept': 'application/json' } });
      if(!resP.ok) throw new Error('Patient not found');
      const p = await resP.json();
      const age = computeAge(p.DateOfBirth);
      const scanningFor = (p.ScanningFor === 'Others' && p.ScanningForOthers) ? `${p.ScanningFor} (${p.ScanningForOthers})` : (p.ScanningFor || '');
      $infoBody.innerHTML = `
        <div class="row g-3">
          <div class="col-md-6">
            <div class="d-flex align-items-center"><span class="fw-semibold me-2">Patient Name:</span><span>${p.FullName ?? ''}</span></div>
            <div class="d-flex align-items-center"><span class="fw-semibold me-2">Doctor Name:</span><span>${p.DoctorName ?? ''}</span></div>
            <div class="d-flex align-items-center"><span class="fw-semibold me-2">Scanning For:</span><span>${scanningFor}</span></div>
          </div>
          <div class="col-md-6">
            <div class="d-flex align-items-center"><span class="fw-semibold me-2">Gender:</span><span>${p.Gender ?? ''}</span></div>
            <div class="d-flex align-items-center"><span class="fw-semibold me-2">Age:</span><span>${age}</span></div>
            <div class="d-flex align-items-center"><span class="fw-semibold me-2">Phone Number:</span><span>${p.PhoneNumber ?? ''}</span></div>
          </div>
        </div>
        <hr class="my-3"/>
        <div class="small text-muted">3D Predict ID: <span class="fw-semibold">${p.Predict3DId}</span></div>`;
      $infoSection.classList.remove('d-none');
      currentPredict = id;

      // Show plan UI immediately with defaults; fill actual data after we fetch plan
      $planCard.classList.remove('d-none');
      $totalAmount.disabled = false; $totalAmount.value = '';
      applyLockState(false);
      $ptFull.checked = true; togglePlanType();
      $remaining.textContent = '0.00';
      $sumTotal.textContent = '0.00';
      $sumPaid.textContent = '0.00';
      $sumRemaining.textContent = '0.00';
      recalcSummaryFromInput();

      const urlG = routeGet.replace('PREDICT_ID', encodeURIComponent(id));
      const resG = await fetch(urlG, { headers: { 'Accept': 'application/json' } });
      if(resG.ok){
        const data = await resG.json();
        const plan = data.plan || null;
        const pays = data.payments || [];
        const deliveries = data.deliveries || [];
        casesState = data.cases || casesState;
        const paid = parseFloat(data.paid || 0);
        const remaining = plan ? (parseFloat(plan.total_amount) - paid) : 0;
        $remaining.textContent = (remaining||0).toFixed(2);
        $history.innerHTML = pays.map(x=>`<tr><td>${fmtDate(x.payment_date)}</td><td>${fmtMethod(x.payment_method)}</td><td>BDT ${parseFloat(x.amount).toFixed(2)}</td></tr>`).join('');
        // Update badges
        $sumTotal.textContent = plan ? parseFloat(plan.total_amount).toFixed(2) : '0.00';
        $sumPaid.textContent = (paid||0).toFixed(2);
        $sumRemaining.textContent = (remaining||0).toFixed(2);
        if ($deliveriesContainer) {
          $deliveriesContainer.innerHTML = '';
          deliveries.forEach((d, i) => {
            $deliveriesContainer.insertAdjacentHTML('beforeend', `
              <div class="card mb-2">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                    <div class="fw-semibold">Delivered</div>
                    <div class="text-muted small">Delivery #${i + 1} · ${fmtDate(d.delivery_date)}</div>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-3"><div class="small text-muted">Upper delivered</div><div class="fw-semibold">${parseInt(d.upper_delivered || 0, 10)}</div></div>
                    <div class="col-md-3"><div class="small text-muted">Lower delivered</div><div class="fw-semibold">${parseInt(d.lower_delivered || 0, 10)}</div></div>
                    <div class="col-md-3"><div class="small text-muted">Paid</div><div class="fw-semibold">BDT ${parseFloat(d.paid_amount || 0).toFixed(2)}</div></div>
                    <div class="col-md-3"><div class="small text-muted">Date</div><div class="fw-semibold">${fmtDate(d.delivery_date)}</div></div>
                  </div>
                </div>
              </div>
            `);
          });
          renderCases();
          addNewDeliveryDraft();
        }
        // Ensure patient info stays visible
        $infoSection.classList.remove('d-none');
        if(plan){
          $planCard.classList.remove('d-none');
          $totalAmount.value = parseFloat(plan.total_amount).toFixed(2);
          totalLocked = true; $totalAmount.disabled = true;
          ($pmRadios.forEach(r=> r.checked = (r.value===plan.payment_method)));
          if(plan.is_installment){ $ptInstall.checked = true; } else { $ptFull.checked = true; }
          togglePlanType();
          applyLockState(!!plan.is_closed);
        } else {
          $planCard.classList.remove('d-none');
          totalLocked = false; $totalAmount.disabled = false; $totalAmount.value = '';
          recalcSummaryFromInput();
          $ptFull.checked = true; togglePlanType();
          applyLockState(false);
        }
      } else {
        // Keep patient info visible and allow creating a new plan
        $infoSection.classList.remove('d-none');
        $planCard.classList.remove('d-none');
        $totalAmount.disabled = false; $totalAmount.value = '';
        $ptFull.checked = true; togglePlanType();
        $history.innerHTML = '';
        $remaining.textContent = '0.00';
        $sumTotal.textContent = '0.00';
        $sumPaid.textContent = '0.00';
        $sumRemaining.textContent = '0.00';
        recalcSummaryFromInput();
        if ($deliveriesContainer) { $deliveriesContainer.innerHTML = ''; renderCases(); addNewDeliveryDraft(); }
        applyLockState(false);
      }
    }catch(e){
      $summary.innerHTML = `<span class='text-danger'>${e.message||'Failed to fetch'}</span>`;
      $summary.classList.remove('d-none');
      // Keep patient info visible and show empty plan so user can proceed after re-fetch
      $infoSection.classList.remove('d-none');
      $planCard.classList.remove('d-none');
      $totalAmount.disabled = false; $totalAmount.value = '';
      $ptFull.checked = true; togglePlanType();
      $history.innerHTML = '';
      $remaining.textContent = '0.00';
      $sumTotal.textContent = '0.00';
      $sumPaid.textContent = '0.00';
      $sumRemaining.textContent = '0.00';
      recalcSummaryFromInput();
      if ($deliveriesContainer) { $deliveriesContainer.innerHTML = ''; renderCases(); }
      applyLockState(false);
      currentPredict='';
    }
  });

  // Open detail directly when redirected from payments table
  const qp = new URLSearchParams(window.location.search);
  const qPredict = (qp.get('predict3d_id') || '').trim();
  if (qPredict) {
    $predict.value = qPredict;
    $btnFetch.click();
  }

  $btnEditTotal.addEventListener('click', ()=>{
    $totalAmount.disabled = false; $btnSaveTotal.classList.remove('d-none');
  });

  $totalAmount.addEventListener('input', recalcSummaryFromInput);
  $totalAmount.addEventListener('change', recalcSummaryFromInput);

  $btnSaveTotal.addEventListener('click', async ()=>{
    if(!currentPredict){ alert('Fetch a patient first'); return; }
    const amt = parseFloat($totalAmount.value);
    if(Number.isNaN(amt) || amt<0){ alert('Enter a valid total'); return; }
    try{
      const url = routeUpdateTotal.replace('PREDICT_ID', encodeURIComponent(currentPredict));
      const res = await fetch(url, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ total_amount: amt })
      });
      const contentType = res.headers.get('content-type')||'';
      if(!res.ok){
        let msg = 'Failed to update total';
        if(contentType.includes('application/json')){ const j = await res.json(); msg = j.message||msg; }
        else { msg = await res.text() || msg; }
        throw new Error(msg);
      }
      const data = await res.json();
      $totalAmount.value = parseFloat(data.plan.total_amount).toFixed(2);
      $totalAmount.disabled = true; $btnSaveTotal.classList.add('d-none');
      // Refresh badges; remaining = total - already paid
      const paidNow = Array.from($history.querySelectorAll('tr td:nth-child(3)')).reduce((s,td)=>{
        const v = parseFloat((td.textContent||'').replace(/[^\d.\-]/g,''));
        return s + (isNaN(v)?0:v);
      },0);
      $sumTotal.textContent = parseFloat(data.plan.total_amount||0).toFixed(2);
      $sumPaid.textContent = paidNow.toFixed(2);
      $sumRemaining.textContent = Math.max(0, parseFloat(data.plan.total_amount||0) - paidNow).toFixed(2);
      alert('Total updated');
    }catch(e){ alert(e.message||'Error updating total'); }
  });

  document.querySelectorAll('input[name="planType"]').forEach(r=>r.addEventListener('change', ()=>{
    togglePlanType();
  }));

  // Save a delivery (delegated)
  if ($deliveriesContainer) {
    $deliveriesContainer.addEventListener('click', async (e) => {
      const btn = e.target.closest('.js-save');
      if (!btn) return;
      if (caseClosed) { alert('This case is closed. No new delivery can be added.'); return; }
      if (!currentPredict) { alert('Fetch a patient first'); return; }
      const card = btn.closest('[data-delivery-draft=\"1\"]');
      if (!card) return;

      const upper = parseInt(card.querySelector('.js-upper').value || '0', 10) || 0;
      const lower = parseInt(card.querySelector('.js-lower').value || '0', 10) || 0;
      const paidAmt = parseFloat(card.querySelector('.js-paid').value || '0') || 0;
      const date = card.querySelector('.js-date').value;

      if (!date) { alert('Select a delivery date'); return; }
      if (upper < 0 || lower < 0) { alert('Cases must be 0 or greater'); return; }
      if (paidAmt < 0) { alert('Paid amount must be 0 or greater'); return; }

      const total = parseFloat($totalAmount.value || '0') || 0;
      if (total <= 0) { alert('Set Total Amount first'); return; }

      btn.disabled = true;
      try {
        const url = routeAddDelivery.replace('PREDICT_ID', encodeURIComponent(currentPredict));
        const res = await fetch(url, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
          body: JSON.stringify({

              upper_delivered: upper,
              lower_delivered: lower,
              paid_amount: paidAmt,
              delivery_date: date,
              payment_method: selectedMethod(),
              total_amount: parseFloat($totalAmount.value || '0') || 0,

              bank_name: bankSelect.value,
              branch_name: branchSelect.value,
              account_name: accountName.value,

              account_number: selectedMethod() === "mobile_banking"
                  ? document.getElementById("mobileNumber").value
                  : accountNumber.value,

              mobile_provider: document.getElementById("mobileBank").value,
              transaction_id: document.getElementById("transactionId").value

          })
        });
        const contentType = res.headers.get('content-type') || '';
        if (!res.ok) {
          let msg = 'Failed to save delivery';
          if (contentType.includes('application/json')) {
            const j = await res.json();
            msg = j.message || msg;
          } else {
            msg = await res.text() || msg;
          }
          throw new Error(msg);
        }

        const data = await res.json();
        const plan = data.plan;
        const pays = data.payments || [];
        const deliveries = data.deliveries || [];
        casesState = data.cases || casesState;

        const paidNow = pays.reduce((s, x) => s + parseFloat(x.amount || 0), 0);
        const remainingNow = Math.max(0, parseFloat(plan.total_amount || 0) - paidNow);
        $remaining.textContent = remainingNow.toFixed(2);
        $sumTotal.textContent = parseFloat(plan.total_amount || 0).toFixed(2);
        $sumPaid.textContent = paidNow.toFixed(2);
        $sumRemaining.textContent = remainingNow.toFixed(2);

        $history.innerHTML = pays.map(x=>`<tr><td>${fmtDate(x.payment_date)}</td><td>${fmtMethod(x.payment_method)}</td><td>BDT ${parseFloat(x.amount).toFixed(2)}</td></tr>`).join('');

        $deliveriesContainer.innerHTML = '';
        deliveries.forEach((d, i) => {
          $deliveriesContainer.insertAdjacentHTML('beforeend', `
            <div class=\"card mb-2\">
              <div class=\"card-body\">
                <div class=\"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2\">
                  <div class=\"fw-semibold\">Delivered</div>
                  <div class=\"text-muted small\">Delivery #${i + 1} · ${fmtDate(d.delivery_date)}</div>
                </div>
                <div class=\"row g-3\">
                  <div class=\"col-md-3\"><div class=\"small text-muted\">Upper delivered</div><div class=\"fw-semibold\">${parseInt(d.upper_delivered || 0, 10)}</div></div>
                  <div class=\"col-md-3\"><div class=\"small text-muted\">Lower delivered</div><div class=\"fw-semibold\">${parseInt(d.lower_delivered || 0, 10)}</div></div>
                  <div class=\"col-md-3\"><div class=\"small text-muted\">Paid</div><div class=\"fw-semibold\">BDT ${parseFloat(d.paid_amount || 0).toFixed(2)}</div></div>
                  <div class=\"col-md-3\"><div class=\"small text-muted\">Date</div><div class=\"fw-semibold\">${fmtDate(d.delivery_date)}</div></div>
                </div>
              </div>
            </div>
          `);
        });
        renderCases();
        addNewDeliveryDraft();
      } catch (err) {
        alert(err.message || 'Error saving delivery');
      } finally {
        btn.disabled = false;
      }
    });
  }
})();
</script>
@endpush
@endsection
