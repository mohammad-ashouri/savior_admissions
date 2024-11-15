<?php

namespace App\Http\Controllers;

use App\Charts\AbsenceInInterview;
use App\Charts\AcceptedStudentsByAcademicYear;
use App\Charts\AdmittedInterviews;
use App\Charts\AllRegisteredStudentsInLastAcademicYear;
use App\Charts\AllReservedApplications;
use App\Charts\AllStudentsPendingForUploadDocuments;
use App\Charts\RejectedInterviews;
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

    public function __construct() {
        $this->allRegisteredStudentsInLastAcademicYear = $this->registeredStudentsInLastAcademicYear($this->getActiveAcademicYears());
        $this->acceptedStudentNumberStatusByAcademicYear = $this->acceptedStudentNumberStatusByAcademicYear($this->getActiveAcademicYears());
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
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->orderBy('id', 'desc')->orderBy('student_id', 'asc')->get();
        }
        if ($me->hasRole('Super Admin')) {
            $allRegisteredStudentsInLastAcademicYear = $this->allRegisteredStudentsInLastAcademicYear;
            $acceptedStudentNumberStatusByAcademicYear = $this->acceptedStudentNumberStatusByAcademicYear;
//            $allReservedApplicationsInLastAcademicYear = $this->allReservedApplicationsInLastAcademicYear->build();
//            $allStudentsPendingForUploadDocument = $this->allStudentsPendingForUploadDocument->build();
//            $admittedInterviews = $this->admittedInterviews->build();
//            $rejectedInterviews = $this->rejectedInterviews->build();
//            $absenceInInterview = $this->absenceInInterview->build();

            return view('Dashboards.Main', compact(
                'me',
                'allRegisteredStudentsInLastAcademicYear',
                'acceptedStudentNumberStatusByAcademicYear',
//                'allReservedApplicationsInLastAcademicYear',
//                'allStudentsPendingForUploadDocument',
//                'admittedInterviews',
//                'rejectedInterviews',
//                'absenceInInterview',
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
            $applicationStatuses = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->whereIn('student_id', $myStudents)->orderByDesc('academic_year')->get();
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
