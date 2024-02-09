<?php

namespace App\Http\Controllers;

use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\InterviewReservation;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\Level;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:applications-list', ['only' => ['index']]);
        $this->middleware('permission:new-application-reserve', ['only' => ['create', 'store']]);
        $this->middleware('permission:show-application-reserve', ['only' => ['show']]);
        $this->middleware('permission:edit-application-reserve', ['only' => ['edit', 'update']]);
        $this->middleware('permission:remove-application', ['only' => ['destroy']]);
        $this->middleware('permission:remove-application-from-reserve', ['only' => ['removeFromReserve']]);
        $this->middleware('permission:change-status-of-application', ['only' => ['changeInterviewStatus']]);
    }

    public function index()
    {
        $me = User::find(session('id'));
        $applications = [];
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myChildes = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $applications = InterviewReservation::with('interviewInfo')->with('studentInfo')->with('reservatoreInfo')->whereIn('student_id', $myChildes)->paginate(30);
        } elseif ($me->hasRole('Super Admin')) {
            $applications = InterviewReservation::with('interviewInfo')->with('studentInfo')->with('reservatoreInfo')->paginate(30);
        }

        if (empty($applications)) {
            $applications = [];
        }

        return view('Applications.index', compact('applications'));

    }

    public function create()
    {
        $me = User::find(session('id'));
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myChildes = StudentInformation::with('generalInformations')->where('guardian', $me->id)->orderBy('id')->get();
            $levels = Level::where('status', 1)->get();

            return view('Applications.create', compact('myChildes', 'levels'));
        }
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

    public function removeFromReserve($id)
    {
        $me = User::find(session('id'));
        if (! $me->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            if (isset($myAllAccesses->principal) or isset($myAllAccesses->admissions_officer)) {
                $principalAccess = explode('|', $myAllAccesses->principal);
                $admissionsOfficerAccess = explode('|', $myAllAccesses->admissions_officer);
                $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
                $checkAccessToApplication = ApplicationTiming::with('academicYearInfo')
                    ->with('interviews')
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

        $removeApplicationReserve = Applications::find($id);
        $removeApplicationReserve->reserved = 0;

        if (! $removeApplicationReserve->save()) {
            return redirect()->back()
                ->withErrors(['errors' => 'Remove Application Reservation Failed!']);
        }

        return redirect()->back()
            ->with('success', 'Application Reservation Changed!');
    }

    public function changeApplicationStatus($id)
    {
        $me = User::find(session('id'));
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

        $changeApplicationStatus = Applications::find($id);
        if ($changeApplicationStatus->status == 0) {
            $changeApplicationStatus->status = 1;
        } else {
            $changeApplicationStatus->status = 0;
        }

        if (! $changeApplicationStatus->save()) {
            return redirect()->back()
                ->withErrors(['errors' => 'Remove Interview Status Failed!']);
        }

        return redirect()->back()
            ->with('success', 'Interview Status Changed!');
    }

    public function getAcademicYearsByLevel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level' => 'required|exists:levels,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Error on choosing level!'], 422);
        }
        $level = $request->level;
        $academicYears = AcademicYear::where('status', 1)->whereJsonContains('levels', $level)->select('id', 'name')->get()->toArray();

        return $academicYears;
    }

    public function getApplicationsByAcademicYear(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|exists:academic_years,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Error on choosing academic year!'], 422);
        }

        $applicationTimings = ApplicationTiming::with('applications')
            ->join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
            ->where('application_timings.academic_year', $request->academic_year)
            ->where('applications.status', 1)
            ->where('applications.reserved', 0)
            ->select('applications.*', 'application_timings.id as application_timings_id')
            ->orderBy('application_timings.start_date')
            ->get();

        return $applicationTimings;
    }
}
