<?php

namespace App\Services\Reports;

use App\Models\MarketingRepresentative;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MrWisePatientsReportService
{
    /**
     * ==========================================================
     * Base Patient Query
     * ==========================================================
     *
     * Patients are connected to Marketing Representatives through:
     *
     * Patient.DoctorName
     *        ↓
     * Doctor.name
     *        ↓
     * Doctor.marketing_representative_name
     *
     * There is no direct MR column on patients.
     */
    public function getQuery(Request $request): Builder
    {
        $query = Patient::query()
            ->leftJoin(
                'doctors',
                'doctors.name',
                '=',
                'patients.DoctorName'
            )
            ->where(
                'patients.is_patient_page_deleted',
                false
            );


        /*
        |--------------------------------------------------------------------------
        | Select Patient Fields + MR
        |--------------------------------------------------------------------------
        */

        $query->select([
            'patients.Predict3DId',
            'patients.FullName',
            'patients.ScanningFor',
            'patients.ScanningForOthers',
            'patients.case_type',
            'patients.DoctorName',
            'patients.doctor_email',
            'patients.ChamberName',
            'patients.TerritoryName',
            'patients.RegionalName',
            'patients.PhoneNumber',
            'patients.EmergencyContact',
            'patients.Gender',
            'patients.DateOfBirth',
            'patients.Address',
            'patients.UpperCases',
            'patients.LowerCases',
            'patients.status',
            'patients.created_by',
            'patients.created_at',
            'patients.updated_at',

            DB::raw(
                "COALESCE(
                    doctors.marketing_representative_name,
                    ''
                ) as MRName"
            ),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from')) {

            $query->whereDate(
                'patients.created_at',
                '>=',
                $request->from
            );
        }


        if ($request->filled('to')) {

            $query->whereDate(
                'patients.created_at',
                '<=',
                $request->to
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Marketing Representative Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('mr')) {

            $query->where(
                'doctors.marketing_representative_name',
                $request->mr
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Doctor Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('doctor')) {

            $query->where(
                'patients.DoctorName',
                $request->doctor
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'patients.status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Gender Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('gender')) {

            $query->where(
                'patients.Gender',
                $request->gender
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Scanning Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('scanning_for')) {

            $query->where(
                'patients.ScanningFor',
                $request->scanning_for
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $keyword = trim(
                $request->search
            );


            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'patients.Predict3DId',
                    'LIKE',
                    "%{$keyword}%"
                )

                ->orWhere(
                    'patients.FullName',
                    'LIKE',
                    "%{$keyword}%"
                )

                ->orWhere(
                    'patients.PhoneNumber',
                    'LIKE',
                    "%{$keyword}%"
                )

                ->orWhere(
                    'patients.DoctorName',
                    'LIKE',
                    "%{$keyword}%"
                )

                ->orWhere(
                    'doctors.marketing_representative_name',
                    'LIKE',
                    "%{$keyword}%"
                );

            });
        }


        return $query;
    }


    /**
     * ==========================================================
     * Marketing Representative List
     * ==========================================================
     */
    public function getMarketingRepresentatives()
    {
        return MarketingRepresentative::query()
            ->orderBy('name')
            ->pluck('name');
    }


    /**
     * ==========================================================
     * Doctor List
     * ==========================================================
     */
    public function getDoctors()
    {
        return Patient::query()
            ->where(
                'is_patient_page_deleted',
                false
            )
            ->whereNotNull('DoctorName')
            ->where(
                'DoctorName',
                '!=',
                ''
            )
            ->orderBy('DoctorName')
            ->distinct()
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

            'statuses' => Patient::query()
                ->whereNotNull('status')
                ->where('status', '!=', '')
                ->distinct()
                ->orderBy('status')
                ->pluck('status'),

            'genders' => Patient::query()
                ->whereNotNull('Gender')
                ->where('Gender', '!=', '')
                ->distinct()
                ->orderBy('Gender')
                ->pluck('Gender'),

            'scanning_types' => Patient::query()
                ->whereNotNull('ScanningFor')
                ->where('ScanningFor', '!=', '')
                ->distinct()
                ->orderBy('ScanningFor')
                ->pluck('ScanningFor'),

        ];
    }


    /**
     * ==========================================================
     * Summary
     * ==========================================================
     */
    public function getSummary(Request $request): array
    {
        $patients = $this->getQuery($request);


        $totalPatients = (clone $patients)
            ->count('patients.Predict3DId');


        $activePatients = (clone $patients)
            ->where(
                'patients.status',
                'active'
            )
            ->count('patients.Predict3DId');


        $inactivePatients = (clone $patients)
            ->where(
                'patients.status',
                'inactive'
            )
            ->count('patients.Predict3DId');


        $totalMrs = (clone $patients)
            ->whereNotNull(
                'doctors.marketing_representative_name'
            )
            ->where(
                'doctors.marketing_representative_name',
                '!=',
                ''
            )
            ->distinct(
                'doctors.marketing_representative_name'
            )
            ->count(
                'doctors.marketing_representative_name'
            );


        $unassignedPatients = (clone $patients)
            ->where(function ($q) {

                $q->whereNull(
                    'doctors.marketing_representative_name'
                )

                ->orWhere(
                    'doctors.marketing_representative_name',
                    ''
                );

            })
            ->count('patients.Predict3DId');


        return [

            'total_mrs' =>
                $totalMrs,

            'total_patients' =>
                $totalPatients,

            'active_patients' =>
                $activePatients,

            'inactive_patients' =>
                $inactivePatients,

            'unassigned_patients' =>
                $unassignedPatients,

        ];
    }


    /**
     * ==========================================================
     * MR-wise Patient Groups
     * ==========================================================
     */
    public function getMrWisePatients(
        Request $request
    ) {

        $patients = $this->getQuery($request)
            ->orderBy(
                'doctors.marketing_representative_name'
            )
            ->orderBy(
                'patients.FullName'
            )
            ->get();


        return $patients
            ->groupBy(function ($patient) {

                return $patient->MRName
                    ?: 'Unassigned';

            });
    }


    /**
     * ==========================================================
     * Paginated MR Summary
     * ==========================================================
     */
    public function getMrWisePatientsPaginated(
        Request $request,
        int $perPage = 10
    ) {

        $groups = $this->getMrWisePatients($request);


        $rows = $groups
            ->map(function ($patients, $mr) {

                $total = $patients->count();


                $active = $patients
                    ->where(
                        'status',
                        'active'
                    )
                    ->count();


                $inactive = $patients
                    ->where(
                        'status',
                        'inactive'
                    )
                    ->count();


                $doctors = $patients
                    ->pluck('DoctorName')
                    ->filter()
                    ->unique()
                    ->count();


                return [

                    'mr' =>
                        $mr,

                    'patients' =>
                        $total,

                    'active' =>
                        $active,

                    'inactive' =>
                        $inactive,

                    'doctors' =>
                        $doctors,

                ];

            })
            ->sortByDesc('patients')
            ->values();


        $currentPage = (int) (
            $request->input(
                'page',
                1
            )
        );


        $items = $rows->forPage(
            $currentPage,
            $perPage
        );


        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $rows->count(),
            $perPage,
            $currentPage,
            [
                'path' =>
                    $request->url(),

                'query' =>
                    $request->query(),
            ]
        );
    }


    /**
     * ==========================================================
     * Individual MR Details
     * ==========================================================
     */
    public function getMrDetails(
        Request $request,
        string $mr
    ): array {

        if ($mr === 'Unassigned') {

            $patients = $this->getQuery($request)
                ->where(function ($q) {

                    $q->whereNull(
                        'doctors.marketing_representative_name'
                    )

                    ->orWhere(
                        'doctors.marketing_representative_name',
                        ''
                    );

                })
                ->orderBy(
                    'patients.FullName'
                )
                ->get();

        } else {

            $patients = $this->getQuery($request)
                ->where(
                    'doctors.marketing_representative_name',
                    $mr
                )
                ->orderBy(
                    'patients.FullName'
                )
                ->get();

        }


        $totalPatients =
            $patients->count();


        $activePatients =
            $patients
                ->where(
                    'status',
                    'active'
                )
                ->count();


        $inactivePatients =
            $patients
                ->where(
                    'status',
                    'inactive'
                )
                ->count();


        $doctors =
            $patients
                ->pluck('DoctorName')
                ->filter()
                ->unique()
                ->values();


        return [

            'mr' =>
                $mr,

            'patients' =>
                $patients,

            'totalPatients' =>
                $totalPatients,

            'activePatients' =>
                $activePatients,

            'inactivePatients' =>
                $inactivePatients,

            'doctors' =>
                $doctors,

        ];
    }


    /**
     * ==========================================================
     * Export Data
     * ==========================================================
     */
    public function exportReportData(
        Request $request
    ) {

        return $this->getQuery($request)
            ->orderBy(
                'doctors.marketing_representative_name'
            )
            ->orderBy(
                'patients.FullName'
            )
            ->get();
    }


    /**
     * ==========================================================
     * PDF Data
     * ==========================================================
     */
    public function exportPdfData(
        Request $request
    ): array {

        return [

            'summary' =>
                $this->getSummary($request),

            'mrGroups' =>
                $this->getMrWisePatients($request),

            'filters' => [

                'search' =>
                    $request->input('search'),

                'mr' =>
                    $request->input('mr'),

                'doctor' =>
                    $request->input('doctor'),

                'status' =>
                    $request->input('status'),

                'gender' =>
                    $request->input('gender'),

                'scanning_for' =>
                    $request->input('scanning_for'),

                'from' =>
                    $request->input('from'),

                'to' =>
                    $request->input('to'),

            ],

            'generatedAt' =>
                now(),

        ];
    }
}