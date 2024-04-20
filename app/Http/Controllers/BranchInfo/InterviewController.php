<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\Interview;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Finance\Discount;
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

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(session('id'));
        $interviews = [];
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $reservations = ApplicationReservation::whereIn('student_id', $myStudents)->pluck('application_id')->toArray();
            $interviews = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->whereIn('id', $reservations)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->paginate(30);
        } elseif ($me->hasRole('Super Admin')) {
            $interviews = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->paginate(30);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $interviews = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
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
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->where(function ($query) use ($me) {
                    $query->where('first_interviewer', $me->id)
                        ->orWhere('second_interviewer', $me->id);
                })
                ->orderBy('interviewed', 'desc') // Corrected column name
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->paginate(30);

        }

        if ($interviews->isEmpty()) {
            $interviews = [];
        }
        $this->logActivity(json_encode(['activity' => 'Getting Interviews']), request()->ip(), request()->userAgent());

        return view('BranchInfo.Interviews.index', compact('interviews'));

    }

    public function GetInterviewForm($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(session('id'));
        $interview = [];
        if ($me->hasRole('Super Admin')) {
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->where('reserved', 1)
                ->where('id', $id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
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
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->where(function ($query) use ($me) {
                    $query->where('first_interviewer', $me->id)
                        ->orWhere('second_interviewer', $me->id);
                })
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

        $discounts = Discount::with('allDiscounts')
            ->where('academic_year', $interview->applicationTimingInfo->academic_year)
            ->join('discount_details', 'discounts.id', '=', 'discount_details.discount_id')
            ->where('discount_details.status', 1)
            ->where('discount_details.interviewer_permission', 1)
            ->get();
        $this->logActivity(json_encode(['activity' => 'Getting Interview Form', 'interview_id' => $interview->id]), request()->ip(), request()->userAgent());

        return view('BranchInfo.Interviews.set', compact('interview', 'discounts'));
    }

    public function SetInterview(Request $request): \Illuminate\Http\RedirectResponse
    {
        $me = User::find(session('id'));
        $interview = [];
        if ($me->hasRole('Super Admin')) {
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->where('reserved', 1)
                ->where('id', $request->application_id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
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
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->where(function ($query) use ($me) {
                    $query->where('first_interviewer', $me->id)
                        ->orWhere('second_interviewer', $me->id);
                })
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
        $interview->interviewer = session('id');
        switch ($request->form_type) {
            case 'kg1':
            case 'l1':
                $interview->interview_type = 1;
                break;
            case 'kg2':
            case 'l2':
                $interview->interview_type = 2;
                break;
            case 'kga':
            case 'la':
                $interview->interview_type = 3;
                break;
        }

        if ($interview->save()) {
            $application = Applications::with('applicationTimingInfo')->with('reservationInfo')->find($request->application_id);
            switch ($request->form_type) {
                case 'kga':
                case 'la':
                    $application->interviewed = 1;
                    break;
            }

            if ($application->save()) {
                //                $studentStatus = StudentApplianceStatus::where('student_id', $application->reservationInfo->student_id)->where('academic_year', $application->applicationTimingInfo->academic_year)->first();
                //                if (empty($studentStatus)) {
                //                    $studentStatus = new StudentApplianceStatus();
                //                    $studentStatus->student_id = $application->reservationInfo->student_id;
                //                    $studentStatus->academic_year = $application->applicationTimingInfo->academic_year;
                //                    $studentStatus->interview_status = $interviewStatus;
                //                } else {
                //                    $studentStatus->student_id = $application->reservationInfo->student_id;
                //                    $studentStatus->application_id = $application->applicationTimingInfo->academic_year;
                //                    $studentStatus->interview_status = $interviewStatus;
                //                }
                //                if ($interviewStatus == 'Admitted') {
                //                    $studentStatus->documents_uploaded = 0;
                //                }
                //                $studentStatus->save();
                $this->logActivity(json_encode(['activity' => 'Interview Set Successfully', 'interview_id' => $interview->id]), request()->ip(), request()->userAgent());

                return redirect()->route('interviews.index')
                    ->with('success', 'The interview was successfully recorded');
            }
            $this->logActivity(json_encode(['activity' => 'Recording The Interview Failed', 'application_id' => $request->application_id]), request()->ip(), request()->userAgent());

            return redirect()->route('interviews.index')
                ->withErrors(['errors' => 'Recording the interview failed!']);
        }
        $this->logActivity(json_encode(['activity' => 'Recording The Interview Failed', 'application_id' => $request->application_id]), request()->ip(), request()->userAgent());

        return redirect()->route('interviews.index')
            ->withErrors(['errors' => 'Recording the interview failed!']);
    }

    public function show($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(session('id'));
        $interview = [];
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $reservations = ApplicationReservation::whereIn('student_id', $myStudents)->pluck('application_id')->toArray();
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->where('reserved', 1)
                ->whereIn('id', $reservations)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
        } elseif ($me->hasRole('Super Admin')) {
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
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
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
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
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->with('interview')
                ->where('reserved', 1)
                ->where(function ($query) use ($me) {
                    $query->where('first_interviewer', $me->id)
                        ->orWhere('second_interviewer', $me->id);
                })
                ->where('id', $id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
        }
        if (empty($interview)) {
            abort(403);
        }

        $this->logActivity(json_encode(['activity' => 'Getting Interview', 'interview_id' => $interview->id]), request()->ip(), request()->userAgent());

        return view('BranchInfo.Interviews.show', compact('interview'));
    }
}
