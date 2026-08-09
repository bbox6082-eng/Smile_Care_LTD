<?php

namespace App\Services\Reports;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DoctorWisePatientsReportService
{
    /**
     * ==========================================================
     * Base Query
     * ==========================================================
     *
     * Central query for Doctor-wise Patients Report.
     *
     * Default behavior:
     * - Excludes patients marked as page deleted
     * - Supports search
     * - Supports doctor filtering
     * - Supports patient status filtering
     * - Supports gender filtering
     * - Supports scanning type filtering
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
         *
         * Search across:
         * - Predict3D ID
         * - Patient name
         * - Phone number
         * - Doctor name
         * - Chamber
         */
        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->where(function (Builder $q) use ($search) {

                $q->where('Predict3DId', 'like', "%{$search}%")
                    ->orWhere('FullName', 'like', "%{$search}%")
                    ->orWhere('PhoneNumber', 'like', "%{$search}%")
                    ->orWhere('DoctorName', 'like', "%{$search}%")
                    ->orWhere('ChamberName', 'like', "%{$search}%");
            });
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
         * Patient Status Filter
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
         * Scanning Type Filter
         * ======================================================
         */
        if ($request->filled('scanning_for')) {

            $query->where(
                'ScanningFor',
                $request->input('scanning_for')
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

        $totalDoctors = (clone $query)
            ->whereNotNull('DoctorName')
            ->where('DoctorName', '!=', '')
            ->distinct('DoctorName')
            ->count('DoctorName');

        $activePatients = (clone $query)
            ->where('status', 'active')
            ->count();

        $inactivePatients = (clone $query)
            ->where('status', 'inactive')
            ->count();

        return [
            'total_doctors'   => $totalDoctors,
            'total_patients'  => $totalPatients,
            'active_patients' => $activePatients,
            'inactive_patients' => $inactivePatients,
        ];
    }


    /**
     * ==========================================================
     * Doctor-wise Patient Groups
     * ==========================================================
     *
     * Returns patients grouped by DoctorName.
     *
     * Doctors without a valid name are grouped as "Unknown".
     */
    public function getDoctorGroups(Request $request)
    {
        return $this->getQuery($request)
            ->orderBy('DoctorName')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(function ($patient) {
                $doctor = trim((string) $patient->DoctorName);

                return $doctor !== ''
                    ? $doctor
                    : 'Unknown';
            });
    }


    /**
     * ==========================================================
     * Paginated Patient Records
     * ==========================================================
     */
    public function getPatients(Request $request)
    {
        return $this->getQuery($request)
            ->orderBy('DoctorName')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();
    }


    /**
     * ==========================================================
     * Doctor List
     * ==========================================================
     *
     * Provides unique doctors for the filter dropdown.
     */
    public function getDoctors()
    {
        return Patient::query()
            ->where(function (Builder $q) {
                $q->whereNull('is_patient_page_deleted')
                  ->orWhere('is_patient_page_deleted', false);
            })
            ->whereNotNull('DoctorName')
            ->where('DoctorName', '!=', '')
            ->distinct()
            ->orderBy('DoctorName')
            ->pluck('DoctorName');
    }


    /**
     * ==========================================================
     * Filter Options
     * ==========================================================
     */
    public function getFilterOptions(): array
    {
        return [
            'doctors' => $this->getDoctors(),

            'statuses' => [
                'active',
                'inactive',
            ],

            'genders' => [
                'Male',
                'Female',
                'Custom',
            ],

            'scanning_types' => [
                'Aligner',
                'Zirconia',
                'Others',
            ],
        ];
    }


    /**
     * ==========================================================
     * Doctor Details
     * ==========================================================
     *
     * Returns all patients belonging to one doctor.
     */
    public function getDoctorPatients(
        Request $request,
        string $doctor
    ) {
        $query = $this->getQuery($request);

        return $query
            ->where('DoctorName', $doctor)
            ->orderByDesc('created_at')
            ->get();
    }


    /**
     * ==========================================================
     * Excel Export Data
     * ==========================================================
     *
     * Returns all filtered records.
     * No pagination is applied.
     */
    public function exportReportData(Request $request)
    {
        return $this->getQuery($request)
            ->orderBy('DoctorName')
            ->orderByDesc('created_at')
            ->get();
    }


    /**
     * ==========================================================
     * PDF Export Data
     * ==========================================================
     */
    public function exportPdfData(Request $request): array
    {
        $query = $this->getQuery($request);

        $patients = (clone $query)
            ->orderBy('DoctorName')
            ->orderByDesc('created_at')
            ->get();

        return [
            'patients' => $patients,

            'summary' => $this->getSummary($request),

            'doctorGroups' => $patients->groupBy(function ($patient) {
                $doctor = trim((string) $patient->DoctorName);

                return $doctor !== ''
                    ? $doctor
                    : 'Unknown';
            }),

            'filters' => [
                'search'       => $request->input('search'),
                'doctor'       => $request->input('doctor'),
                'status'       => $request->input('status'),
                'gender'       => $request->input('gender'),
                'scanning_for' => $request->input('scanning_for'),
            ],

            'generated_at' => now(),
        ];
    }
}