<?php

namespace App\Services\Reports;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InactivePatientReportService
{
    /**
     * ==========================================================
     * Base Query
     * ==========================================================
     *
     * Returns only inactive, non-deleted patients.
     */
    public function getQuery(Request $request): Builder
    {
        $query = Patient::query()
            ->where('status', 'inactive')
            ->where(function ($q) {
                $q->where('is_patient_page_deleted', false)
                  ->orWhereNull('is_patient_page_deleted');
            });

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('Predict3DId', 'like', "%{$search}%")
                    ->orWhere('FullName', 'like', "%{$search}%")
                    ->orWhere('PhoneNumber', 'like', "%{$search}%")
                    ->orWhere('DoctorName', 'like', "%{$search}%")
                    ->orWhere('ChamberName', 'like', "%{$search}%")
                    ->orWhere('TerritoryName', 'like', "%{$search}%")
                    ->orWhere('RegionalName', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Scanning For
        |--------------------------------------------------------------------------
        */

        if ($request->filled('scanning_for')) {

            $query->where(
                'ScanningFor',
                $request->scanning_for
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Gender
        |--------------------------------------------------------------------------
        */

        if ($request->filled('gender')) {

            $query->where(
                'Gender',
                $request->gender
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Doctor
        |--------------------------------------------------------------------------
        */

        if ($request->filled('doctor')) {

            $query->where(
                'DoctorName',
                $request->doctor
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Region
        |--------------------------------------------------------------------------
        */

        if ($request->filled('region')) {

            $query->where(
                'RegionalName',
                $request->region
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Territory
        |--------------------------------------------------------------------------
        */

        if ($request->filled('territory')) {

            $query->where(
                'TerritoryName',
                $request->territory
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Default Sorting
        |--------------------------------------------------------------------------
        */

        return $query->orderBy(
            'created_at',
            'desc'
        );
    }

        /**
     * ==========================================================
     * Summary
     * ==========================================================
     *
     * Provides summary statistics for inactive patients.
     */
    public function getSummary(Request $request): array
    {
        $query = $this->getQuery($request);

        $totalPatients = (clone $query)->count();

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
     * Patient Records
     * ==========================================================
     *
     * Returns paginated inactive patient records.
     */
    public function getPatients(Request $request)
    {
        return $this->getQuery($request)
            ->paginate(15)
            ->withQueryString();
    }

        /**
     * ==========================================================
     * Filter Options
     * ==========================================================
     *
     * Provides dropdown values for the inactive patient report.
     */
    public function getFilterOptions(): array
    {
        $baseQuery = Patient::query()
            ->where('status', 'inactive')
            ->where(function ($q) {
                $q->where('is_patient_page_deleted', false)
                  ->orWhereNull('is_patient_page_deleted');
            });

        return [

            /*
            |--------------------------------------------------------------------------
            | Doctors
            |--------------------------------------------------------------------------
            */

            'doctors' => (clone $baseQuery)
                ->whereNotNull('DoctorName')
                ->where('DoctorName', '!=', '')
                ->distinct()
                ->orderBy('DoctorName')
                ->pluck('DoctorName'),


            /*
            |--------------------------------------------------------------------------
            | Regions
            |--------------------------------------------------------------------------
            */

            'regions' => (clone $baseQuery)
                ->whereNotNull('RegionalName')
                ->where('RegionalName', '!=', '')
                ->distinct()
                ->orderBy('RegionalName')
                ->pluck('RegionalName'),


            /*
            |--------------------------------------------------------------------------
            | Territories
            |--------------------------------------------------------------------------
            */

            'territories' => (clone $baseQuery)
                ->whereNotNull('TerritoryName')
                ->where('TerritoryName', '!=', '')
                ->distinct()
                ->orderBy('TerritoryName')
                ->pluck('TerritoryName'),


            /*
            |--------------------------------------------------------------------------
            | Genders
            |--------------------------------------------------------------------------
            */

            'genders' => [
                'Male',
                'Female',
                'Custom',
            ],


            /*
            |--------------------------------------------------------------------------
            | Scanning Types
            |--------------------------------------------------------------------------
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
     * Complete Report
     * ==========================================================
     *
     * Prepares all data required by the report index page.
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
     * Patient Details
     * ==========================================================
     *
     * Returns one inactive patient for the details page.
     */
    public function getDetails(string $predict3dId): Patient
    {
        return Patient::query()
            ->where('Predict3DId', $predict3dId)
            ->where('status', 'inactive')
            ->where(function ($q) {
                $q->where('is_patient_page_deleted', false)
                  ->orWhereNull('is_patient_page_deleted');
            })
            ->firstOrFail();
    }


    /**
     * ==========================================================
     * Export Report Data
     * ==========================================================
     *
     * Returns all filtered inactive patients for Excel.
     */
    public function exportReportData(Request $request)
    {
        return $this->getQuery($request)
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
        return [

            'patients' => $this->getQuery($request)->get(),

            'summary' => $this->getSummary($request),

            'filters' => [

                'search' => $request->input('search'),

                'gender' => $request->input('gender'),

                'scanning_for' => $request->input('scanning_for'),

                'doctor' => $request->input('doctor'),

                'region' => $request->input('region'),

                'territory' => $request->input('territory'),

                'date_from' => $request->input('date_from'),

                'date_to' => $request->input('date_to'),

            ],

            'generated_at' => now(),

        ];
    }
}