<?php

namespace App\Services\Reports;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PatientReportService
{
    /**
     * ==========================================================
     * Base Query
     * ==========================================================
     *
     * Central query for the Patient Report.
     *
     * Default behavior:
     * - Excludes patients marked as page deleted
     * - Supports search
     * - Supports status filtering
     * - Supports gender filtering
     * - Supports scanning type filtering
     * - Supports doctor filtering
     * - Supports regional filtering
     * - Supports territory filtering
     */
    public function getQuery(Request $request): Builder
    {
        $query = Patient::query()
            ->where(function (Builder $q) {
                $q->whereNull('is_patient_page_deleted')
                  ->orWhere('is_patient_page_deleted', false);
            });

        /**
         * ======================================================
         * Search
         * ======================================================
         */
        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->where(function (Builder $q) use ($search) {

                $q->where('Predict3DId', 'like', "%{$search}%")
                    ->orWhere('FullName', 'like', "%{$search}%")
                    ->orWhere('PhoneNumber', 'like', "%{$search}%")
                    ->orWhere('DoctorName', 'like', "%{$search}%")
                    ->orWhere('ChamberName', 'like', "%{$search}%")
                    ->orWhere('TerritoryName', 'like', "%{$search}%")
                    ->orWhere('RegionalName', 'like', "%{$search}%");

            });
        }

        /**
         * ======================================================
         * Status Filter
         * ======================================================
         */
        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->input('status')
            );
        }

        /**
         * ======================================================
         * Gender Filter
         * ======================================================
         */
        if ($request->filled('gender')) {

            $query->where(
                'Gender',
                $request->input('gender')
            );
        }

        /**
         * ======================================================
         * Scanning For Filter
         * ======================================================
         */
        if ($request->filled('scanning_for')) {

            $query->where(
                'ScanningFor',
                $request->input('scanning_for')
            );
        }

        /**
         * ======================================================
         * Doctor Filter
         * ======================================================
         */
        if ($request->filled('doctor')) {

            $query->where(
                'DoctorName',
                $request->input('doctor')
            );
        }

        /**
         * ======================================================
         * Region Filter
         * ======================================================
         */
        if ($request->filled('region')) {

            $query->where(
                'RegionalName',
                $request->input('region')
            );
        }

        /**
         * ======================================================
         * Territory Filter
         * ======================================================
         */
        if ($request->filled('territory')) {

            $query->where(
                'TerritoryName',
                $request->input('territory')
            );
        }

        return $query;
    }


    /**
     * ==========================================================
     * Summary
     * ==========================================================
     */
    public function getSummary(Request $request): array
    {
        $query = $this->getQuery($request);

        $totalPatients = (clone $query)->count();

        $activePatients = (clone $query)
            ->where('status', 'active')
            ->count();

        $inactivePatients = (clone $query)
            ->where('status', 'inactive')
            ->count();

        $malePatients = (clone $query)
            ->where('Gender', 'Male')
            ->count();

        $femalePatients = (clone $query)
            ->where('Gender', 'Female')
            ->count();

        $customGenderPatients = (clone $query)
            ->where('Gender', 'Custom')
            ->count();

        $alignerPatients = (clone $query)
            ->where('ScanningFor', 'Aligner')
            ->count();

        $zirconiaPatients = (clone $query)
            ->where('ScanningFor', 'Zirconia')
            ->count();

        $othersPatients = (clone $query)
            ->where('ScanningFor', 'Others')
            ->count();

        return [

            'total_patients' => $totalPatients,

            'active_patients' => $activePatients,

            'inactive_patients' => $inactivePatients,

            'male_patients' => $malePatients,

            'female_patients' => $femalePatients,

            'custom_gender_patients' => $customGenderPatients,

            'aligner_patients' => $alignerPatients,

            'zirconia_patients' => $zirconiaPatients,

            'others_patients' => $othersPatients,

        ];
    }

        /**
     * ==========================================================
     * Complete Report
     * ==========================================================
     *
     * Prepares all data required by the Patient Report index page.
     */
    public function getReport(Request $request): array
    {
        return [
            'patients' => $this->getPatients($request),

            'summary' => $this->getSummary($request),

            'filters' => $this->getFilterOptions(),
        ];
    }

        /**
     * ==========================================================
     * Patient Records
     * ==========================================================
     *
     * Returns the filtered patient records for the report table.
     */
    public function getPatients(Request $request)
    {
        return $this->getQuery($request)
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();
    }


    /**
     * ==========================================================
     * Filter Options
     * ==========================================================
     *
     * Provides unique values for the report filters.
     */
    public function getFilterOptions(): array
    {
        return [

            /**
             * --------------------------------------------------
             * Doctors
             * --------------------------------------------------
             */
            'doctors' => Patient::query()
                ->where(function (Builder $query) {
                    $query->whereNull('is_patient_page_deleted')
                          ->orWhere('is_patient_page_deleted', false);
                })
                ->whereNotNull('DoctorName')
                ->where('DoctorName', '!=', '')
                ->distinct()
                ->orderBy('DoctorName')
                ->pluck('DoctorName'),


            /**
             * --------------------------------------------------
             * Regions
             * --------------------------------------------------
             */
            'regions' => Patient::query()
                ->where(function (Builder $query) {
                    $query->whereNull('is_patient_page_deleted')
                          ->orWhere('is_patient_page_deleted', false);
                })
                ->whereNotNull('RegionalName')
                ->where('RegionalName', '!=', '')
                ->distinct()
                ->orderBy('RegionalName')
                ->pluck('RegionalName'),


            /**
             * --------------------------------------------------
             * Territories
             * --------------------------------------------------
             */
            'territories' => Patient::query()
                ->where(function (Builder $query) {
                    $query->whereNull('is_patient_page_deleted')
                          ->orWhere('is_patient_page_deleted', false);
                })
                ->whereNotNull('TerritoryName')
                ->where('TerritoryName', '!=', '')
                ->distinct()
                ->orderBy('TerritoryName')
                ->pluck('TerritoryName'),


            /**
             * --------------------------------------------------
             * Statuses
             * --------------------------------------------------
             */
            'statuses' => [
                'active',
                'inactive',
            ],


            /**
             * --------------------------------------------------
             * Genders
             * --------------------------------------------------
             */
            'genders' => [
                'Male',
                'Female',
                'Custom',
            ],


            /**
             * --------------------------------------------------
             * Scanning Types
             * --------------------------------------------------
             */
            'scanning_types' => [
                'Aligner',
                'Zirconia',
                'Others',
            ],
        ];
    }

        /**
     * ==========================================================
     * Patient Details
     * ==========================================================
     *
     * Returns a single patient record for the details page.
     *
     * Predict3DId is the primary key and is a string.
     */
    public function getDetails(string $predict3dId): Patient
    {
        return Patient::query()
            ->where('Predict3DId', $predict3dId)
            ->where(function (Builder $query) {
                $query->whereNull('is_patient_page_deleted')
                      ->orWhere('is_patient_page_deleted', false);
            })
            ->firstOrFail();
    }


    /**
     * ==========================================================
     * Export Report Data
     * ==========================================================
     *
     * Returns all filtered patient records for Excel export.
     *
     * Unlike the web table, this method does not paginate.
     */
    public function exportReportData(Request $request)
    {
        return $this->getQuery($request)
            ->orderByDesc('created_at')
            ->get();
    }


    /**
     * ==========================================================
     * PDF Report Data
     * ==========================================================
     *
     * Prepares all data required by the PDF view.
     */
    public function exportPdfData(Request $request): array
    {
        $query = $this->getQuery($request);

        $patients = (clone $query)
            ->orderByDesc('created_at')
            ->get();

        return [

            'patients' => $patients,

            'summary' => $this->getSummary($request),

            'filters' => [
                'search' => $request->input('search'),
                'status' => $request->input('status'),
                'gender' => $request->input('gender'),
                'scanning_for' => $request->input('scanning_for'),
                'doctor' => $request->input('doctor'),
                'region' => $request->input('region'),
                'territory' => $request->input('territory'),
            ],

            'generated_at' => now(),

        ];
    }

}

