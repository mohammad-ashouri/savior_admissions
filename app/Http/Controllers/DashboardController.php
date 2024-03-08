<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        $myInfo = User::with('generalInformationInfo')->find(session('id'));

        //Students
        $students=[];
        if ($myInfo->hasRole('Parent(Father)') or $myInfo->hasRole('Parent(Mother)')) {
            $students = StudentInformation::where('guardian', session('id'))
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->orderBy('id', 'desc')->orderBy('student_id', 'asc')->get();
        } elseif ($myInfo->hasRole('Super Admin')) {
            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')
                ->where('tuition_payment_status', 'Paid')
                ->distinct('student_id')
                ->orderBy('id', 'desc')->orderBy('academic_year', 'desc')->take(5)->get();
        } elseif ($myInfo->hasRole('Principal') or $myInfo->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $myInfo->id)->first();
            if (empty($myAllAccesses)) {
                abort(403);
            }
            $principalAccess = explode('|', $myAllAccesses->principal);
            $admissionsOfficerAccess = explode('|', $myAllAccesses->admissions_officer);
            $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')
                ->whereIn('academic_year', $academicYears)
                ->where('tuition_payment_status', 'Paid')
                ->distinct('student_id')
                ->orderBy('id', 'desc')->orderBy('academic_year', 'desc')->take(5)->get();
        }

        if ($students->isEmpty()) {
            $students = [];
        }

        //Applications
        $applicationStatuses = [];
        if ($myInfo->hasRole('Parent(Father)') or $myInfo->hasRole('Parent(Mother)')) {
            $myStudents = StudentInformation::where('guardian', $myInfo->id)->pluck('student_id')->toArray();
            $applicationStatuses = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->whereIn('student_id', $myStudents)->orderByDesc('academic_year')->get();
        } elseif ($myInfo->hasRole('Super Admin')) {
            $applicationStatuses = ApplicationReservation::with('applicationInfo')->with('studentInfo')->with('reservatoreInfo')->paginate(30);
        } elseif ($myInfo->hasRole('Principal') or $myInfo->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $myInfo->id)->first();
            $principalAccess = explode('|', $myAllAccesses->principal);
            $admissionsOfficerAccess = explode('|', $myAllAccesses->admissions_officer);
            $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));

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

        return view('Dashboards.Main', compact('myInfo','students','applicationStatuses'));
    }
}
