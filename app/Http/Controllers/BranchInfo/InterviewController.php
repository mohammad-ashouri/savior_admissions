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

    public function index()
    {
        $me = User::find(auth()->user()->id);
        $interviews = [];
        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $reservations = ApplicationReservation::whereIn('student_id', $myStudents)->pluck('application_id')->toArray();
            $interviews = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->with('interview')
                ->where('reserved', 1)
                ->whereIn('id', $reservations)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->paginate(150);
        } elseif ($me->hasRole('Super Admin')) {
            $interviews = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->with('interview')
                ->where('reserved', 1)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->paginate(100);
        } elseif ($me->hasRole('Financial Manager') or $me->hasRole('Principal')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $interviews = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->with('interview')
                ->where('reserved', 1)
                ->whereIn('application_timing_id', $applicationTimings)
                ->where('reserved', 1)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->paginate(150);

        } elseif ($me->hasRole('Interviewer')) {
            $interviews = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->with('interview')
                ->where('reserved', 1)
                ->where(function ($query) use ($me) {
                    $query->where('first_interviewer', $me->id)
                        ->orWhere('second_interviewer', $me->id);
                })
                ->orderBy('interviewed', 'desc') // Corrected column name
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->paginate(150);

        }

        if (empty($interviews) or $interviews->isEmpty()) {
            $interviews = [];
        }
        $this->logActivity(json_encode(['activity' => 'Getting Interviews']), request()->ip(), request()->userAgent());

        return view('BranchInfo.Interviews.index', compact('interviews'));

    }

    public function GetInterviewForm($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
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
        } elseif ($me->hasRole('Principal')) {
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

        } elseif ($me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesF($myAllAccesses);

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
            $studentApplianceStatus = StudentApplianceStatus::where('academic_year', $interview->applicationTimingInfo->academic_year)->where('student_id', $interview->reservationInfo->studentInfo->id)->orderByDesc('id')->first();

            switch ($studentApplianceStatus->interview_status) {
                case 'Accepted':
                case 'Rejected':
                    abort(403);
            }

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
                ->first();

            $studentApplianceStatus = StudentApplianceStatus::where('academic_year', $interview->applicationTimingInfo->academic_year)->where('student_id', $interview->reservationInfo->studentInfo->id)->orderByDesc('id')->first();

            switch ($studentApplianceStatus->interview_status) {
                case 'Accepted':
                case 'Rejected':
                    abort(403);
            }
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

    public function SetInterview(Request $request)
    {
        $me = User::find(auth()->user()->id);
        $application = [];

        if ($me->hasRole('Super Admin')) {
            $application = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->where('reserved', 1)
                ->where('id', $request->application_id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
        } elseif ($me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $application = Applications::with('applicationTimingInfo')
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
            $application = Applications::with('applicationTimingInfo')
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
        if (empty($application)) {
            abort(403);
        }
        if (isset($request->discount) and ! empty($request->discount)) {
            $discountsPercentage = Discount::join('discount_details', 'discounts.id', '=', 'discount_details.discount_id')
                ->where('discounts.academic_year', $application->applicationTimingInfo->academic_year)
                ->where('discount_details.status', 1)
                ->whereIn('discount_details.id', $request->discount)
                ->sum('discount_details.percentage');
            if ($discountsPercentage > 30) {
                redirect()->back()->withErrors(['The total percentage of selected discounts must be lower or equal to 30%.'])->withInput();
            }
        }

        $studentApplianceStatus = StudentApplianceStatus::where('academic_year', $application->applicationTimingInfo->academic_year)->where('student_id', $application->reservationInfo->studentInfo->id)->orderByDesc('id')->first();

        switch ($studentApplianceStatus->interview_status) {
            case 'Accepted':
            case 'Rejected':
                abort(403);
        }

        $interview = new Interview();
        $interview->application_id = $request->application_id;
        $interview->interview_form = json_encode($request->all(), true);
        $interview->interviewer = auth()->user()->id;

        //        $reservatoreMobile = $application->reservationInfo->reservatoreInfo->mobile;
        //        $this->sendSMS($reservatoreMobile, "The application for your student ($student) was rejected. Savior Schools");

        switch ($request->form_type) {
            case 'kg1':
            case 'l1':
                $interview->interview_type = 1;
                break;
            case 'kg2':
            case 'l2':
                $interview->interview_type = 2;
                break;
            case 'la':
            case 'kga':
                $interview->interview_type = 3;
                break;
        }

        $interview->interview_form = json_encode($request->all(), true);

        if ($interview->save()) {
            //Check if 3 interviews completed then make that to principal for confirmation
            $checkInterview1Completed = Interview::where('application_id', $request->application_id)
                ->where('interview_type', 1)
                ->exists();
            $checkInterview2Completed = Interview::where('application_id', $request->application_id)
                ->where('interview_type', 2)
                ->exists();
            $checkInterview3Completed = Interview::where('application_id', $request->application_id)
                ->where('interview_type', 3)
                ->exists();
            if ($checkInterview1Completed and $checkInterview2Completed and $checkInterview3Completed) {
                $studentApplianceStatus->interview_status = 'Pending For Principal Confirmation';
                $studentApplianceStatus->save();
            }
            $this->logActivity(json_encode(['activity' => 'Interview Set Successfully', 'interview_id' => $interview->id]), request()->ip(), request()->userAgent());

            return redirect()->route('interviews.index')
                ->with('success', 'The interview was successfully recorded');
        }
        $this->logActivity(json_encode(['activity' => 'Recording The Interview Failed', 'application_id' => $request->application_id]), request()->ip(), request()->userAgent());

        return redirect()->route('interviews.index')
            ->withErrors(['errors' => 'Recording the interview failed!']);
    }

    public function show($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
        $interview = [];
        if ($me->hasRole('Parent')) {
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
                ->where('id', $id)
                ->first();
        } elseif ($me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesF($myAllAccesses);

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
                ->where('id', $id)
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
        $this->logActivity(json_encode(['activity' => 'Getting Interview', 'interview_id' => $interview->id]), request()->ip(), request()->userAgent());

        return view('BranchInfo.Interviews.show', compact('interview', 'discounts'));
    }

    public function edit($id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
        $interview = [];
        if ($me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesF($myAllAccesses);

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
                ->where('id', $id)
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
                ->first();
        }
        if (empty($interview)) {
            abort(403);
        }

        $studentApplianceStatus = StudentApplianceStatus::where('academic_year', $interview->applicationTimingInfo->academic_year)->where('student_id', $interview->reservationInfo->studentInfo->id)->orderByDesc('id')->first();

        switch ($studentApplianceStatus->interview_status) {
            case 'Accepted':
            case 'Rejected':
                abort(403);
        }

        $discounts = Discount::with('allDiscounts')
            ->where('academic_year', $interview->applicationTimingInfo->academic_year)
            ->join('discount_details', 'discounts.id', '=', 'discount_details.discount_id')
            ->where('discount_details.status', 1)
            ->where('discount_details.interviewer_permission', 1)
            ->get();
        $this->logActivity(json_encode(['activity' => 'Getting Interview For Edit', 'interview_id' => $interview->id]), request()->ip(), request()->userAgent());

        return view('BranchInfo.Interviews.edit', compact('interview', 'discounts'));
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $me = User::find(auth()->user()->id);
        $application = [];
        if ($me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $application = Applications::with('applicationTimingInfo')
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
            $application = Applications::with('applicationTimingInfo')
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
        if (empty($application)) {
            abort(403);
        }
        if (isset($request->discount) and ! empty($request->discount)) {
            $discountsPercentage = Discount::join('discount_details', 'discounts.id', '=', 'discount_details.discount_id')
                ->where('discounts.academic_year', $application->applicationTimingInfo->academic_year)
                ->where('discount_details.status', 1)
                ->whereIn('discount_details.id', $request->discount)
                ->sum('discount_details.percentage');
            if ($discountsPercentage > 30) {
                redirect()->back()->withErrors(['The total percentage of selected discounts must be lower or equal to 30%.'])->withInput();
            }
        }
        $interview = Interview::find($request->interview_id);
        $interview->interview_form = json_encode($request->all(), true);
        $interview->interviewer = auth()->user()->id;

        $studentApplianceStatus = StudentApplianceStatus::where('academic_year', $application->applicationTimingInfo->academic_year)->where('student_id', $application->reservationInfo->studentInfo->id)->orderByDesc('id')->first();

        //Check if 3 interviews completed then make that to principal for confirmation
        $checkInterviewsCompleted = Interview::where('application_id', $request->application_id)
            ->whereIn('interview_type', [1, 2, 3])
            ->exists();
        if ($checkInterviewsCompleted) {
            $studentApplianceStatus->interview_status = 'Pending For Principal Confirmation';
            $studentApplianceStatus->save();
        }

        $studentApplianceStatus->save();
        if ($interview->save()) {
            $this->logActivity(json_encode(['activity' => 'Interview Updated Successfully', 'interview_id' => $interview->id, 'values' => $request->all()]), request()->ip(), request()->userAgent());

            return redirect()->route('interviews.index')
                ->with('success', 'The interview was successfully recorded');
        }
        $this->logActivity(json_encode(['activity' => 'Recording The Interview Failed', 'application_id' => $request->application_id]), request()->ip(), request()->userAgent());

        return redirect()->route('interviews.index')
            ->withErrors(['errors' => 'Recording the interview failed!']);
    }
}
