<?php

namespace App\Http\Controllers;

use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\Applications;
use App\Models\Branch\InterviewReservation;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    function __construct()
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
        $me=User::find(session('id'));
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')){
            $myChildes=StudentInformation::where('guardian',$me->id)->pluck('student_id')->toArray();
            $applications=InterviewReservation::with('interviewInfo')->with('studentInfo')->with('reservatoreInfo')->whereIn('student_id',$myChildes)->get();
        }

        if ($applications->isEmpty()){
            $applications=[];
        }
        return view('Applications.index', compact('applications'));

    }

    public function destroy($id)
    {
        $me = User::find(session('id'));
        $checkAccessToInterview = [];
        if (! $me->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            if (isset($myAllAccesses->principal) or isset($myAllAccesses->admissions_officer)) {
                $principalAccess = explode('|', $myAllAccesses->principal);
                $admissionsOfficerAccess = explode('|', $myAllAccesses->admissions_officer);
                $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
                $checkAccessToInterview = ApplicationTiming::with('academicYearInfo')
                    ->with('interviews')
                    ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                    ->join('interviews', 'application_timings.id', '=', 'interviews.application_timing_id')
                    ->whereIn('academic_years.school_id', $filteredArray)
                    ->where('interviews.id', $id)
                    ->select('application_timings.*', 'academic_years.id as academic_year_id')
                    ->first();
                if (! $checkAccessToInterview) {
                    return redirect()->back()
                        ->withErrors(['errors' => 'Delete Failed!']);
                }
            }
        }

        $removeInterview = Applications::find($id)->delete();

        if (! $removeInterview) {
            return redirect()->back()
                ->withErrors(['errors' => 'Delete Failed!']);
        }

        return redirect()->back()
            ->with('success', 'Interview deleted!');
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
                $checkAccessToInterview = ApplicationTiming::with('academicYearInfo')
                    ->with('interviews')
                    ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                    ->join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
                    ->whereIn('academic_years.school_id', $filteredArray)
                    ->where('applications.id', $id)
                    ->select('application_timings.*', 'academic_years.id as academic_year_id')
                    ->first();
                if (! $checkAccessToInterview) {
                    return redirect()->back()
                        ->withErrors(['errors' => 'Delete Failed!']);
                }
            }
        }

        $removeInterviewReserve = Applications::find($id);
        $removeInterviewReserve->reserved = 0;

        if (! $removeInterviewReserve->save()) {
            return redirect()->back()
                ->withErrors(['errors' => 'Remove Interview Reservation Failed!']);
        }

        return redirect()->back()
            ->with('success', 'Interview Reservation Changed!');
    }

    public function changeInterviewStatus($id)
    {
        $me = User::find(session('id'));
        if (! $me->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            if (isset($myAllAccesses->principal) or isset($myAllAccesses->admissions_officer)) {
                $principalAccess = explode('|', $myAllAccesses->principal);
                $admissionsOfficerAccess = explode('|', $myAllAccesses->admissions_officer);
                $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
                $checkAccessToInterview = ApplicationTiming::with('academicYearInfo')
                    ->with('interviews')
                    ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                    ->join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
                    ->whereIn('academic_years.school_id', $filteredArray)
                    ->where('applications.id', $id)
                    ->select('application_timings.*', 'academic_years.id as academic_year_id')
                    ->first();
                if (! $checkAccessToInterview) {
                    return redirect()->back()
                        ->withErrors(['errors' => 'Delete Failed!']);
                }
            }
        }

        $changeInterviewStatus = Applications::find($id);
        if ($changeInterviewStatus->status == 0) {
            $changeInterviewStatus->status = 1;
        } else {
            $changeInterviewStatus->status = 0;
        }

        if (! $changeInterviewStatus->save()) {
            return redirect()->back()
                ->withErrors(['errors' => 'Remove Interview Status Failed!']);
        }

        return redirect()->back()
            ->with('success', 'Interview Status Changed!');
    }
}
