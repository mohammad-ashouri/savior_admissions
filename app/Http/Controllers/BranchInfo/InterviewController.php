<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\Interview;
use App\Models\User;
use App\Models\UserAccessInformation;

class InterviewController extends Controller
{
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

        $removeInterview = Interview::find($id)->delete();

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

        $removeInterviewReserve = Interview::find($id);
        if ($removeInterviewReserve->reserved == 0) {
            $removeInterviewReserve->reserved = 1;
        } else {
            $removeInterviewReserve->reserved = 0;
        }

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

        $changeInterviewStatus = Interview::find($id);
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
