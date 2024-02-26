<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\Interview;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:interview-list', ['only' => ['index']]);
        $this->middleware('permission:interview-set', ['only' => ['SetInterview']]);
        $this->middleware('permission:interview-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:interview-delete', ['only' => ['destroy']]);
        $this->middleware('permission:interview-search', ['only' => ['search']]);
//        $this->middleware('permission:interview-show', ['only' => ['show']]);
    }

    public function index()
    {
        $me = User::find(session('id'));
        $interviews = [];
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $reservations = ApplicationReservation::whereIn('student_id', $myStudents)->pluck('application_id')->toArray();
            $interviews = Applications::
            with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->whereIn('id', $reservations)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->paginate(30);
        } elseif ($me->hasRole('Super Admin')) {
            $interviews = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
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
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->whereIn('application_timing_id', $applicationTimings)
                ->where('reserved', 1)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->paginate(30);

        } elseif ($me->hasRole('Interviewer')) {
            $interviews = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->where('interviewer', $me->id)
                ->orderBy('Interviewed', 'desc')
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->paginate(30);
        }

        if ($interviews->isEmpty()) {
            $interviews = [];
        }

        return view('BranchInfo.Interviews.index', compact('interviews'));

    }

    public function GetInterviewForm($id)
    {
        $me = User::find(session('id'));
        $interview = [];
        if ($me->hasRole('Super Admin')) {
            $interview = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->where('reserved', 1)
                ->where('id', $id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
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
            $interview = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->where('reserved', 1)
                ->whereIn('application_timing_id', $applicationTimings)
                ->where('reserved', 1)
                ->where('id', $id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();

        } elseif ($me->hasRole('Interviewer')) {
            $interview = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->where('interviewer', $me->id)
                ->where('Interviewed', 0)
                ->where('id', $id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
        }
        if (empty($interview)) {
            abort(403);
        }

        return view('BranchInfo.Interviews.set', compact('interview'));
    }

    public function SetInterview(Request $request)
    {
        $me = User::find(session('id'));
        $interview = [];
        if ($me->hasRole('Super Admin')) {
            $interview = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->where('reserved', 1)
                ->where('id', $request->application_id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
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
            $interview = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->where('reserved', 1)
                ->whereIn('application_timing_id', $applicationTimings)
                ->where('reserved', 1)
                ->where('id', $request->application_id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();

        } elseif ($me->hasRole('Interviewer')) {
            $interview = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->where('interviewer', $me->id)
                ->where('Interviewed', 0)
                ->where('id', $request->application_id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
        }
        if (empty($interview)) {
            abort(403);
        }

        $interview = new Interview();
        $interview->application_id = $request->application_id;
        $interview->interview_form = json_encode($request->all(), true);
        if ($interview->save()) {
            $application = Applications::with('applicationTimingInfo')->with('reservationInfo')->find($request->application_id);
            switch ($application->reservationInfo->levelInfo->name) {
                case('Kindergarten 1'):
                case('Kindergarten 2'):
                    if ($request->s1_1_s == 'Rejected' or $request->s1_2_s == 'Rejected' or $request->s1_3_s == 'Rejected' or $request->s1_4_s == 'Rejected') {
                        $interviewStatus = "Rejected";
                    } else {
                        $interviewStatus = "Admitted";
                    }
                    break;
                default:
                    if ($request->s1_1_s == 'Rejected' or $request->s1_2_s == 'Rejected' or $request->s1_3_s == 'Rejected') {
                        $interviewStatus = "Rejected";
                    } else {
                        $interviewStatus = "Admitted";
                    }
            }
            $application->interviewed = 1;
            if ($application->save()) {
                $studentStatus = new StudentApplianceStatus();
                $studentStatus->student_id = $application->reservationInfo->student_id;
                $studentStatus->application_id = $application->id;
                $studentStatus->interview_status = $interviewStatus;
                if ($interviewStatus == "Admitted") {
                    $studentStatus->documents_uploaded = 0;
                }
                $studentStatus->save();
                return redirect()->route('Interviews.index')
                    ->with('success', 'The interview was successfully recorded');
            }

            return redirect()->route('Interviews.index')
                ->withErrors(['errors' => 'Recording the interview failed!']);
        }

        return redirect()->route('Interviews.index')
            ->withErrors(['errors' => 'Recording the interview failed!']);
    }

    public function show($id)
    {
        $me = User::find(session('id'));
        $interview = [];
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $reservations = ApplicationReservation::whereIn('student_id', $myStudents)->pluck('application_id')->toArray();
            $interview = Applications::
            with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->whereIn('id', $reservations)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
        } elseif ($me->hasRole('Super Admin')) {
            $interview = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->with('interview')
                ->where('reserved', 1)
                ->where('Interviewed', 1)
                ->where('id', $id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
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
            $interview = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->with('interview')
                ->where('reserved', 1)
                ->whereIn('application_timing_id', $applicationTimings)
                ->where('reserved', 1)
                ->where('Interviewed', 1)
                ->where('id', $id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();

        } elseif ($me->hasRole('Interviewer')) {
            $interview = Applications::with('applicationTimingInfo')
                ->with('interviewerInfo')
                ->with('reservationInfo')
                ->with('interview')
                ->where('reserved', 1)
                ->where('interviewer', $me->id)
                ->where('Interviewed', 1)
                ->where('id', $id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
        }
        if (empty($interview)) {
            abort(403);
        }

        return view('BranchInfo.Interviews.show', compact('interview'));
    }
}
