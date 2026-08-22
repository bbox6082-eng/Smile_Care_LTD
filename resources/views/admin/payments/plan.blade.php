@extends('layouts.dashboard')

@section('title', 'Payments by 3D Predict ID - SmileCare')

@section('content')

<style>
    .payment-page .card {
        border: 0;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
    }

    .payment-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border-radius: 10px 10px 0 0;
    }

    .payment-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .plan-metric {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 14px 18px;
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,.08);
        background: #fff;
        box-shadow: 0 4px 14px rgba(0,0,0,.05);
        min-width: 220px;
    }

    .plan-metric-label {
        font-size: .82rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .plan-metric-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: #111827;
        white-space: nowrap;
    }

    .plan-metric-total {
        border-left: 6px solid #4f46e5;
    }

    .plan-metric-paid {
        border-left: 6px solid #16a34a;
    }

    .plan-metric-due {
        border-left: 6px solid #dc2626;
        background: #fff5f5;
    }

    .plan-metric-due .plan-metric-label {
        color: #991b1b;
    }

    .plan-metric-due .plan-metric-value {
        color: #b91c1c;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
    }

    .delivery-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
    }

    .delivery-card .delivery-header {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        padding: 14px 18px;
        border-radius: 12px 12px 0 0;
    }

    .delivery-draft {
        border: 2px solid #667eea;
        border-radius: 12px;
        background: #fff;
    }

    .delivery-draft .delivery-header {
        background: linear-gradient(
            135deg,
            rgba(102,126,234,.10),
            rgba(118,75,162,.08)
        );
        border-bottom: 1px solid #e5e7eb;
        padding: 14px 18px;
        border-radius: 10px 10px 0 0;
    }

    .empty-history {
        padding: 30px 15px;
        text-align: center;
        color: #6b7280;
    }

    .payment-badge {
        min-width: 120px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .form-label {
        font-weight: 600;
        color: #1f2937;
    }

    .plan-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    @media (max-width: 768px) {
        .plan-actions {
            justify-content: flex-start;
        }

        .plan-metric {
            width: 100%;
        }
    }
</style>

<div class="payment-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div class="page-title">
            <div class="d-flex align-items-center">
                <i class="fas fa-file-invoice-dollar me-3"></i>

                <div>
                    <h1 class="mb-0">Payments by 3D Predict ID</h1>

                    <small class="text-muted">
                        Search patient, create payment plan and manage deliveries
                    </small>
                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         STEP 1 — SEARCH PATIENT
    ========================================================== --}}
    <div class="card mb-3">

        <div class="card-body">

            <div class="row g-3 align-items-end">

                <div class="col-md-4">

                    <label for="predictId" class="form-label">
                        3D Predict ID
                    </label>

                    <input
                        id="predictId"
                        type="text"
                        class="form-control"
                        placeholder="Enter Predict3DId"
                    >

                </div>

                <div class="col-md-2">

                    <button
                        id="btnFetch"
                        type="button"
                        class="btn w-100 text-white"
                        style="background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);"
                    >
                        <i class="fas fa-search me-2"></i>
                        Search
                    </button>

                </div>

                <div class="col-md-6">

                    <div class="d-flex justify-content-md-end gap-2 flex-wrap">

                        <a
                            href="{{ route('admin.patients.index') }}"
                            class="btn btn-outline-primary"
                        >
                            <i class="fas fa-users me-2"></i>
                            Patient List
                        </a>

                        <a
                            href="{{ route('admin.payments.index') }}"
                            class="btn btn-outline-primary"
                        >
                            <i class="fas fa-list me-2"></i>
                            Case Management
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         PATIENT INFORMATION
    ========================================================== --}}
    <div
        id="patientInfoSection"
        class="mb-3 d-none"
    >

        <div class="payment-header px-3 py-3">
            <h5>
                <i class="fas fa-user me-2"></i>
                Patient Information
            </h5>
        </div>

        <div class="border border-top-0 rounded-bottom p-4 bg-white">

            <div id="patientInfoBody"></div>

        </div>

    </div>


    {{-- =========================================================
         STEP 2 — PAYMENT PLAN
    ========================================================== --}}
    <div
        id="planCard"
        class="card mb-4 d-none"
    >

        <div class="payment-header px-3 py-3">

            <h5>
                <i class="fas fa-file-invoice-dollar me-2"></i>
                Payment Plan
            </h5>

        </div>

        <div class="card-body p-4">

            {{-- Summary --}}
            <div class="row g-3 mb-4">

                <div class="col-md-4">

                    <div class="plan-metric plan-metric-total">

                        <div class="plan-metric-label">
                            Total Amount
                        </div>

                        <div class="plan-metric-value">
                            BDT <span id="sumTotal">0.00</span>
                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="plan-metric plan-metric-paid">

                        <div class="plan-metric-label">
                            Paid Amount
                        </div>

                        <div class="plan-metric-value">
                            BDT <span id="sumPaid">0.00</span>
                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="plan-metric plan-metric-due">

                        <div class="plan-metric-label">
                            Due Amount
                        </div>

                        <div class="plan-metric-value">
                            BDT <span id="sumRemaining">0.00</span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Plan Form --}}
            <div class="row g-4 align-items-end">

                {{-- Total Amount --}}
                <div class="col-lg-5">

                    <label
                        for="totalAmount"
                        class="form-label"
                    >
                        Total Amount
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            BDT
                        </span>

                        <input
                            id="totalAmount"
                            type="number"
                            min="0"
                            step="0.01"
                            class="form-control"
                            placeholder="0.00"
                        >

                        @if(auth()->check() && auth()->user()->role === 'admin')

                            <button
                                id="btnEditTotal"
                                type="button"
                                class="btn btn-outline-secondary d-none"
                                title="Edit total amount"
                            >
                                <i class="fas fa-pen"></i>
                            </button>

                            <button
                                id="btnSaveTotal"
                                type="button"
                                class="btn btn-outline-success d-none"
                                title="Save updated total"
                            >
                                <i class="fas fa-save"></i>
                            </button>

                        @endif

                    </div>

                    <small class="text-muted">
                        Total amount can be edited later by an administrator.
                    </small>

                </div>


                {{-- Plan Type --}}
                <div class="col-lg-4">

                    <label class="form-label">
                        Plan Type
                    </label>

                    <div class="d-flex gap-4 mt-2">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="planType"
                                id="ptFull"
                                value="full"
                                checked
                            >

                            <label
                                class="form-check-label"
                                for="ptFull"
                            >
                                Full Payment
                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="planType"
                                id="ptInstallment"
                                value="installment"
                            >

                            <label
                                class="form-check-label"
                                for="ptInstallment"
                            >
                                Installment
                            </label>

                        </div>

                    </div>

                </div>


                {{-- Save Plan --}}
                <div class="col-lg-3 plan-actions">

                    <button
                        id="btnSavePlan"
                        type="button"
                        class="btn btn-primary px-4"
                    >
                        <i class="fas fa-save me-2"></i>
                        Save Plan
                    </button>

                </div>

            </div>


            {{-- Plan success message --}}
            <div
                id="planStatus"
                class="alert alert-success mt-4 mb-0 d-none"
                role="alert"
            >
                <i class="fas fa-check-circle me-2"></i>
                <span id="planStatusText">
                    Payment plan saved successfully.
                </span>
            </div>

        </div>

    </div>

        {{-- =========================================================
         STEP 3 — DELIVERY SECTION
    ========================================================== --}}
    <div
        id="deliverySection"
        class="card mb-4 d-none"
    >

        <div class="payment-header px-3 py-3">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <h5>
                    <i class="fas fa-truck me-2"></i>
                    Delivery
                </h5>

                <div class="d-flex gap-2">

                    <span
                        id="caseClosedBadge"
                        class="badge bg-dark d-none align-self-center"
                    >
                        <i class="fas fa-lock me-1"></i>
                        Case Closed
                    </span>

                    <button
                        id="btnNewDelivery"
                        type="button"
                        class="btn btn-light btn-sm"
                    >
                        <i class="fas fa-plus me-1"></i>
                        New Delivery
                    </button>

                    <button
                        id="btnDone"
                        type="button"
                        class="btn btn-success btn-sm d-none"
                    >
                        <i class="fas fa-check me-1"></i>
                        Done
                    </button>

                </div>

            </div>

        </div>


        <div class="card-body p-4">

            {{-- Cases summary --}}
            <div class="row g-3 mb-4">

                <div class="col-md-3">

                    <div class="plan-metric plan-metric-total">

                        <div class="plan-metric-label">
                            Total Upper Cases
                        </div>

                        <div class="plan-metric-value">
                            <span id="casesUpperTotal">0</span>
                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="plan-metric plan-metric-total">

                        <div class="plan-metric-label">
                            Total Lower Cases
                        </div>

                        <div class="plan-metric-value">
                            <span id="casesLowerTotal">0</span>
                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="plan-metric plan-metric-total">

                        <div class="plan-metric-label">
                            Remaining Upper
                        </div>

                        <div class="plan-metric-value">
                            <span id="casesUpperRemaining">0</span>
                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="plan-metric plan-metric-total">

                        <div class="plan-metric-label">
                            Remaining Lower
                        </div>

                        <div class="plan-metric-value">
                            <span id="casesLowerRemaining">0</span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Existing + New deliveries --}}
            <div id="deliveriesContainer"></div>

        </div>

    </div>


    {{-- =========================================================
         STEP 5 — PAYMENT HISTORY
    ========================================================== --}}
    <div
        id="historySection"
        class="card mb-4 d-none"
    >

        <div class="payment-header px-3 py-3">

            <h5>
                <i class="fas fa-history me-2"></i>
                Payment History
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle mb-0"
                    id="historyTable"
                >

                    <thead class="table-light">

                        <tr>

                            <th class="px-4">
                                Date
                            </th>

                            <th>
                                Payment Method
                            </th>

                            <th>
                                Details
                            </th>

                            <th class="text-end px-4">
                                Amount
                            </th>

                        </tr>

                    </thead>

                    <tbody>
                    </tbody>

                </table>

            </div>

            <div
                id="emptyHistory"
                class="empty-history d-none"
            >
                <i class="fas fa-receipt fa-2x mb-2 opacity-50"></i>

                <div>
                    No payments recorded yet.
                </div>
            </div>

        </div>

    </div>


</div>

{{-- =============================================================
     ADD BANK MODAL
============================================================= --}}
<div
    class="modal fade"
    id="addBankModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="fas fa-university me-2"></i>
                    Add Bank
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body">

                <label class="form-label">
                    Bank Name
                </label>

                <input
                    type="text"
                    id="newBankName"
                    class="form-control"
                    placeholder="Enter bank name"
                >

                <div
                    id="addBankError"
                    class="text-danger small mt-2 d-none"
                ></div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="btnSaveBank"
                >
                    <i class="fas fa-save me-1"></i>
                    Save Bank
                </button>

            </div>

        </div>

    </div>

</div>



{{-- =============================================================
     ADD BRANCH MODAL
============================================================= --}}
<div
    class="modal fade"
    id="addBranchModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="fas fa-code-branch me-2"></i>
                    Add Branch
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body">

                <div class="mb-3">

                    <label class="form-label">
                        Bank
                    </label>

                    <input
                        type="text"
                        id="selectedBankName"
                        class="form-control"
                        readonly
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Branch Name
                    </label>

                    <input
                        type="text"
                        id="newBranchName"
                        class="form-control"
                        placeholder="Enter branch name"
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Account Name
                    </label>

                    <input
                        type="text"
                        id="newBranchAccountName"
                        class="form-control"
                        placeholder="Enter account name"
                    >

                </div>


                <div>

                    <label class="form-label">
                        Account Number
                    </label>

                    <input
                        type="text"
                        id="newBranchAccountNumber"
                        class="form-control"
                        placeholder="Enter account number"
                    >

                </div>


                <div
                    id="addBranchError"
                    class="text-danger small mt-2 d-none"
                ></div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="btnSaveBranch"
                >
                    <i class="fas fa-save me-1"></i>
                    Save Branch
                </button>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
     JAVASCRIPT STARTS HERE
============================================================= --}}

@push('scripts')
<script>
(function () {

    'use strict';

    /* =========================================================
       ROUTES
    ========================================================= */

    const routeFind =
        "{{ route('admin.payments.plan.find-patient', ['predict3dId' => 'PREDICT_ID']) }}";

    const routeGet =
        "{{ route('admin.payments.plan.get', ['predict3dId' => 'PREDICT_ID']) }}";

    const routeSave =
        "{{ route('admin.payments.plan.save', ['predict3dId' => 'PREDICT_ID']) }}";

    const routeAddDelivery =
        "{{ route('admin.payments.plan.add-delivery', ['predict3dId' => 'PREDICT_ID']) }}";

    const routeDone =
        "{{ route('admin.payments.plan.done', ['predict3dId' => 'PREDICT_ID']) }}";

    const routeUpdateTotal =
        "{{ route('admin.payments.plan.update-total', ['predict3dId' => 'PREDICT_ID']) }}";


    /* =========================================================
       DOM ELEMENTS
    ========================================================= */

    const $predict =
        document.getElementById('predictId');

    const $btnFetch =
        document.getElementById('btnFetch');

    const $patientInfoSection =
        document.getElementById('patientInfoSection');

    const $patientInfoBody =
        document.getElementById('patientInfoBody');

    const $planCard =
        document.getElementById('planCard');

    const $deliverySection =
        document.getElementById('deliverySection');

    const $historySection =
        document.getElementById('historySection');

    const $history =
        document.querySelector('#historyTable tbody');

    const $emptyHistory =
        document.getElementById('emptyHistory');

    const $deliveriesContainer =
        document.getElementById('deliveriesContainer');

    const $btnNewDelivery =
        document.getElementById('btnNewDelivery');

    const $btnDone =
        document.getElementById('btnDone');

    const $caseClosedBadge =
        document.getElementById('caseClosedBadge');

    const $totalAmount =
        document.getElementById('totalAmount');

    const $btnSavePlan =
        document.getElementById('btnSavePlan');

    const $btnEditTotal =
        document.getElementById('btnEditTotal');

    const $btnSaveTotal =
        document.getElementById('btnSaveTotal');

    const $ptFull =
        document.getElementById('ptFull');

    const $ptInstallment =
        document.getElementById('ptInstallment');

    const $sumTotal =
        document.getElementById('sumTotal');

    const $sumPaid =
        document.getElementById('sumPaid');

    const $sumRemaining =
        document.getElementById('sumRemaining');

    const $casesUpperTotal =
        document.getElementById('casesUpperTotal');

    const $casesLowerTotal =
        document.getElementById('casesLowerTotal');

    const $casesUpperRemaining =
        document.getElementById('casesUpperRemaining');

    const $casesLowerRemaining =
        document.getElementById('casesLowerRemaining');

    const $planStatus =
        document.getElementById('planStatus');

    const $planStatusText =
        document.getElementById('planStatusText');

    const csrf =
        document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';


    /* =========================================================
       STATE
    ========================================================= */

    let currentPredict = '';

    let hasSavedPlan = false;

    let caseClosed = false;

    let casesState = {
        total_upper: 0,
        total_lower: 0,
        delivered_upper: 0,
        delivered_lower: 0,
        remaining_upper: 0,
        remaining_lower: 0
    };


    /* =========================================================
       HELPERS
    ========================================================= */

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    function fmtDate(value) {

        if (!value) {
            return '';
        }

        const d = new Date(value);

        if (Number.isNaN(d.getTime())) {
            return String(value);
        }

        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');

        return `${y}-${m}-${day}`;
    }


    function fmtMethod(value) {

        if (!value) {
            return 'Unknown';
        }

        return String(value)
            .replace(/_/g, ' ')
            .replace(/\b\w/g, c => c.toUpperCase());
    }


    function computeAge(iso) {

        if (!iso) {
            return '';
        }

        const d = new Date(iso);

        if (Number.isNaN(d.getTime())) {
            return '';
        }

        const years = Math.floor(
            (Date.now() - d.getTime()) /
            (365.25 * 24 * 60 * 60 * 1000)
        );

        return years >= 0 ? `${years} years` : '';
    }


    function showPlanStatus(message, type = 'success') {

        if (!$planStatus) {
            return;
        }

        $planStatus.className =
            `alert alert-${type} mt-4 mb-0`;

        $planStatusText.textContent = message;

        $planStatus.classList.remove('d-none');
    }


    function hidePlanStatus() {

        if ($planStatus) {
            $planStatus.classList.add('d-none');
        }
    }


    function recalcSummary() {

        const total =
            parseFloat($totalAmount?.value || '0') || 0;

        const paid =
            parseFloat($sumPaid?.textContent || '0') || 0;

        const due =
            Math.max(0, total - paid);

        if ($sumTotal) {
            $sumTotal.textContent =
                total.toFixed(2);
        }

        if ($sumRemaining) {
            $sumRemaining.textContent =
                due.toFixed(2);
        }
    }


    function renderCases() {

        if ($casesUpperTotal) {
            $casesUpperTotal.textContent =
                String(casesState.total_upper || 0);
        }

        if ($casesLowerTotal) {
            $casesLowerTotal.textContent =
                String(casesState.total_lower || 0);
        }

        if ($casesUpperRemaining) {
            $casesUpperRemaining.textContent =
                String(casesState.remaining_upper || 0);
        }

        if ($casesLowerRemaining) {
            $casesLowerRemaining.textContent =
                String(casesState.remaining_lower || 0);
        }
    }


    /* =========================================================
       PAYMENT HISTORY DETAILS
    ========================================================= */

    function paymentDetails(payment) {

        const method =
            String(payment.payment_method || '');

        if (method === 'cash' || method === 'card') {

            return `
                <span class="text-muted small">
                    <i class="fas fa-minus me-1"></i>
                    No additional details
                </span>
            `;
        }


        if (method === 'bank_transfer') {

            const bank =
                escapeHtml(payment.bank_name || '');

            const branch =
                escapeHtml(payment.branch_name || '');

            const accountName =
                escapeHtml(payment.account_name || '');

            const accountNumber =
                escapeHtml(payment.account_number || '');

            return `
                <div class="small">

                    ${
                        bank || branch
                            ? `
                                <div class="fw-semibold">
                                    <i class="fas fa-university text-primary me-1"></i>
                                    ${bank || 'Bank'}

                                    ${
                                        branch
                                            ? `<span class="text-muted">
                                                • ${branch}
                                               </span>`
                                            : ''
                                    }
                                </div>
                              `
                            : ''
                    }

                    ${
                        accountName
                            ? `
                                <div class="text-muted mt-1">
                                    <i class="fas fa-user me-1"></i>
                                    ${accountName}
                                </div>
                              `
                            : ''
                    }

                    ${
                        accountNumber
                            ? `
                                <div class="text-muted">
                                    <i class="fas fa-credit-card me-1"></i>
                                    ${accountNumber}
                                </div>
                              `
                            : ''
                    }

                    ${
                        !bank &&
                        !branch &&
                        !accountName &&
                        !accountNumber
                            ? `
                                <span class="text-muted">
                                    No bank details
                                </span>
                              `
                            : ''
                    }

                </div>
            `;
        }


        if (method === 'mobile_banking') {

            const provider =
                escapeHtml(payment.mobile_provider || '');

            const mobileNumber =
                escapeHtml(
                    payment.account_number ||
                    payment.mobile_number ||
                    ''
                );

            const transactionId =
                escapeHtml(payment.transaction_id || '');

            return `
                <div class="small">

                    ${
                        provider
                            ? `
                                <div class="fw-semibold">
                                    <i class="fas fa-mobile-alt text-primary me-1"></i>
                                    ${provider}
                                </div>
                              `
                            : ''
                    }

                    ${
                        mobileNumber
                            ? `
                                <div class="text-muted mt-1">
                                    <i class="fas fa-phone me-1"></i>
                                    ${mobileNumber}
                                </div>
                              `
                            : ''
                    }

                    ${
                        transactionId
                            ? `
                                <div class="text-muted">
                                    <i class="fas fa-receipt me-1"></i>
                                    TXN: ${transactionId}
                                </div>
                              `
                            : ''
                    }

                </div>
            `;
        }


        return `
            <span class="text-muted small">
                No additional details
            </span>
        `;
    }


    function renderHistory(payments) {

        if (!$history) {
            return;
        }

        if (!payments || payments.length === 0) {

            $history.innerHTML = '';

            if ($emptyHistory) {
                $emptyHistory.classList.remove('d-none');
            }

            return;
        }

        if ($emptyHistory) {
            $emptyHistory.classList.add('d-none');
        }


        $history.innerHTML =
            payments.map(payment => {

                const method =
                    String(payment.payment_method || '');

                let methodIcon =
                    'fa-money-bill-wave';

                let methodClass =
                    'bg-success';


                if (method === 'bank_transfer') {

                    methodIcon =
                        'fa-university';

                    methodClass =
                        'bg-primary';
                }


                if (method === 'mobile_banking') {

                    methodIcon =
                        'fa-mobile-alt';

                    methodClass =
                        'bg-info';
                }


                if (method === 'card') {

                    methodIcon =
                        'fa-credit-card';

                    methodClass =
                        'bg-warning text-dark';
                }


                return `
                    <tr>

                        <td class="px-4">
                            <span class="fw-semibold">
                                ${escapeHtml(
                                    fmtDate(payment.payment_date)
                                )}
                            </span>
                        </td>

                        <td>
                            <span class="badge ${methodClass} payment-badge px-3 py-2">
                                <i class="fas ${methodIcon} me-1"></i>
                                ${escapeHtml(fmtMethod(method))}
                            </span>
                        </td>

                        <td>
                            ${paymentDetails(payment)}
                        </td>

                        <td class="text-end px-4">

                            <span class="fw-bold text-primary">
                                BDT
                                ${(
                                    parseFloat(payment.amount || 0)
                                ).toFixed(2)}
                            </span>

                        </td>

                    </tr>
                `;

            }).join('');
    }


    /* =========================================================
       EXISTING DELIVERY DISPLAY
    ========================================================= */

    function renderExistingDeliveries(deliveries) {

        if (!$deliveriesContainer) {
            return;
        }

        $deliveriesContainer.innerHTML = '';


        if (!deliveries || deliveries.length === 0) {

            $deliveriesContainer.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-truck-loading fa-2x mb-2 opacity-50"></i>

                    <div>
                        No deliveries recorded yet.
                    </div>
                </div>
            `;

            return;
        }


        deliveries.forEach((delivery, index) => {

            const upper =
                parseInt(
                    delivery.upper_delivered || 0,
                    10
                );

            const lower =
                parseInt(
                    delivery.lower_delivered || 0,
                    10
                );

            const paid =
                parseFloat(
                    delivery.paid_amount || 0
                );


            $deliveriesContainer.insertAdjacentHTML(
                'afterbegin',
                `
                <div class="delivery-card mb-3">

                    <div class="delivery-header">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <div class="fw-semibold">

                                <i class="fas fa-box-open text-primary me-2"></i>

                                Delivery #${index + 1}

                            </div>

                            <div class="text-muted small">

                                ${escapeHtml(
                                    fmtDate(delivery.delivery_date)
                                )}

                            </div>

                        </div>

                    </div>


                    <div class="p-4">

                        <div class="row g-4">

                            <div class="col-md-3">

                                <div class="small text-muted">
                                    Upper delivered
                                </div>

                                <div class="fw-bold fs-5">
                                    ${upper}
                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="small text-muted">
                                    Lower delivered
                                </div>

                                <div class="fw-bold fs-5">
                                    ${lower}
                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="small text-muted">
                                    Paid
                                </div>

                                <div class="fw-bold fs-5">
                                    BDT ${paid.toFixed(2)}
                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="small text-muted">
                                    Date
                                </div>

                                <div class="fw-bold">
                                    ${escapeHtml(
                                        fmtDate(
                                            delivery.delivery_date
                                        )
                                    )}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                `
            );
        });
    }


    /* =========================================================
       APPLY CASE CLOSED STATE
    ========================================================= */

    function applyLockState(isClosed) {

        caseClosed = !!isClosed;


        if ($caseClosedBadge) {

            $caseClosedBadge.classList.toggle(
                'd-none',
                !caseClosed
            );
        }


        if ($btnNewDelivery) {

            $btnNewDelivery.classList.toggle(
                'd-none',
                caseClosed
            );
        }


        if ($btnDone) {

            $btnDone.classList.add('d-none');
        }


        if (caseClosed && $deliveriesContainer) {

            $deliveriesContainer
                .querySelectorAll(
                    '[data-delivery-draft="1"]'
                )
                .forEach(card => card.remove());
        }
    }


    /* =========================================================
       SEARCH PATIENT
    ========================================================= */

    $btnFetch.addEventListener(
        'click',
        async function () {

            const id =
                ($predict.value || '').trim();


            if (!id) {

                alert('Please enter 3D Predict ID.');

                $predict.focus();

                return;
            }


            hidePlanStatus();


            $patientInfoSection
                .classList
                .add('d-none');

            $planCard
                .classList
                .add('d-none');

            $deliverySection
                .classList
                .add('d-none');

            $historySection
                .classList
                .add('d-none');


            currentPredict = '';

            hasSavedPlan = false;

            caseClosed = false;


            try {

                /* -------------------------------------------------
                   FIND PATIENT
                ------------------------------------------------- */

                const url =
                    routeFind.replace(
                        'PREDICT_ID',
                        encodeURIComponent(id)
                    );


                const response =
                    await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });


                if (!response.ok) {

                    throw new Error(
                        'Patient not found.'
                    );
                }


                const patient =
                    await response.json();


                const age =
                    computeAge(
                        patient.DateOfBirth
                    );


                const scanningFor =
                    (
                        patient.ScanningFor === 'Others' &&
                        patient.ScanningForOthers
                    )
                        ? `${patient.ScanningFor} (${patient.ScanningForOthers})`
                        : (
                            patient.ScanningFor || ''
                        );


                /* -------------------------------------------------
                   PATIENT INFORMATION
                ------------------------------------------------- */

                $patientInfoBody.innerHTML = `

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="mb-2">
                                <span class="fw-semibold">
                                    Patient Name:
                                </span>

                                <span class="ms-2">
                                    ${escapeHtml(
                                        patient.FullName || ''
                                    )}
                                </span>
                            </div>

                            <div class="mb-2">
                                <span class="fw-semibold">
                                    Doctor Name:
                                </span>

                                <span class="ms-2">
                                    ${escapeHtml(
                                        patient.DoctorName || ''
                                    )}
                                </span>
                            </div>

                            <div>
                                <span class="fw-semibold">
                                    Scanning For:
                                </span>

                                <span class="ms-2">
                                    ${escapeHtml(
                                        scanningFor
                                    )}
                                </span>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="mb-2">
                                <span class="fw-semibold">
                                    Gender:
                                </span>

                                <span class="ms-2">
                                    ${escapeHtml(
                                        patient.Gender || ''
                                    )}
                                </span>
                            </div>

                            <div class="mb-2">
                                <span class="fw-semibold">
                                    Age:
                                </span>

                                <span class="ms-2">
                                    ${escapeHtml(age)}
                                </span>
                            </div>

                            <div>
                                <span class="fw-semibold">
                                    Phone Number:
                                </span>

                                <span class="ms-2">
                                    ${escapeHtml(
                                        patient.PhoneNumber || ''
                                    )}
                                </span>
                            </div>

                        </div>

                    </div>

                    <hr class="my-3">

                    <div class="small text-muted">

                        3D Predict ID:

                        <span class="fw-semibold text-dark">
                            ${escapeHtml(
                                patient.Predict3DId || id
                            )}
                        </span>

                    </div>

                `;


                $patientInfoSection
                    .classList
                    .remove('d-none');


                currentPredict = id;


                /* -------------------------------------------------
                   DEFAULT PLAN STATE
                ------------------------------------------------- */

                $totalAmount.value = '';

                $totalAmount.disabled = false;


                $sumTotal.textContent = '0.00';
                $sumPaid.textContent = '0.00';
                $sumRemaining.textContent = '0.00';


                $ptFull.checked = true;


                $deliveriesContainer.innerHTML = '';


                casesState = {
                    total_upper: 0,
                    total_lower: 0,
                    delivered_upper: 0,
                    delivered_lower: 0,
                    remaining_upper: 0,
                    remaining_lower: 0
                };


                renderCases();


                /* -------------------------------------------------
                   GET EXISTING PLAN
                ------------------------------------------------- */

                const getUrl =
                    routeGet.replace(
                        'PREDICT_ID',
                        encodeURIComponent(id)
                    );


                const planResponse =
                    await fetch(getUrl, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });


                if (!planResponse.ok) {

                    throw new Error(
                        'Unable to load payment information.'
                    );
                }


                const data =
                    await planResponse.json();


                const plan =
                    data.plan || null;

                const payments =
                    data.payments || [];

                const deliveries =
                    data.deliveries || [];


                casesState =
                    data.cases || casesState;


                renderCases();


                /* -------------------------------------------------
                   NO EXISTING PLAN
                ------------------------------------------------- */

                if (!plan) {

                    hasSavedPlan = false;


                    $planCard
                        .classList
                        .remove('d-none');


                    $deliverySection
                        .classList
                        .add('d-none');


                    $historySection
                        .classList
                        .add('d-none');


                    $btnSavePlan.disabled = false;


                    return;
                }


                /* -------------------------------------------------
                   EXISTING PLAN
                ------------------------------------------------- */

                hasSavedPlan = true;


                $planCard
                    .classList
                    .remove('d-none');


                $totalAmount.value =
                    parseFloat(
                        plan.total_amount || 0
                    ).toFixed(2);


                $totalAmount.disabled = true;


                if (plan.is_installment) {

                    $ptInstallment.checked = true;

                } else {

                    $ptFull.checked = true;
                }


                const paid =
                    parseFloat(
                        data.paid || 0
                    );


                const remaining =
                    Math.max(
                        0,
                        parseFloat(
                            plan.total_amount || 0
                        ) - paid
                    );


                $sumTotal.textContent =
                    parseFloat(
                        plan.total_amount || 0
                    ).toFixed(2);


                $sumPaid.textContent =
                    paid.toFixed(2);


                $sumRemaining.textContent =
                    remaining.toFixed(2);


                /* -------------------------------------------------
                   SHOW DELIVERY SECTION
                ------------------------------------------------- */

                $deliverySection
                    .classList
                    .remove('d-none');


                renderExistingDeliveries(
                    deliveries
                );


                /* -------------------------------------------------
                   PAYMENT HISTORY
                ------------------------------------------------- */

                $historySection
                    .classList
                    .remove('d-none');


                renderHistory(payments);


                applyLockState(
                    !!plan.is_closed
                );


            } catch (error) {

                console.error(error);

                alert(
                    error.message ||
                    'Failed to load patient information.'
                );
            }

        }
    );


    /* =========================================================
       SAVE PAYMENT PLAN
       ONLY TOTAL AMOUNT + PLAN TYPE
    ========================================================= */

    $btnSavePlan.addEventListener(
        'click',
        async function () {

            if (!currentPredict) {

                alert(
                    'Please search for a patient first.'
                );

                return;
            }


            const total =
                parseFloat(
                    $totalAmount.value || '0'
                ) || 0;


            const planType =
                document.querySelector(
                    'input[name="planType"]:checked'
                )?.value || 'full';


            if (total <= 0) {

                alert(
                    'Please enter a valid Total Amount.'
                );

                $totalAmount.focus();

                return;
            }


            $btnSavePlan.disabled = true;


            try {

                const url =
                    routeSave.replace(
                        'PREDICT_ID',
                        encodeURIComponent(
                            currentPredict
                        )
                    );


                const response =
                    await fetch(url, {

                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                csrf
                        },

                        body: JSON.stringify({

                            total_amount:
                                total,

                            is_installment:
                                planType === 'installment'

                        })

                    });


                const contentType =
                    response.headers
                        .get('content-type') || '';


                if (!response.ok) {

                    let message =
                        'Failed to save payment plan.';


                    if (
                        contentType
                            .includes(
                                'application/json'
                            )
                    ) {

                        const errorData =
                            await response.json();

                        message =
                            errorData.message ||
                            message;

                    } else {

                        message =
                            await response.text() ||
                            message;
                    }


                    throw new Error(message);
                }


                const data =
                    await response.json();


                const savedPlan =
                    data.plan || null;


                const savedTotal =
                    savedPlan
                        ? parseFloat(
                            savedPlan.total_amount || total
                        )
                        : total;


                $totalAmount.value =
                    savedTotal.toFixed(2);


                $sumTotal.textContent =
                    savedTotal.toFixed(2);


                $totalAmount.disabled = true;


                hasSavedPlan = true;


                if ($btnEditTotal) {

                    $btnEditTotal
                        .classList
                        .remove('d-none');
                }


                if ($btnSaveTotal) {

                    $btnSaveTotal
                        .classList
                        .add('d-none');
                }


                /* -------------------------------------------------
                   THIS IS IMPORTANT:
                   Delivery becomes available ONLY AFTER
                   Save Plan.
                ------------------------------------------------- */

                $deliverySection
                    .classList
                    .remove('d-none');


                $historySection
                    .classList
                    .remove('d-none');


                showPlanStatus(
                    'Payment plan saved successfully.'
                );


                /* Refresh plan data */
                await refreshPlanData(false);


            } catch (error) {

                console.error(error);

                alert(
                    error.message ||
                    'Failed to save payment plan.'
                );

            } finally {

                $btnSavePlan.disabled = false;
            }

        }
    );


    /* =========================================================
       EDIT TOTAL
    ========================================================= */

    if ($btnEditTotal && $btnSaveTotal) {

        $btnEditTotal.addEventListener(
            'click',
            function () {

                if (caseClosed) {
                    return;
                }

                $totalAmount.disabled = false;

                $totalAmount.focus();

                $btnEditTotal
                    .classList
                    .add('d-none');

                $btnSaveTotal
                    .classList
                    .remove('d-none');
            }
        );


        $btnSaveTotal.addEventListener(
            'click',
            async function () {

                if (!currentPredict) {

                    alert(
                        'Please search for a patient first.'
                    );

                    return;
                }


                const amount =
                    parseFloat(
                        $totalAmount.value || '0'
                    ) || 0;


                if (amount <= 0) {

                    alert(
                        'Please enter a valid total amount.'
                    );

                    return;
                }


                $btnSaveTotal.disabled = true;


                try {

                    const url =
                        routeUpdateTotal.replace(
                            'PREDICT_ID',
                            encodeURIComponent(
                                currentPredict
                            )
                        );


                    const response =
                        await fetch(url, {

                            method: 'PUT',

                            headers: {
                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrf
                            },

                            body: JSON.stringify({
                                total_amount: amount
                            })

                        });


                    const contentType =
                        response.headers
                            .get('content-type') || '';


                    if (!response.ok) {

                        let message =
                            'Failed to update total amount.';


                        if (
                            contentType
                                .includes(
                                    'application/json'
                                )
                        ) {

                            const errorData =
                                await response.json();

                            message =
                                errorData.message ||
                                message;

                        } else {

                            message =
                                await response.text() ||
                                message;
                        }


                        throw new Error(message);
                    }


                    const data =
                        await response.json();


                    const updatedTotal =
                        parseFloat(
                            data.plan?.total_amount ||
                            amount
                        );


                    $totalAmount.value =
                        updatedTotal.toFixed(2);


                    $totalAmount.disabled = true;


                    $btnEditTotal
                        .classList
                        .remove('d-none');


                    $btnSaveTotal
                        .classList
                        .add('d-none');


                    await refreshPlanData(false);


                    showPlanStatus(
                        'Total amount updated successfully.'
                    );


                } catch (error) {

                    console.error(error);

                    alert(
                        error.message ||
                        'Failed to update total amount.'
                    );

                } finally {

                    $btnSaveTotal.disabled = false;
                }

            }
        );
    }


    /* =========================================================
       TOTAL AMOUNT LIVE UPDATE
    ========================================================= */

    $totalAmount.addEventListener(
        'input',
        recalcSummary
    );


    /* =========================================================
       REFRESH PLAN DATA
    ========================================================= */

    async function refreshPlanData(createDraft = false) {

        if (!currentPredict) {
            return;
        }


        const url =
            routeGet.replace(
                'PREDICT_ID',
                encodeURIComponent(
                    currentPredict
                )
            );


        const response =
            await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });


        if (!response.ok) {
            return;
        }


        const data =
            await response.json();


        const plan =
            data.plan || null;


        if (!plan) {
            return;
        }


        const payments =
            data.payments || [];


        const deliveries =
            data.deliveries || [];


        casesState =
            data.cases || casesState;


        hasSavedPlan = true;


        $totalAmount.value =
            parseFloat(
                plan.total_amount || 0
            ).toFixed(2);


        $totalAmount.disabled = true;


        if (plan.is_installment) {
            $ptInstallment.checked = true;
        } else {
            $ptFull.checked = true;
        }


        const paid =
            payments.reduce(
                (sum, payment) =>
                    sum +
                    parseFloat(
                        payment.amount || 0
                    ),
                0
            );


        const remaining =
            Math.max(
                0,
                parseFloat(
                    plan.total_amount || 0
                ) - paid
            );


        $sumTotal.textContent =
            parseFloat(
                plan.total_amount || 0
            ).toFixed(2);


        $sumPaid.textContent =
            paid.toFixed(2);


        $sumRemaining.textContent =
            remaining.toFixed(2);


        renderCases();

        renderExistingDeliveries(
            deliveries
        );

        renderHistory(
            payments
        );


        $deliverySection
            .classList
            .remove('d-none');


        $historySection
            .classList
            .remove('d-none');


        applyLockState(
            !!plan.is_closed
        );


        /*
         * IMPORTANT:
         * We deliberately DO NOT create a delivery draft here.
         *
         * The user must click:
         *
         * + New Delivery
         *
         * themselves.
         */
    }


    /* =========================================================
       URL AUTO SEARCH
    ========================================================= */

    const urlParams =
        new URLSearchParams(
            window.location.search
        );


    const queryPredict =
        (
            urlParams.get('predict3d_id') ||
            ''
        ).trim();


    if (queryPredict) {

        $predict.value =
            queryPredict;


        window.addEventListener(
            'load',
            function () {

                $btnFetch.click();

            }
        );
    }


})();
</script>

<script>
(function () {

    'use strict';

    const routeAddDelivery =
        "{{ route('admin.payments.plan.add-delivery', ['predict3dId' => 'PREDICT_ID']) }}";

    const csrf =
        document.querySelector(
            'meta[name="csrf-token"]'
        )?.getAttribute('content') || '';


    const $predict =
        document.getElementById('predictId');

    const $deliveriesContainer =
        document.getElementById('deliveriesContainer');

    const $btnNewDelivery =
        document.getElementById('btnNewDelivery');

    const $btnDone =
        document.getElementById('btnDone');

    const $totalAmount =
        document.getElementById('totalAmount');

    const $deliverySection =
        document.getElementById('deliverySection');

    const $historySection =
        document.getElementById('historySection');

    const $sumPaid =
        document.getElementById('sumPaid');

    const $sumTotal =
        document.getElementById('sumTotal');

    const $sumRemaining =
        document.getElementById('sumRemaining');

    const $casesUpperTotal =
        document.getElementById('casesUpperTotal');

    const $casesLowerTotal =
        document.getElementById('casesLowerTotal');

    const $casesUpperRemaining =
        document.getElementById('casesUpperRemaining');

    const $casesLowerRemaining =
        document.getElementById('casesLowerRemaining');

    const $caseClosedBadge =
        document.getElementById('caseClosedBadge');


    let caseClosed = false;


    /* =========================================================
       HELPERS
    ========================================================= */

    function today() {

        const d = new Date();

        const y =
            d.getFullYear();

        const m =
            String(
                d.getMonth() + 1
            ).padStart(2, '0');

        const day =
            String(
                d.getDate()
            ).padStart(2, '0');

        return `${y}-${m}-${day}`;
    }


    function escapeHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    function fmtDate(value) {

        if (!value) {
            return '';
        }

        const d =
            new Date(value);

        if (
            Number.isNaN(
                d.getTime()
            )
        ) {
            return String(value);
        }

        const y =
            d.getFullYear();

        const m =
            String(
                d.getMonth() + 1
            ).padStart(2, '0');

        const day =
            String(
                d.getDate()
            ).padStart(2, '0');

        return `${y}-${m}-${day}`;
    }


    function fmtMethod(value) {

        if (!value) {
            return 'Unknown';
        }

        return String(value)
            .replace(/_/g, ' ')
            .replace(
                /\b\w/g,
                c => c.toUpperCase()
            );
    }


    /* =========================================================
       DELIVERY TEMPLATE
    ========================================================= */

    function deliveryTemplate(number) {

        return `
        <div
            class="delivery-draft mb-3"
            data-delivery-draft="1"
        >

            <div class="delivery-header">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="fw-semibold text-primary">

                        <i class="fas fa-plus-circle me-2"></i>

                        New Delivery

                    </div>

                    <div class="text-muted small">
                        Delivery #${number}
                    </div>

                </div>

            </div>


            <div class="p-4">

                {{-- Basic delivery information --}}
                <div class="row g-3">

                    {{-- Upper --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Upper cases
                        </label>

                        <input
                            type="number"
                            min="0"
                            step="1"
                            class="form-control js-upper"
                            value="0"
                        >

                    </div>


                    {{-- Lower --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Lower cases
                        </label>

                        <input
                            type="number"
                            min="0"
                            step="1"
                            class="form-control js-lower"
                            value="0"
                        >

                    </div>


                    {{-- Paid amount --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Paid amount
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                BDT
                            </span>

                            <input
                                type="number"
                                min="0"
                                step="0.01"
                                class="form-control js-paid"
                                value="0.00"
                            >

                        </div>

                    </div>


                    {{-- Date --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Delivery date
                        </label>

                        <input
                            type="date"
                            class="form-control js-date"
                            value="${today()}"
                        >

                    </div>


                    {{-- Payment method --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Payment method
                        </label>

                        <select
                            class="form-select js-payment-method"
                        >

                            <option value="cash">
                                Cash
                            </option>

                            <option value="card">
                                Card
                            </option>

                            <option value="bank_transfer">
                                Bank Transfer
                            </option>

                            <option value="mobile_banking">
                                Mobile Banking
                            </option>

                        </select>

                    </div>

                </div>


                {{-- =================================================
                    BANK TRANSFER
                ================================================== --}}
                <div
                    class="row g-3 mt-2 d-none js-bank-details"
                >

                    {{-- Bank Name --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Bank Name
                        </label>

                        <div class="input-group">

                            <select
                                class="form-select js-bank"
                            >
                                <option value="">
                                    Select Bank
                                </option>
                            </select>

                            <button
                                type="button"
                                class="btn btn-outline-primary js-add-bank"
                                title="Add new bank"
                            >
                                <i class="fas fa-plus"></i>
                            </button>

                        </div>

                    </div>


                    {{-- Branch Name --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Branch Name
                        </label>

                        <div class="input-group">

                            <select
                                class="form-select js-branch"
                                disabled
                            >
                                <option value="">
                                    Select Branch
                                </option>
                            </select>

                            <button
                                type="button"
                                class="btn btn-outline-primary js-add-branch"
                                title="Add new branch"
                            >
                                <i class="fas fa-plus"></i>
                            </button>

                        </div>

                    </div>


                    {{-- Account Name --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Account Name
                        </label>

                        <input
                            type="text"
                            class="form-control js-account-name"
                            placeholder="Account name"
                            disabled
                        >

                    </div>


                    {{-- Account Number --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Account Number
                        </label>

                        <input
                            type="text"
                            class="form-control js-account-number"
                            placeholder="Account number"
                            disabled
                        >

                    </div>

                </div>

                {{-- =================================================
                     MOBILE BANKING
                ================================================== --}}
                <div
                    class="row g-3 mt-2 d-none js-mobile-details"
                >

                    <div class="col-md-4">

                        <label class="form-label">
                            Mobile Banking
                        </label>

                        <select
                            class="form-select js-mobile-provider"
                        >

                            <option value="">
                                Select Provider
                            </option>

                            <option value="bkash">
                                bKash
                            </option>

                            <option value="nagad">
                                Nagad
                            </option>

                            <option value="rocket">
                                Rocket
                            </option>

                            <option value="upay">
                                Upay
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Mobile Number
                        </label>

                        <input
                            type="text"
                            class="form-control js-mobile-number"
                            placeholder="01XXXXXXXXX"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Transaction ID
                        </label>

                        <input
                            type="text"
                            class="form-control js-transaction-id"
                            placeholder="Enter transaction ID"
                        >

                    </div>

                </div>


                {{-- Actions --}}
                <div
                    class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top"
                >

                    <button
                        type="button"
                        class="btn btn-outline-secondary px-4 js-cancel"
                    >
                        <i class="fas fa-times me-1"></i>
                        Cancel
                    </button>

                    <button
                        type="button"
                        class="btn btn-success px-4 js-save"
                    >
                        <i class="fas fa-save me-1"></i>
                        Save
                    </button>

                </div>

            </div>

        </div>
        `;
    }


    /* =========================================================
       NEW DELIVERY BUTTON
    ========================================================= */

    $btnNewDelivery.addEventListener(
        'click',
        async function () {

            if (!$predict.value.trim()) {

                alert(
                    'Please search for a patient first.'
                );

                return;
            }


            if (
                $totalAmount.disabled === false ||
                !parseFloat(
                    $totalAmount.value || '0'
                )
            ) {

                alert(
                    'Please save the payment plan before creating a delivery.'
                );

                return;
            }


            if (caseClosed) {

                alert(
                    'This case is closed. No new delivery can be added.'
                );

                return;
            }


            /*
             * Prevent multiple unsaved delivery forms.
             */

            const existingDraft =
                $deliveriesContainer
                    .querySelector(
                        '[data-delivery-draft="1"]'
                    );


            if (existingDraft) {

                existingDraft.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                return;
            }


            const existingDeliveries =
                $deliveriesContainer
                    .querySelectorAll(
                        '.delivery-card'
                    ).length;


            const number =
                existingDeliveries + 1;


            $deliveriesContainer.insertAdjacentHTML(
                'afterbegin',
                deliveryTemplate(number)
            );


            const draft =
                $deliveriesContainer
                    .querySelector(
                        '[data-delivery-draft="1"]'
                    );


            if (draft) {

                await initializeDeliveryPayment(
                    draft
                );


                draft.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

        }
    );


    /* =========================================================
       INITIALIZE DELIVERY PAYMENT
    ========================================================= */

    async function initializeDeliveryPayment(card) {

        const method =
            card.querySelector(
                '.js-payment-method'
            );

        const bankDetails =
            card.querySelector(
                '.js-bank-details'
            );

        const mobileDetails =
            card.querySelector(
                '.js-mobile-details'
            );

        const bankSelect =
            card.querySelector(
                '.js-bank'
            );

        const branchSelect =
            card.querySelector(
                '.js-branch'
            );

        const accountName =
            card.querySelector(
                '.js-account-name'
            );

        const accountNumber =
            card.querySelector(
                '.js-account-number'
            );


        /* ---------------------------------------------------------
           LOAD BANKS
        --------------------------------------------------------- */

        try {

            const response =
                await fetch(
                    "{{ route('admin.banks.index') }}",
                    {
                        headers: {
                            'Accept':
                                'application/json'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Failed to load banks.'
                );
            }


            const banks =
                await response.json();


            bankSelect.innerHTML =
                '<option value="">Select Bank</option>';


            banks.forEach(
                bank => {

                    const option =
                        document.createElement(
                            'option'
                        );

                    option.value =
                        bank.id;

                    option.textContent =
                        bank.bank_name;

                    bankSelect.appendChild(
                        option
                    );
                }
            );


        } catch (error) {

            console.error(
                'Bank loading error:',
                error
            );
        }


        /* ---------------------------------------------------------
           PAYMENT METHOD CHANGE
        --------------------------------------------------------- */

        method.addEventListener(
            'change',
            function () {

                const selected =
                    this.value;


                bankDetails
                    .classList
                    .add('d-none');


                mobileDetails
                    .classList
                    .add('d-none');


                if (
                    selected ===
                    'bank_transfer'
                ) {

                    bankDetails
                        .classList
                        .remove('d-none');
                }


                if (
                    selected ===
                    'mobile_banking'
                ) {

                    mobileDetails
                        .classList
                        .remove('d-none');
                }

            }
        );


        /* ---------------------------------------------------------
           BANK CHANGE
        --------------------------------------------------------- */

        bankSelect.addEventListener(
            'change',
            async function () {

                branchSelect.innerHTML =
                    '<option value="">Select Branch</option>';

                branchSelect.disabled = true;


                accountName.value = '';
                accountNumber.value = '';

                accountName.disabled = true;
                accountNumber.disabled = true;


                if (!this.value) {
                    return;
                }


                try {

                    const response =
                        await fetch(
                            '/admin/banks/' +
                            encodeURIComponent(
                                this.value
                            ) +
                            '/branches',
                            {
                                headers: {
                                    'Accept':
                                        'application/json'
                                }
                            }
                        );


                    if (!response.ok) {

                        throw new Error(
                            'Failed to load branches.'
                        );
                    }


                    const branches =
                        await response.json();


                    branches.forEach(
                        branch => {

                            const option =
                                document.createElement(
                                    'option'
                                );


                            option.value =
                                branch.id;


                            option.textContent =
                                branch.branch_name;


                            option.dataset.accountName =
                                branch.account_name || '';


                            option.dataset.accountNumber =
                                branch.account_number || '';


                            branchSelect.appendChild(
                                option
                            );
                        }
                    );


                    branchSelect.disabled = false;


                } catch (error) {

                    console.error(
                        error
                    );

                    alert(
                        'Failed to load branches.'
                    );
                }

            }
        );


        /* ---------------------------------------------------------
           BRANCH CHANGE
        --------------------------------------------------------- */

        branchSelect.addEventListener(
            'change',
            function () {

                const option =
                    this.options[
                        this.selectedIndex
                    ];


                if (!this.value) {

                    accountName.value = '';
                    accountNumber.value = '';

                    accountName.disabled = true;
                    accountNumber.disabled = true;

                    return;
                }


                accountName.value =
                    option.dataset.accountName || '';


                accountNumber.value =
                    option.dataset.accountNumber || '';


                accountName.disabled = false;
                accountNumber.disabled = false;

            }
        );

    }


    /* =========================================================
       DELIVERY CLICK HANDLER
    ========================================================= */

    $deliveriesContainer.addEventListener(
        'click',
        async function (event) {

            /* -----------------------------------------------------
               CANCEL
            ----------------------------------------------------- */

            const cancelButton =
                event.target.closest(
                    '.js-cancel'
                );


            if (cancelButton) {

                const card =
                    cancelButton.closest(
                        '[data-delivery-draft="1"]'
                    );


                if (card) {
                    card.remove();
                }


                return;
            }


            /* -----------------------------------------------------
               SAVE
            ----------------------------------------------------- */

            const saveButton =
                event.target.closest(
                    '.js-save'
                );


            if (!saveButton) {
                return;
            }


            const card =
                saveButton.closest(
                    '[data-delivery-draft="1"]'
                );


            if (!card) {
                return;
            }


            if (caseClosed) {

                alert(
                    'This case is closed. No new delivery can be added.'
                );

                return;
            }


            const upper =
                parseInt(
                    card.querySelector(
                        '.js-upper'
                    ).value || '0',
                    10
                ) || 0;


            const lower =
                parseInt(
                    card.querySelector(
                        '.js-lower'
                    ).value || '0',
                    10
                ) || 0;


            const paidAmount =
                parseFloat(
                    card.querySelector(
                        '.js-paid'
                    ).value || '0'
                ) || 0;


            const deliveryDate =
                card.querySelector(
                    '.js-date'
                ).value;


            const paymentMethod =
                card.querySelector(
                    '.js-payment-method'
                ).value;


            const bankSelect =
                card.querySelector(
                    '.js-bank'
                );


            const branchSelect =
                card.querySelector(
                    '.js-branch'
                );


            const accountName =
                card.querySelector(
                    '.js-account-name'
                );


            const accountNumber =
                card.querySelector(
                    '.js-account-number'
                );


            const mobileProvider =
                card.querySelector(
                    '.js-mobile-provider'
                );


            const mobileNumber =
                card.querySelector(
                    '.js-mobile-number'
                );


            const transactionId =
                card.querySelector(
                    '.js-transaction-id'
                );


            /* -----------------------------------------------------
               VALIDATION
            ----------------------------------------------------- */

            if (!deliveryDate) {

                alert(
                    'Please select a delivery date.'
                );

                return;
            }


            if (
                upper < 0 ||
                lower < 0
            ) {

                alert(
                    'Cases cannot be negative.'
                );

                return;
            }


            if (paidAmount < 0) {

                alert(
                    'Paid amount cannot be negative.'
                );

                return;
            }


            if (
                upper === 0 &&
                lower === 0 &&
                paidAmount === 0
            ) {

                alert(
                    'Enter at least one delivered case or a paid amount.'
                );

                return;
            }


            const total =
                parseFloat(
                    $totalAmount.value || '0'
                ) || 0;


            if (total <= 0) {

                alert(
                    'Please save the payment plan first.'
                );

                return;
            }


            /* -----------------------------------------------------
               BANK VALIDATION
            ----------------------------------------------------- */

            if (
                paymentMethod ===
                'bank_transfer'
            ) {

                if (!bankSelect.value) {

                    alert(
                        'Please select a bank.'
                    );

                    return;
                }


                if (!branchSelect.value) {

                    alert(
                        'Please select a branch.'
                    );

                    return;
                }
            }


            /* -----------------------------------------------------
               MOBILE VALIDATION
            ----------------------------------------------------- */

            if (
                paymentMethod ===
                'mobile_banking'
            ) {

                if (
                    !mobileProvider.value
                ) {

                    alert(
                        'Please select a mobile banking provider.'
                    );

                    return;
                }


                if (
                    !mobileNumber.value.trim()
                ) {

                    alert(
                        'Please enter the mobile number.'
                    );

                    return;
                }
            }


            saveButton.disabled = true;


            try {

                const predictId =
                    $predict.value.trim();


                const url =
                    routeAddDelivery.replace(
                        'PREDICT_ID',
                        encodeURIComponent(
                            predictId
                        )
                    );


                const payload = {

                    upper_delivered:
                        upper,

                    lower_delivered:
                        lower,

                    paid_amount:
                        paidAmount,

                    delivery_date:
                        deliveryDate,

                    payment_method:
                        paymentMethod,

                    total_amount:
                        total,

                    bank_name:
                        paymentMethod ===
                        'bank_transfer'
                            ? (
                                bankSelect
                                    .options[
                                        bankSelect
                                            .selectedIndex
                                    ]?.text.trim() || ''
                              )
                            : '',

                    branch_name:
                        paymentMethod ===
                        'bank_transfer'
                            ? (
                                branchSelect
                                    .options[
                                        branchSelect
                                            .selectedIndex
                                    ]?.text.trim() || ''
                              )
                            : '',

                    account_name:
                        paymentMethod ===
                        'bank_transfer'
                            ? accountName
                                .value
                                .trim()
                            : '',

                    account_number:
                        paymentMethod ===
                        'bank_transfer'
                            ? accountNumber
                                .value
                                .trim()

                            : paymentMethod ===
                              'mobile_banking'
                                ? mobileNumber
                                    .value
                                    .trim()

                                : '',

                    mobile_provider:
                        paymentMethod ===
                        'mobile_banking'
                            ? mobileProvider.value
                            : '',

                    transaction_id:
                        paymentMethod ===
                        'mobile_banking'
                            ? transactionId
                                .value
                                .trim()
                            : ''
                };


                const response =
                    await fetch(
                        url,
                        {

                            method: 'POST',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrf

                            },

                            body:
                                JSON.stringify(
                                    payload
                                )
                        }
                    );


                const contentType =
                    response.headers
                        .get('content-type') || '';


                if (!response.ok) {

                    let message =
                        'Failed to save delivery.';


                    if (
                        contentType
                            .includes(
                                'application/json'
                            )
                    ) {

                        const errorData =
                            await response.json();

                        message =
                            errorData.message ||
                            message;

                    } else {

                        message =
                            await response.text() ||
                            message;
                    }


                    throw new Error(
                        message
                    );
                }


                const data =
                    await response.json();


                /* -------------------------------------------------
                   UPDATE SUMMARY
                ------------------------------------------------- */

                const plan =
                    data.plan || null;


                const payments =
                    data.payments || [];


                const deliveries =
                    data.deliveries || [];


                const cases =
                    data.cases || {};


                const paidNow =
                    payments.reduce(
                        (
                            sum,
                            payment
                        ) =>
                            sum +
                            parseFloat(
                                payment.amount || 0
                            ),
                        0
                    );


                const totalNow =
                    parseFloat(
                        plan?.total_amount ||
                        total
                    );


                const remainingNow =
                    Math.max(
                        0,
                        totalNow -
                        paidNow
                    );


                $sumTotal.textContent =
                    totalNow.toFixed(2);


                $sumPaid.textContent =
                    paidNow.toFixed(2);


                $sumRemaining.textContent =
                    remainingNow.toFixed(2);


                $casesUpperTotal.textContent =
                    String(
                        cases.total_upper || 0
                    );


                $casesLowerTotal.textContent =
                    String(
                        cases.total_lower || 0
                    );


                $casesUpperRemaining.textContent =
                    String(
                        cases.remaining_upper || 0
                    );


                $casesLowerRemaining.textContent =
                    String(
                        cases.remaining_lower || 0
                    );


                /* -------------------------------------------------
                   REPLACE DRAFT WITH SAVED DELIVERY LIST
                ------------------------------------------------- */

                card.remove();


                renderSavedDeliveries(
                    deliveries
                );


                renderSavedHistory(
                    payments
                );


                /* -------------------------------------------------
                   SUCCESS
                ------------------------------------------------- */

                showDeliverySuccess();


            } catch (error) {

                console.error(error);

                alert(
                    error.message ||
                    'Failed to save delivery.'
                );

            } finally {

                saveButton.disabled = false;
            }

        }
    );


    /* ============================================================
   ADD BANK
   ============================================================ */

const addBankModal =
    document.getElementById('addBankModal');

const newBankName =
    document.getElementById('newBankName');

const addBankError =
    document.getElementById('addBankError');

const btnSaveBank =
    document.getElementById('btnSaveBank');


/*
 * Open Add Bank modal
 */
document.addEventListener('click', function (event) {

    const button =
        event.target.closest('.js-add-bank');

    if (!button) {
        return;
    }

    newBankName.value = '';

    addBankError.textContent = '';
    addBankError.classList.add('d-none');

    const modal =
        bootstrap.Modal.getOrCreateInstance(
            addBankModal
        );

    modal.show();

    setTimeout(() => {
        newBankName.focus();
    }, 300);

});


/*
 * Save Bank
 */
btnSaveBank.addEventListener(
    'click',
    async function () {

        const bankName =
            newBankName.value.trim();

        addBankError.textContent = '';
        addBankError.classList.add('d-none');


        if (!bankName) {

            addBankError.textContent =
                'Please enter a bank name.';

            addBankError.classList.remove(
                'd-none'
            );

            newBankName.focus();

            return;
        }


        btnSaveBank.disabled = true;


        try {

            const response =
                await fetch(
                    "{{ route('admin.banks.store') }}",
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                '{{ csrf_token() }}'
                        },

                        body: JSON.stringify({
                            bank_name: bankName
                        })
                    }
                );


            const data =
                await response.json();


            if (!response.ok) {

                throw new Error(
                    data.message ||
                    'Failed to create bank.'
                );
            }


            /*
             * Close modal
             */
            bootstrap.Modal
                .getInstance(addBankModal)
                ?.hide();


            /*
             * Reload all bank dropdowns
             */
            document
                .querySelectorAll('.js-bank')
                .forEach(select => {

                    loadBanks(select);

                });


        } catch (error) {

            console.error(
                'Add bank error:',
                error
            );

            addBankError.textContent =
                error.message ||
                'Failed to add bank.';

            addBankError.classList.remove(
                'd-none'
            );

        } finally {

            btnSaveBank.disabled = false;

        }

    }
);
   /* ============================================================
   ADD BRANCH
   ============================================================ */

const addBranchModal =
    document.getElementById('addBranchModal');

const selectedBankName =
    document.getElementById('selectedBankName');

const newBranchName =
    document.getElementById('newBranchName');

const newBranchAccountName =
    document.getElementById(
        'newBranchAccountName'
    );

const newBranchAccountNumber =
    document.getElementById(
        'newBranchAccountNumber'
    );

const addBranchError =
    document.getElementById(
        'addBranchError'
    );

const btnSaveBranch =
    document.getElementById(
        'btnSaveBranch'
    );


/*
 * Open Add Branch modal
 */
document.addEventListener(
    'click',
    function (event) {

        const button =
            event.target.closest(
                '.js-add-branch'
            );

        if (!button) {
            return;
        }


        const deliveryCard =
            button.closest(
                '[data-delivery-draft="1"]'
            );


        if (!deliveryCard) {

            alert(
                'Delivery form not found.'
            );

            return;
        }


        const bankSelect =
            deliveryCard.querySelector(
                '.js-bank'
            );


        const bankId =
            bankSelect?.value || '';


        const bankName =
            bankSelect &&
            bankSelect.selectedIndex >= 0
                ? bankSelect.options[
                    bankSelect.selectedIndex
                  ].text
                : '';


        if (!bankId) {

            alert(
                'Please select a bank first.'
            );

            return;
        }


        /*
         * Store selected bank information
         * on the modal.
         */
        addBranchModal.dataset.bankId =
            bankId;

        addBranchModal.dataset.deliveryCardId =
            '';


        selectedBankName.value =
            bankName;


        newBranchName.value = '';

        newBranchAccountName.value = '';

        newBranchAccountNumber.value = '';


        addBranchError.textContent = '';

        addBranchError.classList.add(
            'd-none'
        );


        /*
         * Keep reference to the delivery card.
         */
        addBranchModal._deliveryCard =
            deliveryCard;


        const modal =
            bootstrap.Modal.getOrCreateInstance(
                addBranchModal
            );

        modal.show();


        setTimeout(() => {

            newBranchName.focus();

        }, 300);

    }
);


/*
 * Save Branch
 */
btnSaveBranch.addEventListener(
    'click',
    async function () {

        const bankId =
            addBranchModal.dataset.bankId;

        const deliveryCard =
            addBranchModal._deliveryCard;


        const branchName =
            newBranchName.value.trim();

        const accountName =
            newBranchAccountName.value.trim();

        const accountNumber =
            newBranchAccountNumber.value.trim();


        addBranchError.textContent = '';

        addBranchError.classList.add(
            'd-none'
        );


        if (!bankId) {

            addBranchError.textContent =
                'Please select a bank first.';

            addBranchError.classList.remove(
                'd-none'
            );

            return;
        }


        if (!branchName) {

            addBranchError.textContent =
                'Please enter a branch name.';

            addBranchError.classList.remove(
                'd-none'
            );

            newBranchName.focus();

            return;
        }


        if (!accountName) {

            addBranchError.textContent =
                'Please enter account name.';

            addBranchError.classList.remove(
                'd-none'
            );

            newBranchAccountName.focus();

            return;
        }


        if (!accountNumber) {

            addBranchError.textContent =
                'Please enter account number.';

            addBranchError.classList.remove(
                'd-none'
            );

            newBranchAccountNumber.focus();

            return;
        }


        btnSaveBranch.disabled = true;


        try {

            const response =
                await fetch(
                    "{{ route('admin.bank-branches.store') }}",
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                '{{ csrf_token() }}'
                        },

                        body: JSON.stringify({

                            bank_id:
                                bankId,

                            branch_name:
                                branchName,

                            account_name:
                                accountName,

                            account_number:
                                accountNumber

                        })
                    }
                );


            const data =
                await response.json();


            if (!response.ok) {

                throw new Error(
                    data.message ||
                    'Failed to create branch.'
                );
            }


            /*
             * Close modal
             */
            bootstrap.Modal
                .getInstance(addBranchModal)
                ?.hide();


            /*
             * Reload branches
             * for the same delivery card.
             */
            if (deliveryCard) {

                const branchSelect =
                    deliveryCard.querySelector(
                        '.js-branch'
                    );


                if (branchSelect) {

                    await loadBranches(
                        bankId,
                        branchSelect,
                        deliveryCard
                    );

                }

            }


        } catch (error) {

            console.error(
                'Add branch error:',
                error
            );

            addBranchError.textContent =
                error.message ||
                'Failed to add branch.';

            addBranchError.classList.remove(
                'd-none'
            );

        } finally {

            btnSaveBranch.disabled = false;

        }

    }
);


    /* =========================================================
       RENDER SAVED DELIVERIES
    ========================================================= */

    function renderSavedDeliveries(
        deliveries
    ) {

        /*
         * Remove only saved delivery cards.
         * Keep any active draft if one exists.
         */

        $deliveriesContainer
            .querySelectorAll(
                '.delivery-card'
            )
            .forEach(
                element => element.remove()
            );


        if (
            !deliveries ||
            deliveries.length === 0
        ) {

            return;
        }


        const draft =
            $deliveriesContainer
                .querySelector(
                    '[data-delivery-draft="1"]'
                );


        deliveries
            .slice()
            .reverse()
            .forEach(
                (delivery, reverseIndex) => {

                    const number =
                        deliveries.length -
                        reverseIndex;


                    const upper =
                        parseInt(
                            delivery.upper_delivered ||
                            0,
                            10
                        );


                    const lower =
                        parseInt(
                            delivery.lower_delivered ||
                            0,
                            10
                        );


                    const paid =
                        parseFloat(
                            delivery.paid_amount ||
                            0
                        );


                    const html = `

                        <div class="delivery-card mb-3">

                            <div class="delivery-header">

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                                    <div class="fw-semibold">

                                        <i class="fas fa-check-circle text-success me-2"></i>

                                        Delivery #${number}

                                    </div>

                                    <div class="text-muted small">

                                        ${escapeHtml(
                                            fmtDate(
                                                delivery.delivery_date
                                            )
                                        )}

                                    </div>

                                </div>

                            </div>


                            <div class="p-4">

                                <div class="row g-4">

                                    <div class="col-md-3">

                                        <div class="small text-muted">
                                            Upper delivered
                                        </div>

                                        <div class="fw-bold fs-5">
                                            ${upper}
                                        </div>

                                    </div>


                                    <div class="col-md-3">

                                        <div class="small text-muted">
                                            Lower delivered
                                        </div>

                                        <div class="fw-bold fs-5">
                                            ${lower}
                                        </div>

                                    </div>


                                    <div class="col-md-3">

                                        <div class="small text-muted">
                                            Paid
                                        </div>

                                        <div class="fw-bold fs-5">
                                            BDT ${paid.toFixed(2)}
                                        </div>

                                    </div>


                                    <div class="col-md-3">

                                        <div class="small text-muted">
                                            Date
                                        </div>

                                        <div class="fw-bold">
                                            ${escapeHtml(
                                                fmtDate(
                                                    delivery.delivery_date
                                                )
                                            )}
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                    `;


                    if (draft) {

                      draft.insertAdjacentHTML(
                          'afterend',
                          html
                      );

                  } else {

                      $deliveriesContainer
                          .insertAdjacentHTML(
                              'afterbegin',
                              html
                          );
                  }

                }
            );
    }


    /* =========================================================
       PAYMENT HISTORY
    ========================================================= */

    function renderSavedHistory(
        payments
    ) {

        const tableBody =
            document.querySelector(
                '#historyTable tbody'
            );


        const empty =
            document.getElementById(
                'emptyHistory'
            );


        if (!tableBody) {
            return;
        }


        if (
            !payments ||
            payments.length === 0
        ) {

            tableBody.innerHTML = '';

            if (empty) {
                empty.classList
                    .remove('d-none');
            }

            return;
        }


        if (empty) {
            empty.classList
                .add('d-none');
        }


        tableBody.innerHTML =
            payments.map(
                payment => {

                    const method =
                        String(
                            payment.payment_method ||
                            ''
                        );


                    let icon =
                        'fa-money-bill-wave';

                    let badge =
                        'bg-success';


                    if (
                        method ===
                        'bank_transfer'
                    ) {

                        icon =
                            'fa-university';

                        badge =
                            'bg-primary';
                    }


                    if (
                        method ===
                        'mobile_banking'
                    ) {

                        icon =
                            'fa-mobile-alt';

                        badge =
                            'bg-info';
                    }


                    if (
                        method ===
                        'card'
                    ) {

                        icon =
                            'fa-credit-card';

                        badge =
                            'bg-warning text-dark';
                    }


                    return `

                        <tr>

                            <td class="px-4">
                                ${escapeHtml(
                                    fmtDate(
                                        payment.payment_date
                                    )
                                )}
                            </td>

                            <td>

                                <span class="badge ${badge} px-3 py-2">

                                    <i class="fas ${icon} me-1"></i>

                                    ${escapeHtml(
                                        fmtMethod(method)
                                    )}

                                </span>

                            </td>

                            <td>

                                ${buildPaymentDetails(
                                    payment
                                )}

                            </td>

                            <td class="text-end px-4">

                                <span class="fw-bold text-primary">

                                    BDT
                                    ${(
                                        parseFloat(
                                            payment.amount || 0
                                        )
                                    ).toFixed(2)}

                                </span>

                            </td>

                        </tr>
                    `;
                }
            ).join('');
    }


    function buildPaymentDetails(
        payment
    ) {

        const method =
            String(
                payment.payment_method ||
                ''
            );


        if (
            method === 'cash' ||
            method === 'card'
        ) {

            return `
                <span class="text-muted small">
                    No additional details
                </span>
            `;
        }


        if (
            method === 'bank_transfer'
        ) {

            return `

                <div class="small">

                    <div class="fw-semibold">

                        <i class="fas fa-university text-primary me-1"></i>

                        ${escapeHtml(
                            payment.bank_name ||
                            'Bank Transfer'
                        )}

                    </div>

                    ${
                        payment.branch_name
                            ? `
                                <div class="text-muted">
                                    Branch:
                                    ${escapeHtml(
                                        payment.branch_name
                                    )}
                                </div>
                              `
                            : ''
                    }

                    ${
                        payment.account_name
                            ? `
                                <div class="text-muted">
                                    Account:
                                    ${escapeHtml(
                                        payment.account_name
                                    )}
                                </div>
                              `
                            : ''
                    }

                    ${
                        payment.account_number
                            ? `
                                <div class="text-muted">
                                    A/C:
                                    ${escapeHtml(
                                        payment.account_number
                                    )}
                                </div>
                              `
                            : ''
                    }

                </div>
            `;
        }


        if (
            method === 'mobile_banking'
        ) {

            return `

                <div class="small">

                    ${
                        payment.mobile_provider
                            ? `
                                <div class="fw-semibold">
                                    <i class="fas fa-mobile-alt text-primary me-1"></i>
                                    ${escapeHtml(
                                        payment.mobile_provider
                                    )}
                                </div>
                              `
                            : ''
                    }

                    ${
                        payment.account_number
                            ? `
                                <div class="text-muted">
                                    ${escapeHtml(
                                        payment.account_number
                                    )}
                                </div>
                              `
                            : ''
                    }

                    ${
                        payment.transaction_id
                            ? `
                                <div class="text-muted">
                                    TXN:
                                    ${escapeHtml(
                                        payment.transaction_id
                                    )}
                                </div>
                              `
                            : ''
                    }

                </div>
            `;
        }


        return `
            <span class="text-muted small">
                No additional details
            </span>
        `;
    }


    /* =========================================================
       SUCCESS MESSAGE
    ========================================================= */

    function showDeliverySuccess() {

        const message =
            document.createElement(
                'div'
            );


        message.className =
            'alert alert-success mt-3';


        message.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            Delivery saved successfully.
        `;


        $deliveriesContainer
            .prepend(message);


        setTimeout(
            () => message.remove(),
            4000
        );
    }


    /* =========================================================
       DONE BUTTON
    ========================================================= */

    $btnDone.addEventListener(
        'click',
        async function () {

            const predictId =
                $predict.value.trim();


            if (!predictId) {
                return;
            }


            if (
                !confirm(
                    'Are you sure you want to mark this case as completed?'
                )
            ) {

                return;
            }


            try {

                const url =
                    routeDone.replace(
                        'PREDICT_ID',
                        encodeURIComponent(
                            predictId
                        )
                    );


                const response =
                    await fetch(
                        url,
                        {

                            method: 'POST',

                            headers: {

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrf
                            }

                        }
                    );


                if (!response.ok) {

                    const text =
                        await response.text();

                    throw new Error(
                        text ||
                        'Failed to close case.'
                    );
                }


                caseClosed = true;


                $caseClosedBadge
                    .classList
                    .remove('d-none');


                $btnNewDelivery
                    .classList
                    .add('d-none');


                $deliveriesContainer
                    .querySelectorAll(
                        '[data-delivery-draft="1"]'
                    )
                    .forEach(
                        card => card.remove()
                    );


                alert(
                    'Case marked as completed.'
                );


            } catch (error) {

                console.error(error);

                alert(
                    error.message ||
                    'Failed to complete case.'
                );
            }

        }
    );


})();
</script>

@endpush

@endsection