<?php

namespace App\Http\Controllers;

use App\Charts\AcceptedStudentsByAcademicYear;
use App\Charts\AllRegisteredApplications;
use App\Charts\AllRegisteredStudentsInLastAcademicYear;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;

class DashboardController extends Controller
{
    protected AcceptedStudentsByAcademicYear $acceptedStudentNumberStatusByAcademicYear;

    protected AllRegisteredStudentsInLastAcademicYear $allRegisteredStudentsInLastAcademicYear;

    protected AllRegisteredApplications $allRegisteredApplicationsInLastAcademicYear;

    public function __construct(AcceptedStudentsByAcademicYear          $acceptedStudentNumberStatusByAcademicYear,
                                AllRegisteredStudentsInLastAcademicYear $allRegisteredStudentsInLastAcademicYear,
                                AllRegisteredApplications               $allRegisteredApplicationsInLastAcademicYear,
    ) {
        $this->acceptedStudentNumberStatusByAcademicYear = $acceptedStudentNumberStatusByAcademicYear;
        $this->allRegisteredStudentsInLastAcademicYear = $allRegisteredStudentsInLastAcademicYear;
        $this->allRegisteredApplicationsInLastAcademicYear = $allRegisteredApplicationsInLastAcademicYear;
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::with('generalInformationInfo')->find(session('id'));

        //Students
        $students = [];
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $students = StudentInformation::where('guardian', session('id'))
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->orderBy('id', 'desc')->orderBy('student_id', 'asc')->get();
        } elseif ($me->hasRole('Super Admin')) {
            $allRegisteredStudentsInLastAcademicYear = $this->allRegisteredStudentsInLastAcademicYear->build();
            $acceptedStudentNumberStatusByAcademicYear = $this->acceptedStudentNumberStatusByAcademicYear->build();
            $allRegisteredApplicationsInLastAcademicYear = $this->allRegisteredApplicationsInLastAcademicYear->build();

            return view('Dashboards.Main', compact('me', 'acceptedStudentNumberStatusByAcademicYear', 'allRegisteredStudentsInLastAcademicYear', 'allRegisteredApplicationsInLastAcademicYear'));
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')
                ->whereIn('academic_year', $academicYears)
                ->where('tuition_payment_status', 'Paid')
                ->distinct('student_id')
                ->orderBy('id', 'desc')->orderBy('academic_year', 'desc')->take(5)->get();
        }

        if (empty($students) or $students->isEmpty()) {
            $students = [];
        }

        //Applications
        $applicationStatuses = [];
        if ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::where('guardian', $me->id)->pluck('student_id')->toArray();
            $applicationStatuses = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->whereIn('student_id', $myStudents)->orderByDesc('academic_year')->get();
        } elseif ($me->hasRole('Super Admin')) {
            $applicationStatuses = ApplicationReservation::with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->paginate(30);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

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
                ->paginate(30);
        }

        if (empty($applicationStatuses)) {
            $applicationStatuses = [];
        }

        return view('Dashboards.Main', compact('me', 'students', 'applicationStatuses'));
    }
}
