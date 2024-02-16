<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\Level;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;

class ApplicationReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reservation-invoice-list', ['only' => ['index']]);
        $this->middleware('permission:reservation-invoice-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:reservation-invoice-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:reservation-invoice-delete', ['only' => ['destroy']]);
        $this->middleware('permission:reservation-invoice-show', ['only' => ['show']]);
        $this->middleware('permission:reservation-invoice-search', ['only' => ['searchApplicationTiming']]);
    }

    public function index()
    {
        $me = User::find(session('id'));
        $applications = [];
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $applications = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->with('applicationInvoiceInfo')
                ->whereIn('student_id', $myStudents)
                ->paginate(30);
        } elseif ($me->hasRole('Super Admin')) {
            $applications = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->with('applicationInvoiceInfo')
                ->paginate(30);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $principalAccess = explode('|', $myAllAccesses->principal);
            $financialManagerAccess = explode('|', $myAllAccesses->financial_manager);
            $filteredArray = array_filter(array_unique(array_merge($principalAccess, $financialManagerAccess)));

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $applications = Applications::whereIn('application_timing_id', $applicationTimings)
                ->pluck('id')
                ->toArray();

            // Getting reservations of applications along with related information
            $applications = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->with('applicationInvoiceInfo')
                ->whereIn('application_id', $applications)
                ->paginate(30);
        }

        if ($applications->isEmpty()) {
            $applications = [];
        }

        return view('Finance.ApplicationReservationInvoices.index', compact('applications'));

    }

    public function create()
    {
        $me = User::find(session('id'));
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::with('generalInformations')->where('guardian', $me->id)->orderBy('id')->get();
            $levels = Level::where('status', 1)->get();

            return view('Finance.ApplicationReservationInvoices.create', compact('myStudents', 'levels'));
        }
    }

    public function show($id)
    {
        $me = User::find(session('id'));
        $applicationReservation = ApplicationReservation::find($id);
        if (empty($applicationReservation)) {
            abort(403);
        }

        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->whereIn('student_id', $myStudents)->where('id', $id)->first();
        } elseif ($me->hasRole('Super Admin')) {
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->where('id', $id)->first();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $principalAccess = explode('|', $myAllAccesses->principal);
            $financialManagerAccess = explode('|', $myAllAccesses->financial_manager);
            $filteredArray = array_filter(array_unique(array_merge($principalAccess, $financialManagerAccess)));

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $applications = Applications::whereIn('application_timing_id', $applicationTimings)
                ->pluck('id')
                ->toArray();

            // Getting reservations of applications along with related information
            $applicationInfo = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->whereIn('application_id', $applications)
                ->where('id', $id)->first();
            if (empty($applicationInfo)) {
                abort(403);
            }
        }

        return view('Finance.ApplicationReservationInvoices.show', compact('applicationInfo'));
    }

    public function destroy($id)
    {
        $me = User::find(session('id'));
        $checkAccessToApplication = [];
        if (! $me->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            if (isset($myAllAccesses->principal) or isset($myAllAccesses->admissions_officer)) {
                $principalAccess = explode('|', $myAllAccesses->principal);
                $admissionsOfficerAccess = explode('|', $myAllAccesses->admissions_officer);
                $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
                $checkAccessToApplication = ApplicationTiming::with('academicYearInfo')
                    ->with('applications')
                    ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                    ->join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
                    ->whereIn('academic_years.school_id', $filteredArray)
                    ->where('applications.id', $id)
                    ->select('application_timings.*', 'academic_years.id as academic_year_id')
                    ->first();
                if (! $checkAccessToApplication) {
                    return redirect()->back()
                        ->withErrors(['errors' => 'Delete Failed!']);
                }
            }
        }

        $removeApplication = Applications::find($id)->delete();

        if (! $removeApplication) {
            return redirect()->back()
                ->withErrors(['errors' => 'Delete Failed!']);
        }

        return redirect()->back()
            ->with('success', 'Application deleted!');
    }

    public function changeApplicationPaymentStatus(Request $request)
    {
        $me = User::find(session('id'));
        $applicationID=$request->application_id;
        $applicationStatus=$request->status;

        if ($me->hasRole('Super Admin')) {
            $applicationInfo = ApplicationReservation::with('levelInfo')->with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->with('applicationInvoiceInfo')->where('id', $applicationID)->first();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $principalAccess = explode('|', $myAllAccesses->principal);
            $financialManagerAccess = explode('|', $myAllAccesses->financial_manager);
            $filteredArray = array_filter(array_unique(array_merge($principalAccess, $financialManagerAccess)));

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $applications = Applications::whereIn('application_timing_id', $applicationTimings)
                ->pluck('id')
                ->toArray();

            // Getting reservations of applications along with related information
            $applicationInfo = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->whereIn('application_id', $applications)
                ->where('id', $applicationID)->first();

        }

        if (empty($applicationInfo)) {
            return response()->json(['error' => $applicationInfo], 422);
        }

        return $request->all();
    }
}
