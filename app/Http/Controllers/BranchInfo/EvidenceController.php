<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\BloodGroup;
use App\Models\Catalogs\GuardianStudentRelationship;
use App\Models\Country;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;

class EvidenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:evidences-confirmation', ['only' => ['index']]);
    }

    public function index()
    {
        $me = User::find(session('id'));
        if ($me->hasRole('Super Admin')) {
            $academicYears = AcademicYear::pluck('id')->toArray();
        } elseif ($me->hasRole('Admissions Officer')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }
        $studentAppliances = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->whereIn('academic_year', $academicYears)->where('documents_uploaded', '2')->where('interview_status', 'Admitted')->orderBy('documents_uploaded','asc')->paginate(30);

        return view('branchInfo.Evidence.index', compact('studentAppliances'));
    }

    public function show($appliance_id)
    {
        $me = User::find(session('id'));
        if ($me->hasRole('Super Admin')) {
            $academicYears = AcademicYear::pluck('id')->toArray();
        } elseif ($me->hasRole('Admissions Officer')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }
        $studentAppliance = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('evidences')->where('id',$appliance_id)->whereIn('academic_year', $academicYears)->where('documents_uploaded', '2')->where('interview_status', 'Admitted')->orderBy('documents_uploaded','asc')->first();
        if (empty($studentAppliance)){
            abort(403);
        }

        $bloodGroups = BloodGroup::get();
        $guardianStudentRelationships = GuardianStudentRelationship::get();
        $countries = Country::orderBy('en_short_name', 'asc')->get();
        $nationalities = Country::orderBy('nationality', 'asc')->get();
        $studentInformation = StudentInformation::with('generalInformations')->where('student_id', $studentAppliance->student_id)->first();

        return view('Documents.UploadDocumentsParent.show', compact('studentAppliance','bloodGroups','guardianStudentRelationships','countries','nationalities','studentInformation'));
    }
}
