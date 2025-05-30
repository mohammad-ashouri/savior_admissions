<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\Level;
use App\Models\User;
use App\Models\UserAccessInformation;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationTimingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:application-timing-list', ['only' => ['index']]);
        $this->middleware('permission:application-timing-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:application-timing-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:application-timing-delete', ['only' => ['destroy']]);
        $this->middleware('permission:application-timing-search', ['only' => ['searchApplicationTiming']]);
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $applicationTimings = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $applicationTimings = ApplicationTiming::with('academicYearInfo')
                ->with('firstInterviewer')
                ->with('secondInterviewer')
                ->orderBy('start_date', 'desc')->get();
            if ($applicationTimings->isEmpty()) {
                $applicationTimings = [];
            }
        } elseif (! auth()->user()->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            $applicationTimings = ApplicationTiming::with('academicYearInfo')
                ->with('firstInterviewer')
                ->with('secondInterviewer')
                ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                ->whereIn('academic_years.school_id', $filteredArray)
                ->orderBy('application_timings.start_date', 'desc')
                ->select('application_timings.*', 'academic_years.id as academic_year_id')
                ->get();

            if ($applicationTimings->isEmpty()) {
                $applicationTimings = [];
            }
        }

        return view('BranchInfo.ApplicationTimings.index', compact('applicationTimings'));
    }

    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $academicYears = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $academicYears = AcademicYear::whereStatus(1)->get();
        } elseif (auth()->user()->hasRole(['Principal','Admissions Officer'])) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->get();
            if ($academicYears->count() == 0) {
                $academicYears = [];
            }
        }

        return view('BranchInfo.ApplicationTimings.create', compact('academicYears'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|exists:academic_years,id',
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'interview_time' => 'required|integer|min:1',
            'delay_between_reserve' => 'required|integer|min:1',
            'first_interviewer' => 'required|exists:users,id',
            'second_interviewer' => 'required|exists:users,id',
            'interview_fee' => 'required|min:0',
            'meeting_link' => 'required|string',
            'grades' => 'required|array',
            'grades.*' => 'required|integer|min:1|exists:levels,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->first_interviewer == $request->second_interviewer) {
            return redirect()->back()->withErrors(['errors' => 'The first and second interviewers cannot be equal.'])->withInput();
        }

        $academicYears = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $academicYears = AcademicYear::whereStatus(1)->get();
        } elseif (auth()->user()->hasRole(['Principal','Admissions Officer'])) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->get();
            if ($academicYears->count() == 0) {
                $academicYears = [];
            }
        }

        if (! empty($academicYears)) {
            $applicationTiming = new ApplicationTiming;
            $applicationTiming->academic_year = $request->academic_year;
            $applicationTiming->start_date = $request->start_date;
            $applicationTiming->start_time = $request->start_time;
            $applicationTiming->end_date = $request->end_date;
            $applicationTiming->end_time = $request->end_time;
            $applicationTiming->interview_time = $request->interview_time;
            $applicationTiming->delay_between_reserve = $request->delay_between_reserve;
            $applicationTiming->first_interviewer = $request->first_interviewer;
            $applicationTiming->second_interviewer = $request->second_interviewer;
            $applicationTiming->fee = $request->interview_fee;
            $applicationTiming->grades = json_encode($request->grades);
            $applicationTiming->status = 1;
            $applicationTiming->meeting_link = $request->meeting_link;

            if ($applicationTiming->save()) {
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $daysBetween = [];
                $currentDate = clone $startDate;

                while ($currentDate <= $endDate) {
                    $daysBetween[] = $currentDate->format('Y-m-d');
                    $currentDate->addDay();
                }

                foreach ($daysBetween as $day) {
                    //                    foreach ($request->interviewers as $interviewer) {
                    $duration = $request->interview_time;
                    $breakTime = $request->delay_between_reserve;
                    $startTime = Carbon::createFromFormat('H:i', $request->start_time);
                    $endTime = Carbon::createFromFormat('H:i', $request->end_time);
                    $totalSessions = floor($startTime->diffInMinutes($endTime) / ($duration + $breakTime));
                    $currentDateTime = $startTime;

                    for ($i = 1; $i <= $totalSessions; $i++) {
                        $interview = new Applications;
                        $interview->application_timing_id = $applicationTiming->id;
                        $interview->date = $day;
                        $start_from = $currentDateTime->format('H:i');
                        $interview->start_from = $start_from;
                        $currentDateTime->addMinutes($duration);
                        $ends_to = $currentDateTime->format('H:i');
                        $interview->ends_to = $ends_to;
                        $interview->first_interviewer = $request->first_interviewer;
                        $interview->second_interviewer = $request->second_interviewer;
                        $currentDateTime->addMinutes($breakTime);
                        $interview->save();
                    }
                    //                    }
                }
            } else {
                return redirect()->route('ApplicationTimings.create')
                    ->withErrors(['errors' => 'Creating application timing failed!']);
            }
        } else {
            return redirect()->route('ApplicationTimings.create')
                ->withErrors(['errors' => 'Creating application timing failed!']);
        }

        return redirect()->route('ApplicationTimings.index')
            ->with('success', 'Application timing created successfully');
    }

    public function show($id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $applicationTiming = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $applicationTiming = ApplicationTiming::with('applications')
                ->with('firstInterviewer')
                ->with('secondInterviewer')
                ->find($id);
        } elseif (auth()->user()->hasRole(['Principal','Admissions Officer'])) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            $applicationTiming = ApplicationTiming::with('academicYearInfo')
                ->with('firstInterviewer')
                ->with('secondInterviewer')
                ->with('applications')
                ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                ->whereIn('academic_years.school_id', $filteredArray)
                ->where('application_timings.id', $id)
                ->select('application_timings.*', 'academic_years.id as academic_year_id')
                ->first();
        }

        return view('BranchInfo.ApplicationTimings.show', compact('applicationTiming'));
    }

    public function interviewers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|exists:academic_years,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Error on choosing academic year!'], 422);
        }

        $academicYear = $request->academic_year;

        if (auth()->user()->hasRole('Super Admin')) {
            $academicYearInterviewers = AcademicYear::whereStatus(1)->whereId($academicYear)->pluck('employees')->first();
            $interviewers = User::whereIn('id', json_decode($academicYearInterviewers, true)['Interviewer'][0])->whereStatus(1)->with('generalInformationInfo')->get()->keyBy('id')->toArray();
        } else {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            $academicYearInterviewers = AcademicYear::whereStatus(1)->whereId($academicYear)->whereIn('school_id', $filteredArray)->pluck('employees')->first();
            if (empty($academicYearInterviewers)) {
                $academicYearInterviewers = [];
            }
            $interviewers = User::whereIn('id', json_decode($academicYearInterviewers, true)['Interviewer'][0])->whereStatus(1)->with('generalInformationInfo')->get()->toArray();
            if (empty($interviewers)) {
                $interviewers = [];
            }
        }

        return $interviewers;
    }

    public function grades(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|exists:academic_years,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Error on choosing academic year!'], 422);
        }

        $academicYear = $request->academic_year;

        $academicYearGrades = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $academicYearGrades = AcademicYear::whereStatus(1)->whereId($academicYear)->pluck('levels')->first();
            $grades = Level::whereIn('id', json_decode($academicYearGrades, true))->get()->toArray();
        } else {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            $academicYearGrades = AcademicYear::whereStatus(1)->whereId($academicYear)->whereIn('school_id', $filteredArray)->pluck('levels')->first();
            $grades = Level::whereIn('id', json_decode($academicYearGrades, true))->get()->toArray();
        }

        return $grades;
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $applicationTiming = $applications = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $applicationTiming = ApplicationTiming::with('applications')
                ->whereId($id)
                ->whereHas('applications', function ($query) use ($id) {
                    $query->whereApplicationTimingId($id);
                })
                ->first();

        } elseif (! auth()->user()->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            $applicationTiming = ApplicationTiming::with('applications')
                ->join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                ->whereIn('academic_years.school_id', $filteredArray)
                ->whereHas('applications', function ($query) use ($id) {
                    $query->whereApplicationTimingId($id);
                })
                ->where('application_timings.deleted_at', null)
                ->orderBy('application_timings.id', 'desc')
                ->select('application_timings.*', 'academic_years.id as academic_year_id')
                ->first();

        }

        if (! empty($applicationTiming->applications) and $applicationTiming->applications != null) {
            foreach ($applicationTiming->applications as $application) {
                if ($application->reserved == '1') {
                    continue;
                }
                $applications[] = $application->id;
            }
            Applications::whereIn('id', $applications)->delete();
        } else {
            ApplicationTiming::find($id)->delete();
        }

        return redirect()->back()
            ->with('success', 'Applications deleted!');
    }
}
