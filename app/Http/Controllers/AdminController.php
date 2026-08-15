<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Region;
use App\Models\Area;
use App\Models\Territory;
use App\Models\Inventory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductRequest;
use App\Models\User;
use App\Models\ProductionStep;
use App\Models\MarketingRepresentative;
use App\Models\PaymentPlan;
use App\Models\PaymentPlanPayment;
use App\Models\PaymentPlanDelivery;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PatientsExport;
use App\Exports\PaymentsExport;
use App\Exports\DoctorsExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\BankBranch;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_patients' => Patient::count(),
            'total_inventory' => Inventory::count(),
            'low_stock_items' => Inventory::where('quantity', '<', 10)->count(),
            'today_payments' => Payment::whereDate('payment_date', today())->sum('amount'),
            'recent_patients' => Patient::latest()->take(5)->get(),
        ];

        // ===== Dashboard Analytics (last 12 months) =====
        $start = Carbon::now()->startOfMonth()->subMonths(11);
        $months = collect(range(0, 11))
            ->map(fn($i) => $start->copy()->addMonths($i))
            ->values();
        $labels = $months->map(fn($m) => $m->format('M Y'));

        // Patients per month by case_type (stacked)
        $caseTypes = ['Deep CBCD','Full Case','Short Case','Single ARC','Retainer'];
        $rawPatients = DB::table('patients')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, case_type, COUNT(*) as c")
            ->where('created_at', '>=', $start)
            ->groupBy('ym','case_type')
            ->get();
        $patientsByMonth = [];
        foreach ($caseTypes as $ct) { $patientsByMonth[$ct] = array_fill(0, 12, 0); }
        foreach ($rawPatients as $row) {
            $idx = $months->search(fn($m) => $m->format('Y-m') === $row->ym);
            if ($idx !== false && in_array($row->case_type, $caseTypes, true)) {
                $patientsByMonth[$row->case_type][$idx] = (int) $row->c;
            }
        }

        // Payments per month (line) from payment plan payments
        $rawPays = DB::table('payment_plan_payments')
            ->selectRaw("DATE_FORMAT(payment_date, '%Y-%m') as ym, SUM(amount) as total")
            ->where('payment_date', '>=', $start)
            ->groupBy('ym')
            ->get();
        $paymentsByMonth = array_fill(0, 12, 0.0);
        foreach ($rawPays as $row) {
            $idx = $months->search(fn($m) => $m->format('Y-m') === $row->ym);
            if ($idx !== false) { $paymentsByMonth[$idx] = (float) $row->total; }
        }

        $charts = [
            'labels' => $labels,
            'patients' => $patientsByMonth,
            'payments' => $paymentsByMonth,
        ];

        return view('admin.dashboard', compact('stats','charts','caseTypes'));
    }

    // ===== Payments: Shortlist (last 15 days) =====
    public function paymentsShortlistLast15()
    {
        $since = Carbon::now()->subDays(15);
        $payments = PaymentPlanPayment::query()
            ->join('payment_plans as pp', 'pp.id', '=', 'payment_plan_payments.payment_plan_id')
            ->join('patients as pat', 'pat.Predict3DId', '=', 'pp.predict3d_id')
            ->leftJoin('users as u', 'u.id', '=', 'payment_plan_payments.created_by')
            ->whereDate('payment_plan_payments.payment_date', '>=', $since->toDateString())
            ->orderByDesc('payment_plan_payments.payment_date')
            ->select([
                'payment_plan_payments.id as id',
                'payment_plan_payments.amount as amount',
                'payment_plan_payments.payment_method as payment_method',
                'payment_plan_payments.payment_date as payment_date',
                'pp.id as plan_id',
                'pp.predict3d_id as predict3d_id',
                'pat.FullName as patient_full_name',
                'u.name as processor_name',
            ])->get();

        return view('admin.payments.shortlist', [
            'since' => $since,
            'payments' => $payments,
        ]);
    }

    // ===== Payments: Export all (Excel, colorful columns) =====
    public function exportAllPayments()
    {
        $data = PaymentPlanPayment::query()
            ->join('payment_plans as pp', 'pp.id', '=', 'payment_plan_payments.payment_plan_id')
            ->join('patients as pat', 'pat.Predict3DId', '=', 'pp.predict3d_id')
            ->leftJoin('users as u', 'u.id', '=', 'payment_plan_payments.created_by')
            ->orderByDesc('payment_plan_payments.payment_date')
            ->select([
                'payment_plan_payments.amount as amount',
                'payment_plan_payments.payment_method as payment_method',
                'payment_plan_payments.payment_date as payment_date',
                'pp.id as plan_id',
                'pp.predict3d_id as predict3d_id',
                'pp.total_amount as total_amount',
                'pp.remaining_amount as remaining_amount',
                'pp.is_installment as is_installment',
                'pp.next_payment_date as next_payment_date',
                'pat.FullName as patient_full_name',
                'pat.PhoneNumber as patient_phone',
                'u.name as processor_name',
            ])->get();

        $fileName = 'payments_all_'.now()->format('Ymd_His').'.xlsx';
        return Excel::download(new PaymentsExport($data, 'All Payments'), $fileName);
    }

    public function exportFilteredPayments(Request $request)
    {
        $query = $this->buildPaymentsQuery($request);
        $data = $query->leftJoin('users as u', 'u.id', '=', 'payment_plans.created_by')
            ->orderByDesc('payment_plans.updated_at')
            ->select([
                'payment_plans.id as plan_id',
                'payment_plans.predict3d_id as predict3d_id',
                'payment_plans.total_amount as total_amount',
                'payment_plans.remaining_amount as remaining_amount',
                'payment_plans.is_installment as is_installment',
                'payment_plans.next_payment_date as next_payment_date',
                'payment_plans.payment_method as plan_payment_method',
                'pat.FullName as patient_full_name',
                'pat.PhoneNumber as patient_phone',
                'u.name as processor_name',
                DB::raw('(select COALESCE(sum(p2.amount),0) from payment_plan_payments p2 where p2.payment_plan_id = payment_plans.id) as total_paid'),
                DB::raw('(select p3.payment_date from payment_plan_payments p3 where p3.payment_plan_id = payment_plans.id order by p3.payment_date desc, p3.id desc limit 1) as latest_payment_date'),
                DB::raw('(select p4.payment_method from payment_plan_payments p4 where p4.payment_plan_id = payment_plans.id order by p4.payment_date desc, p4.id desc limit 1) as latest_payment_method')
            ])->get();

        // Map to structure expected by PaymentsExport
        $mappedData = $data->map(function ($row) {
            $row->amount = $row->total_paid; // Or latest payment amount if that is what they want
            $row->payment_method = $row->latest_payment_method ?: $row->plan_payment_method;
            $row->payment_date = $row->latest_payment_date;
            return $row;
        });

        $fileName = 'payments_filtered_'.now()->format('Ymd_His').'.xlsx';
        return Excel::download(new PaymentsExport($mappedData, 'Filtered Payments'), $fileName);
    }

    // ===== Payments: Pending installments (unpaid remaining) =====
    public function paymentsInstallmentsPending()
    {
        $plans = PaymentPlan::query()
            ->join('patients as pat', 'pat.Predict3DId', '=', 'payment_plans.predict3d_id')
            ->where('payment_plans.is_installment', true)
            ->where(function($q){
                $q->whereNull('payment_plans.remaining_amount')
                  ->orWhere('payment_plans.remaining_amount', '>', 0);
            })
            ->orderBy('payment_plans.next_payment_date', 'asc')
            ->select([
                'payment_plans.id as plan_id',
                'payment_plans.predict3d_id',
                'payment_plans.total_amount',
                'payment_plans.remaining_amount',
                'payment_plans.payment_method',
                'payment_plans.next_payment_date',
                'pat.FullName as patient_full_name',
                DB::raw('(select COALESCE(sum(p2.amount),0) from payment_plan_payments p2 where p2.payment_plan_id = payment_plans.id) as total_paid'),
            ])->get();

        return view('admin.payments.installments', [
            'plans' => $plans,
        ]);
    }

    // --- Utility: Serve files from public storage (helps on Windows/XAMPP when symlink is problematic)
    public function servePublic(string $path)
    {
        $path = ltrim($path, '/');
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        if (!$disk->exists($path)) {
            abort(404);
        }
        $absolute = storage_path('app/public/' . $path);
        $mime = \Illuminate\Support\Facades\File::mimeType($absolute) ?: 'application/octet-stream';
        return response()->file($absolute, [ 'Content-Type' => $mime ]);
    }

    // Patient Management
    public function patients()
    {
        $patients = Patient::with('creator')
            ->where('is_patient_page_deleted', false)
            ->paginate(10);
        return view('admin.patients.index', compact('patients'));
    }

    // ===== Shortlist (last 15 days) =====
    public function shortlistPatients()
    {
        $since = Carbon::now()->subDays(15);
        $patients = Patient::where('created_at', '>=', $since)
            ->where('is_patient_page_deleted', false)
            ->orderByDesc('created_at')
            ->get(['Predict3DId','FullName','PhoneNumber','Gender','DateOfBirth','DoctorName','doctor_email','created_at']);
        return view('admin.patients.shortlist', [
            'type' => 'patients',
            'since' => $since,
            'patients' => $patients,
            'doctors' => collect(),
        ]);
    }

    public function shortlistDoctors()
    {
        $since = Carbon::now()->subDays(15);
        $doctors = Patient::where('created_at', '>=', $since)
            ->where('is_patient_page_deleted', false)
            ->orderByDesc('created_at')
            ->get(['DoctorName','doctor_email','created_at'])
            ->unique(function($row){ return $row->DoctorName.'|'.$row->doctor_email; })
            ->values();
        return view('admin.patients.shortlist', [
            'type' => 'doctors',
            'since' => $since,
            'patients' => collect(),
            'doctors' => $doctors,
        ]);
    }

    // ===== Exports =====
    public function exportPatientsAll()
    {
        $data = Patient::where('is_patient_page_deleted', false)->orderBy('created_at', 'desc')->get();
        $fileName = 'patients_all_'.now()->format('Ymd_His').'.xlsx';
        return Excel::download(new PatientsExport($data, 'All Patients'), $fileName);
    }

    public function exportPatientsLastMonth()
    {
        $since = Carbon::now()->subDays(30);
        $data = Patient::where('created_at', '>=', $since)
            ->where('is_patient_page_deleted', false)
            ->orderBy('created_at', 'desc')
            ->get();
        $fileName = 'patients_last_month_'.now()->format('Ymd_His').'.xlsx';
        return Excel::download(new PatientsExport($data, 'Last 30 Days Patients'), $fileName);
    }

    public function exportPatientsRange(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|date',
            'to' => 'required|date',
        ]);

        $from = Carbon::parse($validated['from'])->startOfDay();
        $to = Carbon::parse($validated['to'])->endOfDay();
        if ($from->gt($to)) { [$from, $to] = [$to, $from]; }

        $data = Patient::whereBetween('created_at', [$from, $to])
            ->where('is_patient_page_deleted', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $label = 'Patients from '.$from->format('Y-m-d').' to '.$to->format('Y-m-d');
        $fileName = 'patients_'.$from->format('Ymd').'_to_'.$to->format('Ymd').'_'.now()->format('His').'.xlsx';
        return Excel::download(new PatientsExport($data, $label), $fileName);
    }

    public function createPatient()
    {
        $regions = Region::with(['areas.territories.doctors'])->orderBy('name')->get();

        $regionsTree = $regions->map(function ($region) {
            return [
                'id' => $region->id,
                'name' => $region->name,
                'areas' => $region->areas->map(function ($area) {
                    return [
                        'id' => $area->id,
                        'name' => $area->name,
                        'territories' => $area->territories->map(function ($territory) {
                            return [
                                'id' => $territory->id,
                                'name' => $territory->name,
                                'doctors' => $territory->doctors->map(function ($doctor) {
                                    return [
                                        'id' => $doctor->id,
                                        'name' => $doctor->name,
                                        'email' => $doctor->email,
                                        'chamber_address' => $doctor->chamber_address,
                                        'mr_name' => $doctor->marketing_representative_name,
                                    ];
                                })->values()->all(),
                            ];
                        })->values()->all(),
                    ];
                })->values()->all(),
            ];
        })->values()->all();

        return view('admin.patients.create', compact('regionsTree'));
    }

    public function storePatient(Request $request)
    {
        $validated = $request->validate([
            'Predict3DId' => 'required|string|max:255|unique:patients,Predict3DId',
            'FullName' => 'required|string|max:255',
            'ScanningFor' => 'required|in:Aligner,Zirconia,Others',
            'ScanningForOthers' => 'required_if:ScanningFor,Others|nullable|string|max:255',
            'case_type' => 'required|in:Deep CBCD,Full Case,Short Case,Single ARC,Retainer',
            'region_id' => 'required|exists:regions,id',
            'area_id' => 'required|exists:areas,id',
            'territory_id' => 'required|exists:territories,id',
            'doctor_id' => 'required|exists:doctors,id',
            'PhoneNumber' => 'required|string|max:13',
            'EmergencyContact' => 'required|string|max:13',
            'Gender' => 'required|in:Male,Female,Custom',
            'DateOfBirth' => 'required|date',
            'Address' => 'required|string',
            'UpperCases' => 'required|integer|min:0',
            'LowerCases' => 'required|integer|min:0',
        ]);

        $area = Area::where('id', $validated['area_id'])
            ->where('region_id', $validated['region_id'])
            ->firstOrFail();

        $territory = Territory::where('id', $validated['territory_id'])
            ->where('area_id', $area->id)
            ->firstOrFail();

        $doctor = Doctor::with('territory.area.region')
            ->where('id', $validated['doctor_id'])
            ->where('territory_id', $territory->id)
            ->firstOrFail();

        Patient::create([
            'Predict3DId' => $validated['Predict3DId'],
            'FullName' => $validated['FullName'],
            'ScanningFor' => $validated['ScanningFor'],
            'ScanningForOthers' => $validated['ScanningForOthers'] ?? null,
            'case_type' => $validated['case_type'],
            'DoctorName' => $doctor->name,
            'doctor_email' => $doctor->email,
            'ChamberName' => $doctor->chamber_address ?: '-',
            'TerritoryName' => $territory->name,
            'RegionalName' => $doctor->territory?->area?->region?->name ?? '-',
            'PhoneNumber' => $validated['PhoneNumber'],
            'EmergencyContact' => $validated['EmergencyContact'],
            'Gender' => $validated['Gender'],
            'DateOfBirth' => $validated['DateOfBirth'],
            'Address' => $validated['Address'],
            'UpperCases' => $validated['UpperCases'],
            'LowerCases' => $validated['LowerCases'],
            'created_by' => auth()->id(),
        ]);

         return redirect()->route(
            'admin.payments.plan.index',
            [
                'predict3d_id' => $validated['Predict3DId']
            ]
            )->with('success', 'Patient added successfully.');
    }

    public function showPatient(Patient $patient)
    {
        $patient->load('creator', 'payments');
        return view('admin.patients.show', compact('patient'));
    }

    public function editPatient(Patient $patient)
    {
        return view('admin.patients.edit', compact('patient'));
    }

    public function updatePatient(Request $request, Patient $patient)
    {
        // Log the update attempt with detailed information
        \Log::info('=== UPDATE PATIENT START ===');
        \Log::info('Patient ID from route: ' . $patient->id);
        \Log::info('Patient Predict3DId: ' . $patient->Predict3DId);
        \Log::info('Request method: ' . $request->method());
        \Log::info('Request URL: ' . $request->fullUrl());
        \Log::info('Request data:', $request->all());
        \Log::info('Current patient data:', $patient->toArray());

        // Validate the request data
        try {
            $validated = $request->validate([
            'Predict3DId' => 'required|string|max:255|unique:patients,Predict3DId,' . $patient->Predict3DId . ',Predict3DId',
            'FullName' => 'required|string|max:255',
            'ScanningFor' => 'required|in:Aligner,Zirconia,Others',
            'ScanningForOthers' => 'required_if:ScanningFor,Others|nullable|string|max:255',
            'case_type' => 'required|in:Deep CBCD,Full Case,Short Case,Single ARC,Retainer',
            'DoctorName' => 'required|string|max:255',
            'doctor_email' => 'nullable|email',
            'ChamberName' => 'required|string|max:255',
            'TerritoryName' => 'required|string|max:255',
            'RegionalName' => 'required|string|max:255',
            'PhoneNumber' => 'required|string|max:13',
            'EmergencyContact' => 'required|string|max:13',
            'Gender' => 'required|in:Male,Female,Custom',
            'DateOfBirth' => 'required|date',
            'Address' => 'required|string',
            'UpperCases' => 'nullable|integer|min:0',
            'LowerCases' => 'nullable|integer|min:0',
        ]);

            \Log::info('Validation passed', ['validated_data' => $validated]);

            $updateData = [
                'Predict3DId' => $request->Predict3DId,
                'FullName' => $request->FullName,
                'ScanningFor' => $request->ScanningFor,
                'ScanningForOthers' => $request->ScanningForOthers,
                'case_type' => $request->case_type,
                'DoctorName' => $request->DoctorName,
                'doctor_email' => $request->doctor_email,
                'ChamberName' => $request->ChamberName,
                'TerritoryName' => $request->TerritoryName,
                'RegionalName' => $request->RegionalName,
                'PhoneNumber' => $request->PhoneNumber,
                'EmergencyContact' => $request->EmergencyContact,
                'Gender' => $request->Gender,
                'DateOfBirth' => $request->DateOfBirth,
                'Address' => $request->Address,
                'UpperCases' => $request->UpperCases,
                'LowerCases' => $request->LowerCases,
            ];

            \Log::info('Attempting to update patient with data:', $updateData);
            
            // Try updating using save() instead of update() for better error reporting
            foreach ($updateData as $key => $value) {
                $patient->$key = $value;
            }
            
            $saved = $patient->save();
            
            if ($saved) {
                $updatedPatient = $patient->fresh();
                \Log::info('Patient updated successfully', $updatedPatient->toArray());
                return redirect()->route('admin.patients.show', $patient)
                    ->with('success', 'Patient updated successfully.');
            } else {
                $error = 'Failed to save patient record. No database error but save() returned false.';
                \Log::error($error);
                throw new \Exception($error);
            }
        } catch (\Illuminate\Validation\ValidationException $ve) {
            // Log validation errors
            \Log::error('Validation failed', [
                'errors' => $ve->errors(),
                'input' => $request->all()
            ]);
            throw $ve; // Re-throw to let Laravel handle the validation response
            
        } catch (\Exception $e) {
            // Log detailed error information
            $errorMessage = 'Error updating patient: ' . $e->getMessage();
            \Log::error($errorMessage, [
                'exception' => get_class($e),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'patient_id' => $patient->id ?? 'unknown',
                'predict3d_id' => $patient->Predict3DId ?? 'unknown'
            ]);
            
            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Failed to update patient. Please try again or contact support if the problem persists.',
                    'details' => $e->getMessage()
                ]);
        }
    }

    public function deletePatient(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'delete_mode' => 'required|in:full_delete,hide_only',
        ]);

        if ($data['delete_mode'] === 'hide_only') {
            $patient->is_patient_page_deleted = true;
            $patient->save();

            return redirect()->route('admin.patients.index')
                ->with('success', 'Patient removed from Patient page. Payment history/details are kept in Payment Management.');
        }

        DB::transaction(function () use ($patient) {
            // Delete classic payments tied directly to patient
            Payment::where('patient_id', $patient->Predict3DId)->delete();

            // Delete payment-plan module data
            $planIds = PaymentPlan::where('predict3d_id', $patient->Predict3DId)->pluck('id');
            if ($planIds->isNotEmpty()) {
                PaymentPlanDelivery::whereIn('payment_plan_id', $planIds)->delete();
                PaymentPlanPayment::whereIn('payment_plan_id', $planIds)->delete();
                PaymentPlan::whereIn('id', $planIds)->delete();
            }

            // Finally delete patient
            $patient->delete();
        });

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient and all payment history/details deleted successfully.');
    }

    // Doctor Management
    public function doctors(Request $request)
    {
        $regionId = $request->input('region_id');
        $areaId = $request->input('area_id');
        $territoryId = $request->input('territory_id');
        $filterMrName = $request->input('mr_name');

        if ($regionId && $areaId) {
            $area = Area::find($areaId);
            if (! $area || (string) $area->region_id !== (string) $regionId) {
                $areaId = null;
                $territoryId = null;
            }
        }
        if ($areaId && $territoryId) {
            $t = Territory::find($territoryId);
            if (! $t || (string) $t->area_id !== (string) $areaId) {
                $territoryId = null;
            }
        }

        $query = Doctor::with(['creator', 'territory.area.region']);

        if ($territoryId) {
            $query->where('territory_id', $territoryId);
        } elseif ($areaId) {
            $query->whereHas('territory', fn ($q) => $q->where('area_id', $areaId));
        } elseif ($regionId) {
            $query->whereHas('territory.area', fn ($q) => $q->where('region_id', $regionId));
        }

        if ($filterMrName) {
            $query->where('marketing_representative_name', $filterMrName);
        }

        $doctors = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        $regions = Region::orderBy('name')->get();
        $areas = $regionId
            ? Area::where('region_id', $regionId)->orderBy('name')->get()
            : collect();
        $territories = $areaId
            ? Territory::where('area_id', $areaId)->orderBy('name')->get()
            : collect();

        return view('admin.doctors.index', [
            'doctors' => $doctors,
            'regions' => $regions,
            'areas' => $areas,
            'territories' => $territories,
            'filterRegionId' => $regionId,
            'filterAreaId' => $areaId,
            'filterTerritoryId' => $territoryId,
            'filterMrName' => $filterMrName,
            'mrOptions' => $this->marketingRepOptions(),
        ]);
    }

    public function doctorAreasJson(Request $request)
    {
        $request->validate(['region_id' => 'required|exists:regions,id']);

        return response()->json(
            Area::where('region_id', $request->region_id)->orderBy('name')->get(['id', 'name'])
        );
    }

    public function doctorTerritoriesJson(Request $request)
    {
        $request->validate(['area_id' => 'required|exists:areas,id']);

        return response()->json(
            Territory::where('area_id', $request->area_id)->orderBy('name')->get(['id', 'name'])
        );
    }

    public function doctorsShortlist(int $days)
    {
        $days = in_array($days, [15, 30], true) ? $days : 15;
        $since = Carbon::now()->subDays($days);
        $doctors = Doctor::with(['creator', 'territory.area.region'])
            ->where('created_at', '>=', $since)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.doctors.shortlist', [
            'since' => $since,
            'days' => $days,
            'doctors' => $doctors,
        ]);
    }

    public function exportDoctorsAll()
    {
        $data = Doctor::with(['creator', 'territory.area.region'])->orderByDesc('created_at')->get();
        $fileName = 'doctors_all_'.now()->format('Ymd_His').'.xlsx';

        return Excel::download(new DoctorsExport($data, 'All Doctors'), $fileName);
    }

    public function createDoctor()
    {
        $regions = Region::with(['areas.territories'])->orderBy('name')->get();
        $mrOptions = $this->marketingRepOptions();
        $regionsTree = $this->buildDoctorRegionsTree($regions);

        return view('admin.doctors.create', compact('regions', 'mrOptions', 'regionsTree'));
    }

    public function storeDoctor(Request $request)
    {
        $mrOptions = $this->marketingRepOptions();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'chamber_name' => 'required|string|max:255',
            'chamber_address' => 'required|string',
            'chamber_map_link' => 'nullable|url|max:2048',
            'mobile_number' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'note' => 'nullable|string',
            'marketing_representative_name' => ['required', \Illuminate\Validation\Rule::in($mrOptions)],
            'region_name' => 'required|string|max:255',
            'area_name' => 'required|string|max:255',
            'territory_name' => 'required|string|max:255',
        ]);

        $territory = $this->resolveTerritoryFromNames(
            $validated['region_name'],
            $validated['area_name'],
            $validated['territory_name']
        );

        Doctor::create([
            'name' => $validated['name'],
            'chamber_name' => $validated['chamber_name'],
            'chamber_address' => $validated['chamber_address'],
            'chamber_map_link' => $validated['chamber_map_link'] ?? null,
            'mobile_number' => $validated['mobile_number'],
            'email' => $validated['email'] ?? null,
            'note' => $validated['note'] ?? null,
            'marketing_representative_name' => $validated['marketing_representative_name'],
            'mr_assigned_at' => now(),
            'territory_id' => $territory->id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor added successfully.');
    }

    public function showDoctor(Doctor $doctor)
    {
        $doctor->load(['creator', 'territory.area.region']);

        return view('admin.doctors.show', compact('doctor'));
    }

    public function editDoctor(Doctor $doctor)
    {
        $doctor->load('territory.area.region');
        $regionName = $doctor->territory?->area?->region?->name ?? '';
        $areaName = $doctor->territory?->area?->name ?? '';
        $territoryName = $doctor->territory?->name ?? '';
        $regions = Region::with(['areas.territories'])->orderBy('name')->get();
        $mrOptions = $this->marketingRepOptions();
        $regionsTree = $this->buildDoctorRegionsTree($regions);

        return view('admin.doctors.edit', compact('doctor', 'regionName', 'areaName', 'territoryName', 'regions', 'mrOptions', 'regionsTree'));
    }

    public function updateDoctor(Request $request, Doctor $doctor)
    {
        $mrOptions = $this->marketingRepOptions();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'chamber_name' => 'required|string|max:255',
            'chamber_address' => 'required|string',
            'chamber_map_link' => 'nullable|url|max:2048',
            'mobile_number' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'note' => 'nullable|string',
            'marketing_representative_name' => ['required', \Illuminate\Validation\Rule::in($mrOptions)],
            'region_name' => 'required|string|max:255',
            'area_name' => 'required|string|max:255',
            'territory_name' => 'required|string|max:255',
        ]);

        $territory = $this->resolveTerritoryFromNames(
            $validated['region_name'],
            $validated['area_name'],
            $validated['territory_name']
        );

        $updateData = [
            'name' => $validated['name'],
            'chamber_name' => $validated['chamber_name'],
            'chamber_address' => $validated['chamber_address'],
            'chamber_map_link' => $validated['chamber_map_link'] ?? null,
            'mobile_number' => $validated['mobile_number'],
            'email' => $validated['email'] ?? null,
            'note' => $validated['note'] ?? null,
            'marketing_representative_name' => $validated['marketing_representative_name'],
            'territory_id' => $territory->id,
        ];

        if ($doctor->marketing_representative_name !== $validated['marketing_representative_name']) {
            $updateData['mr_assigned_at'] = now();
        }

        $doctor->update($updateData);

        return redirect()->route('admin.doctors.show', $doctor)
            ->with('success', 'Doctor updated successfully.');
    }

    public function deleteDoctor(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }

    // Marketing Representatives Management
    public function mrs()
    {
        $mrs = MarketingRepresentative::orderBy('name')->get();
        return view('admin.mrs.index', compact('mrs'));
    }

    public function createMr()
    {
        return view('admin.mrs.create');
    }

    public function storeMr(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:marketing_representatives,email|max:255',
            'phone' => 'nullable|string|max:20',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
        ]);

        MarketingRepresentative::create($validated);

        return redirect()->route('admin.mrs.index')
            ->with('success', 'Marketing Representative added successfully.');
    }

    public function showMr(MarketingRepresentative $mr)
    {
        return view('admin.mrs.show', compact('mr'));
    }

    public function editMr(MarketingRepresentative $mr)
    {
        return view('admin.mrs.edit', compact('mr'));
    }

    public function updateMr(Request $request, MarketingRepresentative $mr)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:marketing_representatives,email,'.$mr->id,
            'phone' => 'nullable|string|max:20',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
        ]);

        $mr->update($validated);

        return redirect()->route('admin.mrs.index')
            ->with('success', 'Marketing Representative updated successfully.');
    }

    public function deleteMr(MarketingRepresentative $mr)
    {
        $mr->delete();

        return redirect()->route('admin.mrs.index')
            ->with('success', 'Marketing Representative deleted successfully.');
    }

    protected function resolveTerritoryFromNames(string $regionName, string $areaName, string $territoryName): Territory
    {
        $regionName = trim($regionName);
        $areaName = trim($areaName);
        $territoryName = trim($territoryName);

        $region = Region::firstOrCreate(['name' => $regionName]);
        $area = Area::firstOrCreate(
            ['region_id' => $region->id, 'name' => $areaName]
        );

        return Territory::firstOrCreate(
            ['area_id' => $area->id, 'name' => $territoryName]
        );
    }

    protected function marketingRepOptions(): array
    {
        return MarketingRepresentative::orderBy('name')->pluck('name')->toArray();
    }

    protected function buildDoctorRegionsTree($regions): array
    {
        return $regions->map(function ($region) {
            return [
                'name' => $region->name,
                'areas' => $region->areas->map(function ($area) {
                    return [
                        'name' => $area->name,
                        'territories' => $area->territories->pluck('name')->values()->all(),
                    ];
                })->values()->all(),
            ];
        })->values()->all();
    }

    // Inventory Management
    public function inventory()
    {
        $products = Product::with('creator', 'requester')->paginate(12);
        $pendingRequests = ProductRequest::with('requester')->where('status', 'pending')->get();
        return view('admin.inventory.index', compact('products', 'pendingRequests'));
    }

    public function createProduct()
    {
        return view('admin.inventory.create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'image' => $imagePath,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.inventory.index')
                        ->with('success', 'Product added successfully.');
    }

    public function showProduct(Product $product)
    {
        return view('admin.inventory.show', compact('product'));
    }

    public function editProduct(Product $product)
    {
        return view('admin.inventory.edit', compact('product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.inventory.index')
                        ->with('success', 'Product updated successfully.');
    }

    public function deleteProduct(Product $product)
    {
        // Delete image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.inventory.index')
                        ->with('success', 'Product deleted successfully.');
    }

    public function approveProductRequest(ProductRequest $productRequest)
    {
        try {
            // Lookup requester safely (may be null if bad data)
            $requester = User::find($productRequest->requested_by);
            $requesterName = $requester ? $requester->name : 'Unknown';

            // Create product from request
            $imagePath = $productRequest->image;

            Product::create([
                'name' => $productRequest->name,
                'price' => $productRequest->price,
                'quantity' => $productRequest->quantity,
                'description' => trim(($productRequest->description ?? '') . "\n\n[Requested by: {$requesterName} - Lab Technician]"),
                'image' => $imagePath,
                'status' => 'active',
                'created_by' => auth()->id(),
                'requested_by' => $productRequest->requested_by,
            ]);

            // Mark request as approved
            $productRequest->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            return redirect()->route('admin.inventory.index')
                ->with('success', 'Product request approved and added to inventory. Lab Technician: ' . $requesterName);
        } catch (\Exception $e) {
            return redirect()->route('admin.inventory.index')
                ->with('error', 'Error approving request: ' . $e->getMessage());
        }
    }

    public function rejectProductRequest(Request $requestData, ProductRequest $request)
    {
        $requestData->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $request->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => $requestData->rejection_reason,
        ]);

        return redirect()->route('admin.inventory.index')
                        ->with('success', 'Product request rejected.');
    }

    // Payment Management
    public function updateDaysPerAligner(Request $request)
    {
        $request->validate(['days' => 'required|integer|min:1']);
        \Cache::forever('days_per_aligner', (int) $request->days);
        return back()->with('success', 'Days per aligner updated. All reminders recalculated.');
    }

    protected function buildPaymentsQuery(Request $request)
    {
        $query = PaymentPlan::query()
            ->join('patients as pat', 'pat.Predict3DId', '=', 'payment_plans.predict3d_id')
            ->leftJoin('doctors as d', function($join) {
                $join->on('pat.DoctorName', '=', 'd.name')
                     ->whereRaw("pat.ChamberName = COALESCE(NULLIF(d.chamber_address, ''), '-')");
            });

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = Carbon::parse($request->from_date)->startOfDay();
            $to = Carbon::parse($request->to_date)->endOfDay();
            if ($from->gt($to)) { [$from, $to] = [$to, $from]; }
            $query->whereBetween('pat.created_at', [$from, $to]);
        }

        $regionId = $request->get('region_id');
        $areaId = $request->get('area_id');
        $territoryId = $request->get('territory_id');
        if ($territoryId) {
            $query->where('d.territory_id', $territoryId);
        } elseif ($areaId || $regionId) {
            $query->leftJoin('territories as t', 't.id', '=', 'd.territory_id');
            if ($areaId) {
                $query->where('t.area_id', $areaId);
            } elseif ($regionId) {
                $query->leftJoin('areas as a', 'a.id', '=', 't.area_id')
                      ->where('a.region_id', $regionId);
            }
        }

        if ($request->filled('doctor_name')) {
            $query->where('pat.DoctorName', $request->doctor_name);
        }

        if ($request->filled('mr_name')) {
            $query->where('d.marketing_representative_name', $request->mr_name);
        }

        $status = $request->get('status', 'All');
        if ($status === 'Active') {
            $query->where('payment_plans.is_closed', false);
        } elseif ($status === 'Completed') {
            $query->where('payment_plans.is_closed', true);
        }

        return $query;
    }

    public function payments(Request $request)
    {
        $query = $this->buildPaymentsQuery($request);

        // One row per payment plan (latest payment + latest delivery reminder)
        $payments = $query
            ->orderByDesc('payment_plans.updated_at')
            ->select([
                'payment_plans.id as plan_id',
                'payment_plans.predict3d_id as predict3d_id',
                'payment_plans.total_amount as total_amount',
                'payment_plans.remaining_amount as remaining_amount',
                'payment_plans.is_installment as is_installment',
                'payment_plans.next_payment_date as next_payment_date',
                'payment_plans.payment_method as plan_payment_method',
                'payment_plans.is_closed as is_closed',
                'pat.FullName as patient_full_name',
                'pat.PhoneNumber as patient_phone',
                'pat.status as patient_status',
                'pat.UpperCases as total_upper_cases',
                'pat.LowerCases as total_lower_cases',
                DB::raw('(select COALESCE(sum(p2.amount),0) from payment_plan_payments p2 where p2.payment_plan_id = payment_plans.id) as total_paid'),
                DB::raw('(select p3.payment_date from payment_plan_payments p3 where p3.payment_plan_id = payment_plans.id order by p3.payment_date desc, p3.id desc limit 1) as latest_payment_date'),
                DB::raw('(select p4.payment_method from payment_plan_payments p4 where p4.payment_plan_id = payment_plans.id order by p4.payment_date desc, p4.id desc limit 1) as latest_payment_method'),
                DB::raw('(select d1.delivery_date from payment_plan_deliveries d1 where d1.payment_plan_id = payment_plans.id order by d1.delivery_date desc, d1.id desc limit 1) as latest_delivery_date'),
                DB::raw('(select d2.upper_delivered from payment_plan_deliveries d2 where d2.payment_plan_id = payment_plans.id order by d2.delivery_date desc, d2.id desc limit 1) as latest_upper_delivered'),
                DB::raw('(select d3.lower_delivered from payment_plan_deliveries d3 where d3.payment_plan_id = payment_plans.id order by d3.delivery_date desc, d3.id desc limit 1) as latest_lower_delivered'),
                DB::raw('(select d4.paid_amount from payment_plan_deliveries d4 where d4.payment_plan_id = payment_plans.id order by d4.delivery_date desc, d4.id desc limit 1) as latest_delivery_paid_amount'),
                DB::raw('(select COALESCE(sum(d.upper_delivered),0) from payment_plan_deliveries d where d.payment_plan_id = payment_plans.id) as total_upper_delivered'),
                DB::raw('(select COALESCE(sum(d.lower_delivered),0) from payment_plan_deliveries d where d.payment_plan_id = payment_plans.id) as total_lower_delivered'),
                ])
            ->paginate(10)->withQueryString();

        $payments->getCollection()->transform(function ($row) {

            $row->payment_date = $row->latest_payment_date;
            $row->payment_method = $row->latest_payment_method ?: $row->plan_payment_method;

            /*
            |--------------------------------------------------------------------------
            | Reminder
            |--------------------------------------------------------------------------
            */
            $row->reminder_level = 'normal';
            $row->reminder_text = 'No delivery recorded yet';
            $row->row_class = '';

            if ((bool) ($row->is_closed ?? false)) {
                $row->reminder_level = 'closed';
                $row->reminder_text = 'Case closed';
                $row->row_class = 'table-reminder-closed';
            } elseif (!empty($row->latest_delivery_date)) {

                $lastDeliveryDate = Carbon::parse($row->latest_delivery_date)->startOfDay();

                $upper = (int) ($row->latest_upper_delivered ?? 0);
                $lower = (int) ($row->latest_lower_delivered ?? 0);

                $maxCases = max($upper, $lower);

                $daysPerAligner = \Cache::get('days_per_aligner', 15);

                $cycleDays = $maxCases * $daysPerAligner;

                $nextDue = $lastDeliveryDate->copy()->addDays($cycleDays);

                $daysLeft = Carbon::today()->diffInDays($nextDue, false);

                $row->next_delivery_due_date = $nextDue->toDateString();
                $row->next_delivery_days_left = $daysLeft;

                $latestDeliveryPaid = (float) ($row->latest_delivery_paid_amount ?? 0);

                if ($latestDeliveryPaid <= 0 && $maxCases > 0) {

                    $row->reminder_level = 'critical_unpaid';
                    $row->row_class = 'table-reminder-critical';
                    $row->reminder_text = 'Unpaid delivery - immediate follow-up required';

                } elseif ($daysLeft <= 7) {

                    $row->reminder_level = 'critical';
                    $row->row_class = 'table-reminder-red';
                    $row->reminder_text = $daysLeft < 0
                        ? 'Delivery overdue by '.abs($daysLeft).' day(s)'
                        : 'Delivery due in '.$daysLeft.' day(s)';

                } elseif ($daysLeft <= 15) {

                    $row->reminder_level = 'warning';
                    $row->row_class = 'table-reminder-yellow';
                    $row->reminder_text = 'Prepare next delivery in '.$daysLeft.' day(s)';

                } else {

                    $row->reminder_level = 'normal';
                    $row->reminder_text = 'Next delivery due on '.$nextDue->format('M d, Y');

                }
            }

            /*
            |--------------------------------------------------------------------------
            | Case Status
            |--------------------------------------------------------------------------
            */

            $row->case_status_level = 'success';
            $row->case_status_text = 'Case Start';

            $totalCases =
                (int)$row->total_upper_cases +
                (int)$row->total_lower_cases;

            $totalDelivered =
                (int)$row->total_upper_delivered +
                (int)$row->total_lower_delivered;

            $totalPaid = (float)$row->total_paid;

            $pricePerAligner = $totalCases > 0
                ? ((float)$row->total_amount / $totalCases)
                : 0;

            $expectedPayment = $totalDelivered * $pricePerAligner;

            if ($row->patient_status === 'inactive') {

                if ((float)$row->remaining_amount <= 0) {

                    $row->case_status_level = 'closed';
                    $row->case_status_text = 'Case Completed';

                } else {

                    $row->case_status_level = 'critical';
                    $row->case_status_text = 'Delivery Completed - Payment Due';

                }

            } else {

                if ($totalDelivered == 0) {

                    $row->case_status_level = 'success';
                    $row->case_status_text = 'Payment Start';

                } elseif (abs($expectedPayment - $totalPaid) < 0.01) {

                    $row->case_status_level = 'warning';
                    $row->case_status_text = 'Payment & Delivery Ratio Equal';

                } elseif ($totalPaid < $expectedPayment) {

                    $row->case_status_level = 'critical';
                    $row->case_status_text = 'Payment Due';

                } else {

                    $row->case_status_level = 'success';
                    $row->case_status_text = 'Payment Start';

                }
            }

            return $row;
        });

        $totalThisMonth = \App\Models\PaymentPlanPayment::whereDate('payment_date', '>=', now()->startOfMonth())->sum('amount');
        $totalToday = \App\Models\PaymentPlanPayment::whereDate('payment_date', today())->sum('amount');
        $grandTotal = clone $query;
        // The grandTotal shouldn't double count if joined, but wait, the join is on doctors and patients.
        // It's just plans. We can sum remaining.
        // Let's keep grandTotal logic as is or we can sum total_amount.
        $grandTotalAmount = \App\Models\PaymentPlanPayment::sum('amount');

        $regions = Region::orderBy('name')->get();
        $areas = $request->region_id ? Area::where('region_id', $request->region_id)->orderBy('name')->get() : collect();
        $territories = $request->area_id ? Territory::where('area_id', $request->area_id)->orderBy('name')->get() : collect();
        $doctors = \App\Models\Doctor::select('name')->distinct()->orderBy('name')->pluck('name');
        $mrOptions = $this->marketingRepOptions();

        return view('admin.payments.index', [
            'payments' => $payments,
            'totalThisMonth' => $totalThisMonth,
            'totalToday' => $totalToday,
            'grandTotal' => $grandTotalAmount,
            'regions' => $regions,
            'areas' => $areas,
            'territories' => $territories,
            'doctorOptions' => $doctors,
            'mrOptions' => $mrOptions,
            'filterRegionId' => $request->region_id,
            'filterAreaId' => $request->area_id,
            'filterTerritoryId' => $request->territory_id,
            'filterDoctorName' => $request->doctor_name,
            'filterMrName' => $request->mr_name,
            'filterStatus' => $request->status ?? 'All',
            'filterFromDate' => $request->from_date,
            'filterToDate' => $request->to_date,
        ]);
    }

    public function createPayment()
    {
        $patients = Patient::where('status', 'active')->get();
        return view('admin.payments.create', compact('patients'));
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            // patients now use Predict3DId (string) as primary key
            'patient_id' => 'required|exists:patients,Predict3DId',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank_transfer,mobile_banking,check',
            'payment_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Payment::create([
            'patient_id' => $request->patient_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'description' => $request->description,
            'processed_by' => auth()->id(),
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Payment recorded successfully!');
    }

    // Lab Technician Management
    public function labTechnicians()
    {
        $technicians = User::where('role', 'lab_technician')->paginate(10);
        return view('admin.lab-technicians.index', compact('technicians'));
    }

    public function createLabTechnician()
    {
        return view('admin.lab-technicians.create');
    }

    public function storeLabTechnician(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'lab_technician',
            'status' => 'active',
        ]);

        // Flash credentials so admin can see them once
        return redirect()->route('admin.lab-technicians.index')->with([
            'success' => 'Lab Technician created successfully!',
            'labtech_created' => [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'password' => $request->password, // show once; we do NOT store plaintext
            ],
        ]);
    }

    public function editLabTechnician(User $user)
    {
        if ($user->role !== 'lab_technician') {
            abort(404);
        }
        return view('admin.lab-technicians.edit', compact('user'));
    }

    public function updateLabTechnician(Request $request, User $user)
    {
        if ($user->role !== 'lab_technician') {
            abort(404);
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:active,inactive',
        ]);

        $update = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'status' => $data['status'],
        ];
        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }
        $user->update($update);

        return redirect()->route('admin.lab-technicians.index')->with('success', 'Lab Technician updated successfully!');
    }

    public function deleteLabTechnician(User $user)
    {
        if ($user->role !== 'lab_technician') {
            abort(404);
        }
        try {
            $user->delete();
            return redirect()->route('admin.lab-technicians.index')->with('success', 'Lab Technician deleted successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Likely foreign key constraint (e.g., product_requests.requested_by)
            return redirect()->route('admin.lab-technicians.index')
                ->with('error', 'Cannot delete this Lab Technician because there are linked records (e.g., product requests). Reassign or remove those records first.');
        }
    }

    // Data Entry Portal
    public function dataEntry()
    {
        return view('lab.data-entry');
    }

    // ===================== Payments: Predict3DId-based Plan =====================
    public function paymentPlanIndex()
    {
        return view('admin.payments.plan');
    }

    public function getPaymentPlan(string $predict3dId)
    {
        $patient = Patient::where('Predict3DId', $predict3dId)->first();
        $plan = PaymentPlan::where('predict3d_id', $predict3dId)->first();
        $payments = [];
        $paid = 0;
        $deliveries = [];
        $deliveredUpper = 0;
        $deliveredLower = 0;
        if ($plan) {
            $payments = PaymentPlanPayment::where('payment_plan_id', $plan->id)
                ->orderBy('payment_date', 'asc')
                ->get();
            $paid = (float) $payments->sum('amount');

            $deliveries = PaymentPlanDelivery::where('payment_plan_id', $plan->id)
                ->orderBy('delivery_date', 'asc')
                ->get();
            $deliveredUpper = (int) $deliveries->sum('upper_delivered');
            $deliveredLower = (int) $deliveries->sum('lower_delivered');
        }

        $totalUpper = (int) ($patient?->UpperCases ?? 0);
        $totalLower = (int) ($patient?->LowerCases ?? 0);
        $remainingUpper = max(0, $totalUpper - $deliveredUpper);
        $remainingLower = max(0, $totalLower - $deliveredLower);

        return response()->json([
            'plan' => $plan,
            'payments' => $payments,
            'paid' => $paid,
            'remaining' => $plan ? (float) $plan->total_amount - $paid : null,
            'deliveries' => $deliveries,
            'cases' => [
                'total_upper' => $totalUpper,
                'total_lower' => $totalLower,
                'delivered_upper' => $deliveredUpper,
                'delivered_lower' => $deliveredLower,
                'remaining_upper' => $remainingUpper,
                'remaining_lower' => $remainingLower,
            ],
        ]);
    }

    public function addPaymentPlanDelivery(Request $request, string $predict3dId)
    {
        $data = $request->validate([
            'upper_delivered' => 'required|integer|min:0',
            'lower_delivered' => 'required|integer|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'delivery_date' => 'required|date',

            'payment_method' => 'required|in:cash,card,bank_transfer,mobile_banking',

            'total_amount' => 'nullable|numeric|min:0',

            'bank_name' => 'nullable|string|max:100',
            'branch_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:100',

            'mobile_provider' => 'nullable|string|max:100',
            'transaction_id' => 'nullable|string|max:100',
        ]);

        $patient = Patient::where('Predict3DId', $predict3dId)->first();
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        $plan = PaymentPlan::firstOrNew(['predict3d_id' => $predict3dId]);
        if (!$plan->exists) {
            $plan->predict3d_id = $predict3dId;
            $plan->total_amount = (float) ($data['total_amount'] ?? 0);
            $plan->payment_method = $data['payment_method'];
            $plan->is_installment = true;
            $plan->next_payment_date = null;
            $plan->remaining_amount = (float) ($data['total_amount'] ?? 0);
            $plan->is_closed = false;
            $plan->created_by = auth()->id();
            $plan->save();
        } elseif (array_key_exists('total_amount', $data) && $data['total_amount'] !== null) {
            if ((bool) $plan->is_closed) {
                return response()->json(['message' => 'This case is closed. No further delivery can be added.'], 422);
            }
            // Respect admin-entered total amount from UI when saving a delivery.
            $enteredTotal = (float) $data['total_amount'];
            $plan->total_amount = $enteredTotal;
        }

        if ((bool) $plan->is_closed) {
            return response()->json(['message' => 'This case is closed. No further delivery can be added.'], 422);
        }

        $existingDeliveries = PaymentPlanDelivery::where('payment_plan_id', $plan->id)->get();
        $deliveredUpper = (int) $existingDeliveries->sum('upper_delivered');
        $deliveredLower = (int) $existingDeliveries->sum('lower_delivered');
        $totalUpper = (int) ($patient->UpperCases ?? 0);
        $totalLower = (int) ($patient->LowerCases ?? 0);

        $newUpper = (int) $data['upper_delivered'];
        $newLower = (int) $data['lower_delivered'];

        // Prevent completely empty delivery records.
        // A delivery is valid if it contains at least one case
        // or a payment amount.
        if (
            $newUpper === 0 &&
            $newLower === 0 &&
            (float) $data['paid_amount'] === 0.0
        ) {
            return response()->json([
                'message' => 'Enter at least one delivered case or a paid amount.'
            ], 422);
        }

        if ($deliveredUpper + $newUpper > $totalUpper) {
            return response()->json(['message' => 'Upper delivered exceeds total upper cases'], 422);
        }
        if ($deliveredLower + $newLower > $totalLower) {
            return response()->json(['message' => 'Lower delivered exceeds total lower cases'], 422);
        }


        // Save / update reusable bank branch account information
        if (
            $data['payment_method'] === 'bank_transfer' &&
            !empty($data['bank_name']) &&
            !empty($data['branch_name']) &&
            !empty($data['account_name']) &&
            !empty($data['account_number'])
        ) {
            $bank = \App\Models\Bank::where(
                'bank_name',
                trim($data['bank_name'])
            )->first();

            if ($bank) {
                $branch = BankBranch::where('bank_id', $bank->id)
                    ->whereRaw(
                        'LOWER(TRIM(branch_name)) = ?',
                        [strtolower(trim($data['branch_name']))]
                    )
                    ->first();

                if ($branch) {
                    $branch->account_name = trim($data['account_name']);
                    $branch->account_number = trim($data['account_number']);
                    $branch->save();
                }
            }
        }

        // Save delivery
        PaymentPlanDelivery::create([
            'payment_plan_id' => $plan->id,
            'upper_delivered' => $newUpper,
            'lower_delivered' => $newLower,
            'paid_amount' => (float) $data['paid_amount'],
            'delivery_date' => $data['delivery_date'],
            'created_by' => auth()->id(),
        ]);

        // Record payment (if any)
        if ((float) $data['paid_amount'] > 0) {
            PaymentPlanPayment::create([
                'payment_plan_id' => $plan->id,
                'amount' => (float) $data['paid_amount'],
                'payment_date' => $data['delivery_date'],
                'payment_method' => $data['payment_method'],

                'bank_name' => $data['bank_name'] ?? null,
                'branch_name' => $data['branch_name'] ?? null,
                'account_name' => $data['account_name'] ?? null,
                'account_number' => $data['account_number'] ?? null,

                'mobile_provider' => $data['mobile_provider'] ?? null,
                'transaction_id' => $data['transaction_id'] ?? null,

                'created_by' => auth()->id(),
            ]);
        }

        // Update remaining on plan
        $paid = (float) PaymentPlanPayment::where('payment_plan_id', $plan->id)->sum('amount');
        $plan->payment_method = $data['payment_method'];
        $plan->remaining_amount = max(0, (float) $plan->total_amount - $paid);
        $plan->save();

        // If fully paid, mark patient inactive
        if ((float) $plan->remaining_amount === 0.0) {
            if ($patient->status !== 'inactive') {
                $patient->status = 'inactive';
                $patient->save();
            }
        }

        $deliveries = PaymentPlanDelivery::where('payment_plan_id', $plan->id)->orderBy('delivery_date')->get();
        $deliveredUpper2 = (int) $deliveries->sum('upper_delivered');
        $deliveredLower2 = (int) $deliveries->sum('lower_delivered');

        return response()->json([
            'success' => true,
            'plan' => $plan,
            'payments' => PaymentPlanPayment::where('payment_plan_id', $plan->id)->orderBy('payment_date')->get(),
            'deliveries' => $deliveries,
            'cases' => [
                'total_upper' => $totalUpper,
                'total_lower' => $totalLower,
                'delivered_upper' => $deliveredUpper2,
                'delivered_lower' => $deliveredLower2,
                'remaining_upper' => max(0, $totalUpper - $deliveredUpper2),
                'remaining_lower' => max(0, $totalLower - $deliveredLower2),
            ],
        ]);
    }

    public function markPaymentPlanCaseDone(string $predict3dId)
    {
        $patient = Patient::where('Predict3DId', $predict3dId)->first();
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        $plan = PaymentPlan::where('predict3d_id', $predict3dId)->first();
        if (!$plan) {
            return response()->json(['message' => 'Payment plan not found'], 404);
        }

        $deliveries = PaymentPlanDelivery::where('payment_plan_id', $plan->id)->get();
        $deliveredUpper = (int) $deliveries->sum('upper_delivered');
        $deliveredLower = (int) $deliveries->sum('lower_delivered');
        $totalUpper = (int) ($patient->UpperCases ?? 0);
        $totalLower = (int) ($patient->LowerCases ?? 0);
        $remainingUpper = max(0, $totalUpper - $deliveredUpper);
        $remainingLower = max(0, $totalLower - $deliveredLower);

        $paid = (float) PaymentPlanPayment::where('payment_plan_id', $plan->id)->sum('amount');
        $due = max(0, (float) $plan->total_amount - $paid);

        if ($remainingUpper > 0 || $remainingLower > 0 || $due > 0) {
            return response()->json([
                'message' => 'Cannot close case yet. Ensure all cases are delivered and due amount is zero.',
                'state' => [
                    'remaining_upper' => $remainingUpper,
                    'remaining_lower' => $remainingLower,
                    'due' => $due,
                ],
            ], 422);
        }

        $plan->is_closed = true;
        $plan->save();

        if ($patient->status !== 'inactive') {
            $patient->status = 'inactive';
            $patient->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Case marked as done successfully.',
        ]);
    }

    public function savePaymentPlan(Request $request, string $predict3dId)
    {
        $data = $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank_transfer,mobile_banking',
            'is_installment' => 'required|boolean',
            'current_payment_amount' => 'nullable|numeric|min:0',
            'current_payment_date' => 'nullable|date',
            // When installment, next payment date is mandatory
            'next_payment_date' => 'required_if:is_installment,1|date',
        ], [
            'next_payment_date.required_if' => 'Next payment date is required when installment is selected.',
        ]);

        $plan = PaymentPlan::firstOrNew(['predict3d_id' => $predict3dId]);
        if ($plan->exists && (bool) $plan->is_closed) {
            return response()->json(['message' => 'This case is closed and cannot be edited.'], 422);
        }
        $plan->predict3d_id = $predict3dId;
        $plan->total_amount = $data['total_amount'];
        $plan->payment_method = $data['payment_method'];
        $plan->is_installment = (bool) $data['is_installment'];
        $plan->next_payment_date = $data['next_payment_date'] ?? null;

        // Compute remaining amount: total - sum(payments) - optionally current payment
        $existingPaid = 0;
        if ($plan->exists) {
            $existingPaid = (float) PaymentPlanPayment::where('payment_plan_id', $plan->id)->sum('amount');
        }
        $currentPaid = (float) ($data['current_payment_amount'] ?? 0);
        $plan->remaining_amount = max(0, (float) $plan->total_amount - $existingPaid - $currentPaid);
        $plan->created_by = auth()->id();
        $plan->save();

        // If plan is now fully paid, mark patient as inactive
        if ((float) $plan->remaining_amount === 0.0) {
            if ($plan->predict3d_id) {
                $p = Patient::where('Predict3DId', $plan->predict3d_id)->first();
                if ($p && $p->status !== 'inactive') {
                    $p->status = 'inactive';
                    $p->save();
                }
            }
        }

        // If there is a current payment amount, record it
        if ($currentPaid > 0) {
            PaymentPlanPayment::create([
                'payment_plan_id' => $plan->id,
                'amount' => $currentPaid,
                'payment_date' => $data['current_payment_date'] ?? now()->toDateString(),
                'payment_method' => $data['payment_method'],
                'created_by' => auth()->id(),
            ]);
        }

        // Recompute paid/remaining
        $paid = (float) PaymentPlanPayment::where('payment_plan_id', $plan->id)->sum('amount');
        $plan->remaining_amount = max(0, (float) $plan->total_amount - $paid);
        $plan->save();

        // If plan is fully paid after this installment, mark patient as inactive
        if ((float) $plan->remaining_amount === 0.0) {
            if ($plan->predict3d_id) {
                $p = Patient::where('Predict3DId', $plan->predict3d_id)->first();
                if ($p && $p->status !== 'inactive') {
                    $p->status = 'inactive';
                    $p->save();
                }
            }
        }

        return response()->json([
            'success' => true,
            'plan' => $plan,
            'payments' => PaymentPlanPayment::where('payment_plan_id', $plan->id)->orderBy('payment_date')->get(),
        ]);
    }

    public function addInstallmentPayment(Request $request, string $predict3dId)
    {
        // Fetch plan first to know if this is an installment plan (create if missing)
        $plan = PaymentPlan::where('predict3d_id', $predict3dId)->first();
        if ($plan && (bool) $plan->is_closed) {
            return response()->json(['message' => 'This case is closed and cannot be edited.'], 422);
        }
        if (!$plan) {
            $plan = new PaymentPlan();
            $plan->predict3d_id = $predict3dId;
            $plan->total_amount = (float) ($request->input('total_amount') ?? $request->input('amount') ?? 0);
            $plan->payment_method = $request->input('payment_method', 'cash');
            $plan->is_installment = false; // default to full payment if created via this endpoint
            $plan->next_payment_date = null;
            $plan->remaining_amount = $plan->total_amount; // will be recomputed below after inserting payment
            $plan->is_closed = false;
            $plan->created_by = auth()->id();
            $plan->save();
        }

        $rules = [
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,mobile_banking',
            'next_payment_date' => ($plan->is_installment ? 'required|date' : 'nullable|date'),
        ];
        $messages = [
            'next_payment_date.required' => 'Next payment date is required for installment plans.',
        ];
        $data = $request->validate($rules, $messages);

        PaymentPlanPayment::create([
            'payment_plan_id' => $plan->id,
            'amount' => $data['amount'],
            'payment_date' => $data['payment_date'],
            'payment_method' => $data['payment_method'],
            'created_by' => auth()->id(),
        ]);

        // Update next date and remaining
        $plan->next_payment_date = $data['next_payment_date'] ?? $plan->next_payment_date;
        $paid = (float) PaymentPlanPayment::where('payment_plan_id', $plan->id)->sum('amount');
        $plan->remaining_amount = max(0, (float) $plan->total_amount - $paid);
        $plan->save();

        return response()->json([
            'success' => true,
            'plan' => $plan,
            'payments' => PaymentPlanPayment::where('payment_plan_id', $plan->id)->orderBy('payment_date')->get(),
        ]);
    }

    public function updatePlanTotal(Request $request, string $predict3dId)
    {
        // Only Admins can update total
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'total_amount' => 'required|numeric|min:0',
        ]);
        // Find or create plan to avoid 404 when updating total before plan exists
        $plan = PaymentPlan::firstOrNew(['predict3d_id' => $predict3dId]);
        if ($plan->exists && (bool) $plan->is_closed) {
            return response()->json(['message' => 'This case is closed and cannot be edited.'], 422);
        }
        if (!$plan->exists) {
            $plan->predict3d_id = $predict3dId;
            $plan->payment_method = $plan->payment_method ?: 'cash';
            $plan->is_installment = (bool) ($plan->is_installment ?? false);
            $plan->next_payment_date = $plan->next_payment_date ?? null;
            $plan->remaining_amount = $plan->remaining_amount ?? 0;
            $plan->is_closed = false;
            $plan->created_by = auth()->id();
        }
        $paid = (float) PaymentPlanPayment::where('payment_plan_id', $plan->id)->sum('amount');
        if ($data['total_amount'] < $paid) {
            return response()->json(['message' => 'Total cannot be less than amount already paid'], 422);
        }
        $plan->total_amount = $data['total_amount'];
        $plan->remaining_amount = max(0, (float) $plan->total_amount - $paid);
        $plan->save();
        return response()->json(['success' => true, 'plan' => $plan]);
    }
    public function productionIndex()
    {
        return view('admin.production.index');
    }

    public function findPatientByPredictId(string $predict3dId)
    {
        $patient = \App\Models\Patient::where('Predict3DId', $predict3dId)
            ->with('payments')
            ->first();

        if(!$patient){
            return response()->json(['message' => 'Patient not found'], 404);
        }

        return response()->json($patient);
    }

    /**
     * Save patient cases (Upper and Lower)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $predict3dId
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePatientCases(Request $request, $predict3dId)
    {
        $request->validate([
            'upper_cases' => 'required|integer|min:0',
            'lower_cases' => 'required|integer|min:0',
        ]);

        $patient = \App\Models\Patient::where('Predict3DId', $predict3dId)->firstOrFail();

        $patient->update([
            'UpperCases' => $request->upper_cases,
            'LowerCases' => $request->lower_cases,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cases updated successfully',
            'patient' => $patient
        ]);
    }

    /**
     * Get production steps for a patient by Predict3DId
     */
    public function getProductionSteps(string $predict3dId)
    {
        $steps = ProductionStep::where('predict3d_id', $predict3dId)
            ->orderBy('step_number')
            ->get(['step_number','upper_value','lower_value']);

        return response()->json([
            'predict3d_id' => $predict3dId,
            'steps' => $steps,
        ]);
    }

    /**
     * Save production steps for a patient by Predict3DId
     */
    public function saveProductionSteps(Request $request, string $predict3dId)
    {
        $data = $request->validate([
            'steps' => 'required|array|min:1',
            'steps.*.step_number' => 'required|integer|min:1',
            'steps.*.upper' => 'nullable|integer|min:0',
            'steps.*.lower' => 'nullable|integer|min:0',
        ]);

        $hasPatientPredict = Schema::hasColumn('production_steps', 'patient_predict3d_id');
        $hasStepType = Schema::hasColumn('production_steps', 'step_type');
        $hasCreatedBy = Schema::hasColumn('production_steps', 'created_by');

        foreach ($data['steps'] as $step) {
            $values = [
                'upper_value' => $step['upper'] ?? null,
                'lower_value' => $step['lower'] ?? null,
            ];
            if ($hasPatientPredict) { $values['patient_predict3d_id'] = $predict3dId; }
            if ($hasStepType) { $values['step_type'] = 'UL'; }
            if ($hasCreatedBy) { $values['created_by'] = auth()->id() ?? 0; }

            ProductionStep::updateOrCreate(
                [
                    'predict3d_id' => $predict3dId,
                    'step_number' => (int) $step['step_number'],
                ],
                $values
            );
        }

        $saved = ProductionStep::where('predict3d_id', $predict3dId)
            ->orderBy('step_number')
            ->get(['step_number','upper_value','lower_value']);

        return response()->json([
            'success' => true,
            'predict3d_id' => $predict3dId,
            'steps' => $saved,
        ]);
    }
}
