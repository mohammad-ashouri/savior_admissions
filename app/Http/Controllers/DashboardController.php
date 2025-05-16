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
        if (empty(auth()->user())) {
            redirect()->route('logout');
        }

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

        //Students
        $students = collect();
        if (auth()->user()->hasRole(['Parent'])) {
            $parentStudents = StudentInformation::whereGuardian(auth()->user()->id)
                ->with(['studentInfo', 'nationalityInfo', 'identificationTypeInfo', 'generalInformations'])
                ->orderBy('id', 'desc')->orderBy('student_id', 'asc')->get();
            $students = $students->merge($parentStudents);
        }

        if ($students->isEmpty()) {
            $students = collect();
        }


        //Applications
        $applicationStatuses = [];
        if (auth()->user()->hasRole(['Parent'])) {
            $myStudents = StudentInformation::whereGuardian(auth()->user()->id)->pluck('student_id')->toArray();
            $applicationStatuses = StudentApplianceStatus::with('studentInfo','academicYearInfo','levelInfo')
                ->whereIn('student_id', $myStudents)
                ->orderByDesc('academic_year')
                ->get();
        }


        if (empty($applicationStatuses)) {
            $applicationStatuses = [];
        }
        return view('Dashboards.Main', compact(
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
            'students', 'applicationStatuses'));
    }
}
