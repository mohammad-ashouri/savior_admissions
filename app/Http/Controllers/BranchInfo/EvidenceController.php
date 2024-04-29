<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
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
            $filteredArray = $this->getFilteredAccessesP($myAllAccesses);

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
            $filteredArray = $this->getFilteredAccessesP($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }
        $studentAppliance = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('evidences')->where('id',$appliance_id)->whereIn('academic_year', $academicYears)->where('documents_uploaded', '2')->where('interview_status', 'Admitted')->orderBy('documents_uploaded','asc')->first();
        if (empty($studentAppliance)){
            abort(403);
        }
        return view('Documents.UploadDocumentsParent.show', compact('studentAppliance'));
    }
}
