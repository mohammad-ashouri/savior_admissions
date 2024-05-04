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
        $studentAppliances = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->whereIn('academic_year', $academicYears)->where('documents_uploaded', '2')->where('interview_status', 'Admitted')->orderBy('documents_uploaded', 'asc')->paginate(30);

        return view('BranchInfo.Evidence.index', compact('studentAppliances'));
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
        $studentAppliance = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('evidences')->where('id', $appliance_id)->whereIn('academic_year', $academicYears)->where('documents_uploaded', '2')->where('interview_status', 'Admitted')->orderBy('documents_uploaded', 'asc')->first();
        if (empty($studentAppliance)) {
            abort(403);
        }

        $bloodGroups = BloodGroup::get();
        $guardianStudentRelationships = GuardianStudentRelationship::get();
        $countries = Country::orderBy('en_short_name', 'asc')->get();
        $nationalities = Country::orderBy('nationality', 'asc')->get();
        $studentInformation = StudentInformation::with('generalInformations')->where('student_id', $studentAppliance->student_id)->first();
        $this->logActivity(json_encode(['activity' => 'Getting Appliance Form', 'appliance_id' => $appliance_id]), request()->ip(), request()->userAgent());

        return view('Documents.UploadDocumentsParent.show', compact('studentAppliance', 'bloodGroups', 'guardianStudentRelationships', 'countries', 'nationalities', 'studentInformation'));
    }

    public function confirmEvidences(Request $request)
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
        $appliance_id = $request->appliance_id;
        $studentAppliance = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('evidences')->where('id', $appliance_id)->whereIn('academic_year', $academicYears)->where('documents_uploaded', '2')->where('interview_status', 'Admitted')->first();
        if (empty($studentAppliance)) {
            $this->logActivity(json_encode(['activity' => 'Failed To Confirm Evidences', 'values' => $request->all(), 'appliance_id' => $appliance_id]), request()->ip(), request()->userAgent());

            abort(403);
        }

        $studentInfo=StudentInformation::with('guradianInfo')->where('student_id', $studentAppliance->student_id)->first();
        $guardianMobile=$studentInfo->guradianInfo->mobile;

        switch (request('status')) {
            case 'Accept':
                $studentAppliance->documents_uploaded = 1;
                $studentAppliance->documents_uploaded_approval = 1;
                $studentAppliance->tuition_payment_status = 'Pending';
                $this->sendSMS($guardianMobile,"Your documents have been approved. Please pay the tuition within the next 72 hours.\nSavior Schools");
                break;
            case 'Reject':
                $studentAppliance->documents_uploaded = 3;
                $studentAppliance->documents_uploaded_approval = 2;
                $studentAppliance->description = 'Documents Rejected';
                $this->sendSMS($guardianMobile,"Your documents were rejected. To review and see the reason for rejection, please refer to your panel.\nSavior Schools");
                break;
            default:
                $this->logActivity(json_encode(['activity' => 'Failed To Confirm Evidences (Wrong Status)', 'values' => $request->all(), 'appliance_id' => $appliance_id]), request()->ip(), request()->userAgent());

                abort(403);
        }
        $studentAppliance->seconder_description = $request->seconder_description;
        $studentAppliance->documents_uploaded_seconder = $me->id;
        $studentAppliance->save();

        return redirect()->route('Evidences')
            ->with('success', 'Determining the status of the documents was done successfully');
    }
}
