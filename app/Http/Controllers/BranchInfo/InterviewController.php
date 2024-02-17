<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Catalogs\AcademicYear;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;

class InterviewController extends Controller
{
        public function __construct()
        {
            $this->middleware('permission:interview-list', ['only' => ['index']]);
            $this->middleware('permission:interview-set', ['only' => ['set']]);
            $this->middleware('permission:interview-edit', ['only' => ['edit', 'update']]);
            $this->middleware('permission:interview-delete', ['only' => ['destroy']]);
            $this->middleware('permission:interview-search', ['only' => ['search']]);
            $this->middleware('permission:interview-show', ['only' => ['show']]);
        }

    public function index()
    {
        $me = User::find(session('id'));
        $interviews = [];
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $interviews = ApplicationReservation::with('studentInfo')
                ->with('reservatoreInfo')
                ->with('applicationInvoiceInfo')
                ->whereIn('student_id', $myStudents)
                ->orderBy('date','desc')
                ->orderBy('ends_to','desc')
                ->orderBy('start_from','desc')
                ->paginate(30);
        } elseif ($me->hasRole('Super Admin')) {
            $interviews = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->where('reserved', 1)
                ->where('reserved', 1)
                ->orderBy('date','desc')
                ->orderBy('ends_to','desc')
                ->orderBy('start_from','desc')
                ->paginate(30);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
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
            $interviews = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->where('reserved', 1)
                ->whereIn('application_timing_id', $applicationTimings)
                ->where('reserved', 1)
                ->orderBy('date','desc')
                ->orderBy('ends_to','desc')
                ->orderBy('start_from','desc')
                ->paginate(30);

        } elseif ($me->hasRole('Interviewer')) {
            $interviews = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->where('interviewer', $me->id)
                ->where('Interviewed', 0)
                ->orderBy('date','desc')
                ->orderBy('ends_to','desc')
                ->orderBy('start_from','desc')
                ->paginate(30);
        }

        if ($interviews->isEmpty()) {
            $interviews = [];
        }

        return view('BranchInfo.Interviews.index', compact('interviews'));

    }
}
