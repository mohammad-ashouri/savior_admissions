<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplianceConfirmationInformation;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\Interview;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Document;
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

    public function index(Request $request)
    {
        $me = User::find(auth()->user()->id);
        $interviews = [];
        $academicYears = [];

        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::whereGuardian($me->id)->pluck('student_id')->toArray();
            $reservations = ApplicationReservation::whereIn('student_id', $myStudents)->pluck('application_id')->toArray();
            $interviews = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->with('interview')
                ->whereReserved(1)
                ->whereIn('id', $reservations)
                ->orderBy('interviewed', 'asc') // Corrected column name
                ->orderBy('date', 'asc')
                ->orderBy('ends_to', 'asc')
                ->orderBy('start_from', 'asc')
                ->get();
        }
        if ($me->hasRole('Super Admin')) {
            $academicYears = AcademicYear::get();
        } elseif ($me->hasRole('Financial Manager') or $me->hasRole('Principal')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        } elseif ($me->hasRole('Interviewer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesI($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }

        if ($request->academic_year) {
            if ($me->hasRole('Super Admin')) {
                $academicYears = AcademicYear::get();

                $interviews = Applications::with('firstInterviewerInfo')
                    ->with('secondInterviewerInfo')
                    ->with('reservationInfo')
                    ->with('interview')
                    ->with(['applicationTimingInfo' => function ($query) use ($request) {
                        $query->where('academic_year', $request->academic_year);
                    }])
                    ->whereHas('applicationTimingInfo', function ($query) use ($request) {
                        $query->where('academic_year', $request->academic_year);
                    })
                    ->whereReserved(1)
                    ->orderBy('interviewed', 'asc') // Corrected column name
                    ->orderBy('date', 'asc')
                    ->orderBy('ends_to', 'asc')
                    ->orderBy('start_from', 'asc')
                    ->get();
            } elseif ($me->hasRole('Financial Manager') or $me->hasRole('Principal')) {
                // Convert accesses to arrays and remove duplicates
                $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
                $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

                // Finding academic years with status 1 in the specified schools
                $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

                // Finding application timings based on academic years
                $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

                // Finding applications related to the application timings
                $interviews = Applications::with('firstInterviewerInfo')
                    ->with('secondInterviewerInfo')
                    ->with('reservationInfo')
                    ->with('interview')
                    ->with(['applicationTimingInfo' => function ($query) use ($request) {
                        $query->where('academic_year', $request->academic_year);
                    }])
                    ->whereHas('applicationTimingInfo', function ($query) use ($request) {
                        $query->where('academic_year', $request->academic_year);
                    })
                    ->whereReserved(1)
                    ->whereIn('application_timing_id', $applicationTimings)
                    ->whereReserved(1)
                    ->orderBy('interviewed', 'asc') // Corrected column name
                    ->orderBy('date', 'asc')
                    ->orderBy('ends_to', 'asc')
                    ->orderBy('start_from', 'asc')
                    ->get();

            } elseif ($me->hasRole('Interviewer')) {
                // Convert accesses to arrays and remove duplicates
                $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
                $filteredArray = $this->getFilteredAccessesI($myAllAccesses);

                // Finding academic years with status 1 in the specified schools
                $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

                $interviews = Applications::with('firstInterviewerInfo')
                    ->with('secondInterviewerInfo')
                    ->with('reservationInfo')
                    ->with('interview')
                    ->with(['applicationTimingInfo' => function ($query) use ($request) {
                        $query->where('academic_year', $request->academic_year);
                    }])
                    ->whereHas('applicationTimingInfo', function ($query) use ($request) {
                        $query->where('academic_year', $request->academic_year);
                    })
                    ->whereReserved(1)
                    ->where(function ($query) use ($me) {
                        $query->where('first_interviewer', $me->id)
                            ->orWhere('second_interviewer', $me->id);
                    })
                    ->orderBy('interviewed', 'asc') // Corrected column name
                    ->orderBy('date', 'asc')
                    ->orderBy('ends_to', 'asc')
                    ->orderBy('start_from', 'asc')
                    ->get();

            }

        }
        if (empty($interviews) or $interviews->isEmpty()) {
            $interviews = [];
        }

        return view('BranchInfo.Interviews.index', compact('interviews', 'academicYears'));

    }

    public function GetInterviewForm($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
        $interview = [];
        if ($me->hasRole('Super Admin')) {
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->whereReserved(1)
                ->whereId($id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
        } elseif ($me->hasRole('Principal')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->whereReserved(1)
                ->whereIn('application_timing_id', $applicationTimings)
                ->whereId($id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();

        } elseif ($me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->whereIn('application_timing_id', $applicationTimings)
                ->whereReserved(1)
                ->whereId($id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
            $studentApplianceStatus = StudentApplianceStatus::whereAcademicYear($interview->applicationTimingInfo->academic_year)->whereStudentId($interview->reservationInfo->studentInfo->id)->orderByDesc('id')->first();

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
                ->whereReserved(1)
                ->where(function ($query) use ($me) {
                    $query->where('first_interviewer', $me->id)
                        ->orWhere('second_interviewer', $me->id);
                })
                ->where('Interviewed', 0)
                ->whereId($id)
                ->first();

            $studentApplianceStatus = StudentApplianceStatus::whereAcademicYear($interview->applicationTimingInfo->academic_year)->whereStudentId($interview->reservationInfo->studentInfo->id)->orderByDesc('id')->first();

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
            ->whereAcademicYear($interview->applicationTimingInfo->academic_year)
            ->join('discount_details', 'discounts.id', '=', 'discount_details.discount_id')
            ->where('discount_details.status', 1)
            ->where('discount_details.interviewer_permission', 1)
            ->get();

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
                ->whereReserved(1)
                ->whereId($request->application_id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
        } elseif ($me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $application = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->whereIn('application_timing_id', $applicationTimings)
                ->whereReserved(1)
                ->whereId($request->application_id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();

        } elseif ($me->hasRole('Interviewer')) {
            $application = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->whereReserved(1)
                ->where(function ($query) use ($me) {
                    $query->where('first_interviewer', $me->id)
                        ->orWhere('second_interviewer', $me->id);
                })
                ->where('Interviewed', 0)
                ->whereId($request->application_id)
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
            if ($discountsPercentage > 40) {
                redirect()->back()->withErrors(['The total percentage of selected discounts must be lower or equal to 40%.'])->withInput();
            }
        }

        $studentApplianceStatus = StudentApplianceStatus::whereAcademicYear($application->applicationTimingInfo->academic_year)->whereStudentId($application->reservationInfo->studentInfo->id)->orderByDesc('id')->first();

        switch ($studentApplianceStatus->interview_status) {
            case 'Accepted':
            case 'Rejected':
                abort(403);
        }

        $interview = new Interview;
        $interview->application_id = $request->application_id;
        $interview->interview_form = json_encode($request->all(), true);
        $interview->interviewer = auth()->user()->id;

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

                // Define a helper function for file upload and document creation
                function uploadAndCreateDocument($file, $description, $index, $studentApplianceStatus)
                {
                    $fileName = "document_file{$index}_".now()->format('Y-m-d_H-i-s');
                    $fileExtension = $file->getClientOriginalExtension();
                    $filePath = $file->storeAs(
                        "public/uploads/Documents/{$studentApplianceStatus->student_id}/Appliance_{$studentApplianceStatus->id}/Financial_Files",
                        "$fileName.$fileExtension"
                    );

                    // Return the file data and create document entries
                    Document::create([
                        'user_id' => auth()->user()->id,
                        'document_type_id' => 8,
                        'src' => $filePath,
                        'description' => $description,
                    ]);

                    Document::create([
                        'user_id' => $studentApplianceStatus->student_id,
                        'document_type_id' => 8,
                        'src' => $filePath,
                        'description' => $description,
                    ]);

                    return [
                        "src{$index}" => $filePath,
                        "description{$index}" => $description,
                    ];
                }

                // Initialize files array
                $files = [];

                // Loop through the possible files
                for ($i = 1; $i <= 5; $i++) {
                    $fileKey = "document_file{$i}";
                    $descriptionKey = "document_description{$i}";

                    if ($request->file($fileKey)) {
                        $files["file{$i}"] = uploadAndCreateDocument(
                            $request->file($fileKey),
                            $request->input($descriptionKey),
                            $i,
                            $studentApplianceStatus
                        );
                    }
                }

                $interview->files = json_encode($files, true);

                break;
        }

        $interview->interview_form = json_encode($request->all(), true);

        if ($interview->save()) {
            // Check if all interviews are completed, then update status to principal confirmation
            $completedInterviews = Interview::whereApplicationId($request->application_id)
                ->whereIn('interview_type', [1, 2, 3])
                ->pluck('interview_type')
                ->toArray();

            if (count($completedInterviews) === 3) {
                $studentApplianceStatus->interview_status = 'Pending For Principal Confirmation';
                $studentApplianceStatus->save();

                // Add to appliance confirmation information
                ApplianceConfirmationInformation::firstOrCreate([
                    'appliance_id' => $studentApplianceStatus->id,
                    'referrer' => auth()->user()->id,
                ]);
            }

            return redirect()->route('interviews.index')
                ->with('success', 'The interview was successfully recorded');
        }

        return redirect()->route('interviews.index')
            ->withErrors(['errors' => 'Recording the interview failed!']);
    }

    public function show($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
        $interview = [];
        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::whereGuardian($me->id)->pluck('student_id')->toArray();
            $reservations = ApplicationReservation::whereIn('student_id', $myStudents)->pluck('application_id')->toArray();
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->whereReserved(1)
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
                ->whereReserved(1)
                ->whereId($id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('interview')
                ->whereReserved(1)
                ->whereIn('application_timing_id', $applicationTimings)
                ->whereId($id)
                ->first();
        } elseif ($me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('interview')
                ->whereReserved(1)
                ->whereIn('application_timing_id', $applicationTimings)
                ->whereId($id)
                ->first();
        } elseif ($me->hasRole('Interviewer')) {
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->with('interview')
                ->whereReserved(1)
                ->where(function ($query) use ($me) {
                    $query->where('first_interviewer', $me->id)
                        ->orWhere('second_interviewer', $me->id);
                })
                ->whereId($id)
                ->first();
        }
        if (empty($interview)) {
            abort(403);
        }
        $discounts = Discount::with('allDiscounts')
            ->whereAcademicYear($interview->applicationTimingInfo->academic_year)
            ->join('discount_details', 'discounts.id', '=', 'discount_details.discount_id')
            ->where('discount_details.status', 1)
            ->where('discount_details.interviewer_permission', 1)
            ->get();

        return view('BranchInfo.Interviews.show', compact('interview', 'discounts'));
    }

    public function edit($id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
        $interview = [];
        if ($me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('interview')
                ->whereReserved(1)
                ->whereIn('application_timing_id', $applicationTimings)
                ->whereId($id)
                ->first();
        } elseif ($me->hasRole('Interviewer')) {
            $interview = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->with('interview')
                ->whereReserved(1)
                ->where(function ($query) use ($me) {
                    $query->where('first_interviewer', $me->id)
                        ->orWhere('second_interviewer', $me->id);
                })
                ->whereId($id)
                ->first();
        }
        if (empty($interview)) {
            abort(403);
        }

        $studentApplianceStatus = StudentApplianceStatus::whereAcademicYear($interview->applicationTimingInfo->academic_year)->whereStudentId($interview->reservationInfo->studentInfo->id)->orderByDesc('id')->first();

        switch ($studentApplianceStatus->interview_status) {
            case 'Accepted':
            case 'Rejected':
                abort(403);
        }

        $discounts = Discount::with('allDiscounts')
            ->whereAcademicYear($interview->applicationTimingInfo->academic_year)
            ->join('discount_details', 'discounts.id', '=', 'discount_details.discount_id')
            ->where('discount_details.status', 1)
            ->where('discount_details.interviewer_permission', 1)
            ->get();

        return view('BranchInfo.Interviews.edit', compact('interview', 'discounts'));
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $me = User::find(auth()->user()->id);
        $application = [];
        if ($me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $application = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->whereReserved(1)
                ->whereIn('application_timing_id', $applicationTimings)
                ->whereId($request->application_id)
                ->orderBy('date', 'desc')
                ->orderBy('ends_to', 'desc')
                ->orderBy('start_from', 'desc')
                ->first();

        } elseif ($me->hasRole('Interviewer')) {
            $application = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->whereReserved(1)
                ->where(function ($query) use ($me) {
                    $query->where('first_interviewer', $me->id)
                        ->orWhere('second_interviewer', $me->id);
                })
                ->where('Interviewed', 0)
                ->whereId($request->application_id)
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

        $studentApplianceStatus = StudentApplianceStatus::whereAcademicYear($application->applicationTimingInfo->academic_year)->whereStudentId($application->reservationInfo->studentInfo->id)->orderByDesc('id')->first();

        $interview = Interview::find($request->interview_id);
        $interview->interview_form = json_encode($request->all(), true);
        $files = [];
        $document_files = [
            'document_file1' => $request->document_description1,
            'document_file2' => $request->document_description2,
            'document_file3' => $request->document_description3,
            'document_file4' => $request->document_description4,
            'document_file5' => $request->document_description5,
        ];

        foreach ($document_files as $fileKey => $description) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $fileName = $fileKey.'_'.now()->format('Y-m-d_H-i-s');
                $fileExtension = $file->getClientOriginalExtension();
                $filePath = $file->storeAs(
                    'public/uploads/Documents/'.$studentApplianceStatus->student_id.'/Appliance_'.$studentApplianceStatus->id.'/Financial_Files',
                    "$fileName.$fileExtension"
                );

                $files[$fileKey] = [
                    'src' => $filePath,
                    'description' => $description,
                ];

                // Create the document for the current user and student
                Document::create([
                    'user_id' => auth()->user()->id,
                    'document_type_id' => 8,
                    'src' => $filePath,
                    'description' => $description,
                ]);

                Document::create([
                    'user_id' => $studentApplianceStatus->student_id,
                    'document_type_id' => 8,
                    'src' => $filePath,
                    'description' => $description,
                ]);
            }
        }

        $interview->files = json_encode($files, true);

        $interview->interviewer = auth()->user()->id;

        $studentApplianceStatus = StudentApplianceStatus::whereAcademicYear($application->applicationTimingInfo->academic_year)->whereStudentId($application->reservationInfo->studentInfo->id)->orderByDesc('id')->first();

        // Check if all 3 interviews are completed, then make it pending for principal confirmation
        $interviewTypes = [1, 2, 3];
        $allInterviewsCompleted = true;

        foreach ($interviewTypes as $interviewType) {
            if (! Interview::whereApplicationId($application->id)->where('interview_type', $interviewType)->exists()) {
                $allInterviewsCompleted = false;
                break;
            }
        }

        if ($allInterviewsCompleted) {
            $studentApplianceStatus->interview_status = 'Pending For Principal Confirmation';

            // Add to appliance confirmation information
            ApplianceConfirmationInformation::firstOrCreate([
                'appliance_id' => $studentApplianceStatus->id,
                'referrer' => auth()->user()->id,
            ]);
        }

        $studentApplianceStatus->save();

        if ($interview->save()) {
            return redirect()->route('interviews.index')
                ->with('success', 'The interview was successfully recorded');
        }

        return redirect()->route('interviews.index')
            ->withErrors(['errors' => 'Recording the interview failed!']);
    }

    public function submitAbsence(Request $request)
    {
        /* TODO:
        1- validation application id by academic year permission
        */
        $me = User::find(auth()->user()->id);
        $applicationId = $request->application_id;

        if ($me->hasRole('Super Admin')) {
            $application = Applications::where('reserved', 1)
                ->whereId($applicationId)
                ->first();
        } elseif ($me->hasRole('Interviewer')) {
            $application = Applications::where('reserved', 1)
                ->where('first_interviewer', $me->id)
                ->whereId($applicationId)
                ->first();
        }

        if (empty($application)) {
            return redirect()->route('interviews.index')
                ->withErrors(['errors' => 'Registration of absence in the interview encountered an error!']);

        }
        $applicationInfo = Applications::with('applicationTimingInfo')->with('reservationInfo')->find($applicationId);
        $applicationInfo->interviewed = 2;
        $applicationInfo->save();

        $studentStatus = StudentApplianceStatus::whereStudentId($applicationInfo->reservationInfo->student_id)->whereAcademicYear($applicationInfo->applicationTimingInfo->academic_year)->first();
        $studentStatus->interview_status = 'Absence';
        $studentStatus->save();

        if ($applicationInfo->save() and $studentStatus->save()) {
            return redirect()->route('interviews.index')
                ->with(['success' => 'Registration of absence in the interview was done successfully!']);
        }

        return redirect()->route('interviews.index')
            ->withErrors(['errors' => 'Registration of absence in the interview encountered an error!']);
    }

    public function searchInterviews(Request $request)
    {
        $this->validate($request, [
            'student_id' => 'nullable|exists:student_appliance_statuses,student_id',
            'student_first_name' => 'nullable|string',
            'student_last_name' => 'nullable|string',
            'application_id' => 'nullable|exists:applications,id',
        ]);
        $studentId = $request->student_id;
        $firstName = $request->student_first_name;
        $lastName = $request->student_last_name;
        $applicationId = $request->application_id;

        $me = User::find(auth()->user()->id);
        $interviews = [];
        if ($me->hasRole('Super Admin')) {
            $data = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->with('interview')
                ->where('applications.reserved', 1);
        } elseif ($me->hasRole('Financial Manager') or $me->hasRole('Principal')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $data = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->with('interview')
                ->whereIn('application_timing_id', $applicationTimings)
                ->where('applications.reserved', 1);
        } elseif ($me->hasRole('Interviewer')) {
            $data = Applications::with('applicationTimingInfo')
                ->with('firstInterviewerInfo')
                ->with('secondInterviewerInfo')
                ->with('reservationInfo')
                ->with('interview')
                ->where(function ($query) use ($me) {
                    $query->where('first_interviewer', $me->id)
                        ->orWhere('second_interviewer', $me->id);
                })
                ->where('applications.reserved', 1);
        }

        if (! empty($studentId)) {
            $data->whereHas('reservationInfo', function ($query) use ($studentId) {
                $query->whereStudentId($studentId);
            });
        }
        if (! empty($applicationId)) {
            $data->whereId($applicationId);
        }
        if (! empty($firstName)) {
            $data->whereHas('reservationInfo', function ($query) use ($firstName) {
                $query->whereHas('studentInfo', function ($query) use ($firstName) {
                    $query->whereHas('generalInformationInfo', function ($query) use ($firstName) {
                        $query->where('first_name_en', 'like', "%$firstName%");
                    });
                });
            });
        }
        if (! empty($lastName)) {
            $data->whereHas('reservationInfo', function ($query) use ($lastName) {
                $query->whereHas('studentInfo', function ($query) use ($lastName) {
                    $query->whereHas('generalInformationInfo', function ($query) use ($lastName) {
                        $query->where('last_name_en', 'like', "%$lastName%");
                    });
                });
            });
        }
        $interviews = $data->orderBy('applications.interviewed', 'asc') // Corrected column name
            ->orderBy('applications.date', 'asc')
            ->orderBy('applications.ends_to', 'asc')
            ->orderBy('applications.start_from', 'asc')
            ->get();
        if (empty($interviews) or $interviews->isEmpty()) {
            $interviews = [];
        }

        return view('BranchInfo.Interviews.index', compact('interviews'));

    }
}
