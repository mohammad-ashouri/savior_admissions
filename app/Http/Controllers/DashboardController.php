<?php

namespace App\Http\Controllers;

use App\Http\Controllers\GeneralControllers\ChartController;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use App\Traits\ChartFunctions;

class DashboardController extends Controller
{
    use ChartFunctions;

    protected $allRegisteredStudentsInLastAcademicYear;
    protected $acceptedStudentNumberStatusByAcademicYear;
    protected $reservedApplicationsByAcademicYear;
    protected $admittedInterviews;
    protected $rejectedInterviews;
    protected $absenceInInterview;
    protected $interviewTypes;
    protected $paymentTypes;
    protected $tuitionPaidAcademicYear;
    protected $tuitionPaidPaymentType;
    protected $levels;
    protected $userRolesChart;

    public function __construct()
    {
        $this->allRegisteredStudentsInLastAcademicYear = $this->registeredStudentsInLastAcademicYear($this->getActiveAcademicYears());
        $this->acceptedStudentNumberStatusByAcademicYear = $this->acceptedStudentNumberStatusByAcademicYear($this->getActiveAcademicYears());
        $this->reservedApplicationsByAcademicYear = $this->reservedApplicationsByAcademicYear($this->getActiveAcademicYears());
        $this->absenceInInterview = $this->absenceInInterview($this->getActiveAcademicYears());
        $this->admittedInterviews = $this->admittedInterviews($this->getActiveAcademicYears());
        $this->rejectedInterviews = $this->rejectedInterviews($this->getActiveAcademicYears());
        $this->interviewTypes = $this->interviewTypes($this->getActiveAcademicYears());
        $this->paymentTypes = $this->paymentTypes($this->getActiveAcademicYears());
        $this->tuitionPaidAcademicYear = $this->tuitionPaidAcademicYear($this->getActiveAcademicYears());
        $this->tuitionPaidPaymentType = $this->tuitionPaidPaymentType($this->getActiveAcademicYears());
        $this->levels = $this->levels($this->getActiveAcademicYears());
        $this->userRolesChart = $this->userRolesChart();
    }

    public function index()
    {
        $me = User::with('generalInformationInfo')->find(auth()->user()->id);

        if (empty($me)) {
            redirect()->route('logout');
        }
        //Students
        $students = [];
        if ($me->hasRole('Parent')) {
            $students = StudentInformation::whereGuardian(auth()->user()->id)
                ->with(['studentInfo','nationalityInfo','identificationTypeInfo','generalInformations'])
                ->orderBy('id', 'desc')->orderBy('student_id', 'asc')->get();
        }
        if ($me->hasRole('Super Admin')) {
            $allRegisteredStudentsInLastAcademicYear = $this->allRegisteredStudentsInLastAcademicYear;
            $acceptedStudentNumberStatusByAcademicYear = $this->acceptedStudentNumberStatusByAcademicYear;
            $reservedApplicationsByAcademicYear = $this->reservedApplicationsByAcademicYear;
            $absenceInInterview = $this->absenceInInterview;
            $admittedInterviews = $this->admittedInterviews;
            $rejectedInterviews = $this->rejectedInterviews;
            $interviewTypes = $this->interviewTypes;
            $paymentTypes = $this->paymentTypes;
            $tuitionPaidPaymentType = $this->tuitionPaidPaymentType;
            $tuitionPaidAcademicYear = $this->tuitionPaidAcademicYear;
            $levels = $this->levels;
            $userRolesChart = $this->userRolesChart;

            return view('Dashboards.Main', compact(
                'me',
                'allRegisteredStudentsInLastAcademicYear',
                'acceptedStudentNumberStatusByAcademicYear',
                'reservedApplicationsByAcademicYear',
                'absenceInInterview',
                'admittedInterviews',
                'rejectedInterviews',
                'interviewTypes',
                'paymentTypes',
                'tuitionPaidAcademicYear',
                'tuitionPaidPaymentType',
                'levels',
                'userRolesChart',
            ));
        }
        if ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')
                ->whereIn('academic_year', $academicYears)
                ->whereTuitionPaymentStatus('Paid')
                ->distinct('student_id')
                ->orderBy('id', 'desc')->orderBy('academic_year', 'desc')->take(5)->get();
        }

        if (empty($students) or $students->isEmpty()) {
            $students = [];
        }

        //Applications
        $applicationStatuses = [];
        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::whereGuardian($me->id)->pluck('student_id')->toArray();
            $applicationStatuses = StudentApplianceStatus::with('studentInfo','academicYearInfo','levelInfo')
                ->whereIn('student_id', $myStudents)
                ->orderByDesc('academic_year')
                ->get();
        }
        if ($me->hasRole('Super Admin')) {
            $applicationStatuses = ApplicationReservation::with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->get();
        }
        if ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            // Finding application timings based on academic years
            $applicationTimings = ApplicationTiming::whereIn('academic_year', $academicYears)->pluck('id')->toArray();

            // Finding applications related to the application timings
            $applications = Applications::whereIn('application_timing_id', $applicationTimings)
                ->pluck('id')
                ->toArray();

            // Getting reservations of applications along with related information
            $applicationStatuses = ApplicationReservation::with('applicationInfo')
                ->with('studentInfo')
                ->with('reservatoreInfo')
                ->whereIn('application_id', $applications)
                ->get();
        }

        if (empty($applicationStatuses)) {
            $applicationStatuses = [];
        }

        return view('Dashboards.Main', compact('me', 'students', 'applicationStatuses'));
    }
}
