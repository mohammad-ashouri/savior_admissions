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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvidenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:evidences-confirmation', ['only' => ['index']]);
    }

    public function index()
    {
        if (auth()->user()->hasRole('Super Admin')) {
            $academicYears = AcademicYear::pluck('id')->toArray();
        } elseif (auth()->user()->hasRole(['Admissions Officer'])) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }
        $studentAppliances = StudentApplianceStatus::with('studentInfo')
            ->with('academicYearInfo')
            ->whereIn('academic_year', $academicYears)
            ->whereDocumentsUploaded('2')
            ->where('tuition_payment_status', 'Paid')
//            ->whereInterviewStatus('Admitted')
            ->orderBy('documents_uploaded', 'asc')
            ->get();

        return view('BranchInfo.Evidence.index', compact('studentAppliances'));
    }

    public function show($appliance_id)
    {
        if (auth()->user()->hasRole('Super Admin')) {
            $academicYears = AcademicYear::pluck('id')->toArray();
        } elseif (auth()->user()->hasRole(['Admissions Officer'])) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }
        $studentAppliance = StudentApplianceStatus::with('studentInfo')
            ->with('academicYearInfo')
            ->with('evidences')
            ->whereId($appliance_id)
            ->whereIn('academic_year', $academicYears)
            ->where('tuition_payment_status', 'Paid')
//            ->whereInterviewStatus('Admitted')
            ->orderBy('documents_uploaded', 'asc')
            ->first();
        if (empty($studentAppliance)) {
            abort(403);
        }

        $bloodGroups = BloodGroup::get();
        $guardianStudentRelationships = GuardianStudentRelationship::get();
        $countries = Country::orderBy('en_short_name', 'asc')->get();
        $nationalities = Country::orderBy('nationality', 'asc')->get();
        $studentInformation = StudentInformation::with('generalInformations')->whereStudentId($studentAppliance->student_id)->first();

        return view('Documents.UploadDocumentsParent.show', compact('studentAppliance', 'bloodGroups', 'guardianStudentRelationships', 'countries', 'nationalities', 'studentInformation'));
    }

    public function confirmEvidences(Request $request)
    {
        if (auth()->user()->hasRole('Super Admin')) {
            $academicYears = AcademicYear::pluck('id')->toArray();
        } elseif (auth()->user()->hasRole(['Admissions Officer'])) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }
        $appliance_id = $request->appliance_id;
        $studentAppliance = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('evidences')->whereId($appliance_id)->whereIn('academic_year', $academicYears)
            ->where('tuition_payment_status', 'Paid')
            ->first();
        if (empty($studentAppliance)) {
            abort(403);
        }

        $studentInfo = StudentInformation::with('guardianInfo')->whereStudentId($studentAppliance->student_id)->first();
        $guardianMobile = $studentInfo->guardianInfo->mobile;

        switch (request('status')) {
            case 'Accept':
                $studentAppliance->interview_status = 'Admitted';
                $studentAppliance->documents_uploaded = 1;
                $studentAppliance->documents_uploaded_approval = 1;
                $studentAppliance->tuition_payment_status = 'Pending';
                $studentAppliance->date_of_document_approval = Carbon::now();
                $studentAppliance->description = null;
                $this->sendSMS($guardianMobile, "Your documents have been approved. Please pay the tuition within the next 72 hours.\nSavior Schools");
                break;
            case 'Reject':
                $studentAppliance->documents_uploaded = 3;
                $studentAppliance->documents_uploaded_approval = 2;
                $studentAppliance->description = 'Documents Rejected';
                $studentAppliance->date_of_document_approval = Carbon::now();
                $this->sendSMS($guardianMobile, "Your documents were rejected. To review and see the reason for rejection, please refer to your panel.\nSavior Schools");
                break;
            default:
                abort(403);
        }
        $studentAppliance->seconder_description = $request->description;
        $studentAppliance->documents_uploaded_seconder = auth()->user()->id;
        $studentAppliance->save();

        return redirect()->route('Evidences')
            ->with('success', 'Determining the status of the documents was done successfully');
    }

    public function extensionOfDocumentUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appliance_id' => 'required|integer|exists:student_appliance_statuses,id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $applianceId = $request->appliance_id;
        if (auth()->user()->hasRole('Super Admin')) {
            $appliance = StudentApplianceStatus::find($applianceId);
        } elseif (auth()->user()->hasRole(['Principal','Admissions Officer'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $appliance = StudentApplianceStatus::whereIn('academic_year', $academicYears)->whereId($applianceId)->first();
        }

        StudentApplianceStatus::find($applianceId)->update([
            'documents_uploaded' => 0,
            'documents_uploaded_approval' => null,
            'description' => 'The deadline for uploading documents has been extended',
        ]);

        $appliance = StudentApplianceStatus::find($applianceId);

        $studentInformation = StudentInformation::with('guardianInfo')->whereStudentId($appliance->student_id)->first();
        $guardianMobile = $studentInformation->guardianInfo->mobile;
        $message = "The deadline for uploading your documents has been extended. You have 72 hours to upload your documents.\nSavior Schools";
        $this->sendSMS($guardianMobile, $message);

        return redirect()->route('StudentStatus')
            ->with('success', 'The deadline for uploading documents for this appliance ID:'.$applianceId.' has been extended');
    }

    public function showEvidence($appliance_id)
    {
        if (auth()->user()->hasRole('Super Admin')) {
            $academicYears = AcademicYear::pluck('id')->toArray();
        } elseif (auth()->user()->hasRole(['Admissions Officer'])) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        }
        $studentAppliance = StudentApplianceStatus::with('studentInfo')
            ->with('academicYearInfo')
            ->with('evidences')
            ->whereId($appliance_id)
            ->whereIn('academic_year', $academicYears)
            ->where('documents_uploaded_approval', '=', '1')
            ->whereInterviewStatus('Admitted')
            ->orderBy('documents_uploaded', 'asc')
            ->first();
        if (empty($studentAppliance)) {
            abort(403);
        }

        $bloodGroups = BloodGroup::get();
        $guardianStudentRelationships = GuardianStudentRelationship::get();
        $countries = Country::orderBy('en_short_name', 'asc')->get();
        $nationalities = Country::orderBy('nationality', 'asc')->get();
        $studentInformation = StudentInformation::with('generalInformations')->whereStudentId($studentAppliance->student_id)->first();

        return view('Documents.UploadDocumentsParent.ShowUploadedEvidence', compact('studentAppliance', 'bloodGroups', 'guardianStudentRelationships', 'countries', 'nationalities', 'studentInformation'));
    }
}
