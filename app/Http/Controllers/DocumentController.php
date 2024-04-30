<?php

namespace App\Http\Controllers;

use App\Models\Branch\Evidence;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\BloodGroup;
use App\Models\Catalogs\DocumentType;
use App\Models\Catalogs\GuardianStudentRelationship;
use App\Models\Country;
use App\Models\Document;
use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:document-list', ['only' => ['index', 'showUserDocuments']]);
        $this->middleware('permission:document-create', ['only' => ['createDocument', 'createDocumentForUser']]);
        $this->middleware('permission:document-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:document-delete', ['only' => ['destroy']]);
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $documentTypes = DocumentType::where('status', '1')->orderBy('name')->get();
        $myDocuments = Document::with('documentType')->where('user_id', session('id'))->orderBy('id', 'desc')->get();
        $myDocumentTypes = Document::with('documentType')->where('user_id', session('id'))->pluck('document_type_id')->all();
        $myDocumentTypes = array_unique($myDocumentTypes);

        return view('Documents.index', compact('documentTypes', 'myDocuments', 'myDocumentTypes'));
    }

    public function createDocument(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'document_type' => 'exists:document_types,id',
            'document_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
        ]);
        $path = $request->file('document_file')->store('public/uploads/Documents/'.session('id'));
        $document = new Document();
        $document->user_id = session('id');
        $document->document_type_id = $request->document_type;
        $document->src = $path;
        $document->save();
        $this->logActivity(json_encode(['activity' => 'User Document Added', 'document id' => $document->id, 'user_id' => $document->user_id]), request()->ip(), request()->userAgent());

        return response()->json(['success' => 'Document added!'], 200);
    }

    public function createDocumentForUser(Request $request, $user_id): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'document_type' => 'exists:document_types,id',
            'document_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'user_id' => Rule::exists('users', 'id')->where(function ($query) use ($user_id) {
                $query->where('id', (int) $user_id);
            }),
        ]);
        $path = $request->file('document_file')->store('public/uploads/Documents/'.session('id'));
        $document = new Document();
        $document->user_id = $user_id;
        $document->document_type_id = $request->document_type;
        $document->src = $path;
        $document->save();
        $this->logActivity(json_encode(['activity' => 'User Document Added', 'document id' => $document->id, 'user_id' => $document->user_id]), request()->ip(), request()->userAgent());

        return response()->json(['success' => 'Document added!'], 200);
    }

    public function showUserDocuments($user_id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $documentTypes = DocumentType::orderBy('name', 'asc')->get();
        $myDocuments = Document::with('documentType')->where('user_id', $user_id)->orderBy('id', 'desc')->get();
        $documentOwner = User::find($user_id);
        $myDocumentTypes = Document::with('documentType')->where('user_id', $user_id)->pluck('document_type_id')->all();

        return view('Documents.index', compact('documentTypes', 'myDocuments', 'myDocumentTypes', 'documentOwner', 'user_id'));

    }

    public function uploadStudentDocumentByParent($student_id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $studentInformation = StudentInformation::with('generalInformations')->where('student_id', $student_id)->where('guardian', session('id'))->first();
        if (empty($studentInformation)) {
            abort(403);
        }
        $checkStudentApplianceStatus = StudentApplianceStatus::where('student_id', $student_id)->where('documents_uploaded', 0)->first();
        if (empty($checkStudentApplianceStatus)) {
            return redirect()->back()->withErrors('Student documents are uploaded or under review');
        }

        $bloodGroups = BloodGroup::get();
        $guardianStudentRelationships = GuardianStudentRelationship::get();
        $countries = Country::orderBy('en_short_name', 'asc')->get();
        $nationalities = Country::orderBy('nationality', 'asc')->get();

        return view('Documents.UploadDocumentsParent.create', compact('studentInformation', 'bloodGroups', 'guardianStudentRelationships', 'countries', 'nationalities'));

    }

    public function uploadStudentDocuments(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'blood_group' => 'required|exists:blood_groups,id',
            'relationship' => 'required|exists:guardian_student_relationships,id',
            'marital_status' => 'required|in:Married,Divorced',
            'father_name' => 'required|string',
            'father_family' => 'required|string',
            'father_mobile' => 'required',
            'father_email' => 'email',
            'father_occupation' => 'required|string',
            'father_qualification' => 'required|string',
            'father_passport_number' => 'required|string',
            'father_nationality' => 'required|exists:countries,id',
            'mother_name' => 'required|string',
            'mother_family' => 'required|string',
            'mother_mobile' => 'required',
            'mother_email' => 'email',
            'mother_occupation' => 'required|string',
            'mother_qualification' => 'required|string',
            'mother_passport_number' => 'required|string',
            'mother_nationality' => 'required|exists:countries,id',
            'previous_school_name' => 'string',
            'previous_school_country' => 'exists:countries,id',
            'student_skills' => 'string',
            'miscellaneous' => 'string',
            'student_passport_number' => 'required|string',
            'passport_expiry_date' => 'required|date',
            'student_iranian_visa' => 'required|string',
            'iranian_residence_expiry' => 'required|date',
            'student_iranian_faragir_code' => 'required|string',
            'student_iranian_sanad_code' => 'required|string',
            'student_id' => 'required|exists:user,id',
            'father_passport_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'mother_passport_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'student_passport_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'latest_report_card' => 'mimes:png,jpg,jpeg,pdf,bmp|max:2048',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Upload Student Documents Failed', 'errors' => json_encode($validator), 'values' => $request->all()]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $studentInformation = StudentInformation::where('student_id', $request->student_id)->where('guardian', session('id'))->first();
        if (empty($studentInformation)) {
            $this->logActivity(json_encode(['activity' => 'Access To Upload Student Documents Failed','errors'=>'Student information is wrong', 'values' => $request->all()]), request()->ip(), request()->userAgent());

            abort(403);
        }
        $checkStudentApplianceStatus = StudentApplianceStatus::where('student_id', $request->student_id)->where('documents_uploaded', 0)->latest()->first();
        if (empty($checkStudentApplianceStatus)) {
            $this->logActivity(json_encode(['activity' => 'Access To Upload Student Documents Failed','errors'=>'Student appliance status is wrong', 'values' => $request->all()]), request()->ip(), request()->userAgent());

            abort(403);
        }

        $fatherPassportFileName = 'FatherPassportScan_'.now()->format('Y-m-d_H-i-s');
        $fatherPassportFile = $request->file('father_passport_file')->storeAs(
            'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
            "$fatherPassportFileName.jpg"
        );

        $motherPassportFileName = 'MotherPassportScan_'.now()->format('Y-m-d_H-i-s');
        $motherPassportFileName = $request->file('mother_passport_file')->storeAs(
            'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
            "$motherPassportFileName.jpg"
        );

        $studentPassportFileName = 'StudentPassportFile_'.now()->format('Y-m-d_H-i-s');
        $studentPassportFileName = $request->file('student_passport_file')->storeAs(
            'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
            "$studentPassportFileName.jpg"
        );

        $latestReportCard_FileName = '';
        if ($request->hasFile('latest_report_card')) {
            $latestReportCard_FileName = 'LatestReportCard_'.now()->format('Y-m-d_H-i-s');
            $latestReportCard_FileName = $request->file('latest_report_card')->storeAs(
                'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
                "$latestReportCard_FileName.jpg"
            );
        }

        $files = json_encode(
            [
                'father_passport_file' => $fatherPassportFile,
                'mother_passport_file' => $motherPassportFileName,
                'latest_report_card' => $latestReportCard_FileName,
                'student_passport_file' => $studentPassportFileName,
            ], true);

        $evidences = new Evidence();
        $evidences->appliance_id = $checkStudentApplianceStatus->id;
        $evidences->informations = json_encode($request->all(), true);
        $evidences->files = $files;
        $evidences->save();

        $studentAppliance = StudentApplianceStatus::where('student_id', $request->student_id)->first();
        $studentAppliance->documents_uploaded = 2;
        $studentAppliance->save();

        $this->sendSMS($studentInformation->guradianInfo->mobile, "Documents uploaded successfully. Please wait for the confirmation of the documents sent.\nSavior Schools");

        return redirect()->route('dashboard')->with('success', 'Documents Uploaded Successfully!');
    }

    public function editUploadedEvidences($student_id)
    {
        $studentInformation = StudentInformation::with('generalInformations')->where('student_id', $student_id)->where('guardian', session('id'))->first();
        if (empty($studentInformation)) {
            abort(403);
        }
        $checkStudentApplianceStatus = StudentApplianceStatus::where('student_id', $student_id)->where('documents_uploaded', 2)->where('documents_uploaded_approval', 2)->first();
        if (empty($checkStudentApplianceStatus)) {
            return redirect()->back()->withErrors('Student documents are uploaded or under review');
        }

        $studentAppliance=StudentApplianceStatus::with('evidences')->where('student_id',$student_id)->latest()->first();

        $bloodGroups = BloodGroup::get();
        $guardianStudentRelationships = GuardianStudentRelationship::get();
        $countries = Country::orderBy('en_short_name', 'asc')->get();
        $nationalities = Country::orderBy('nationality', 'asc')->get();

        return view('Documents.UploadDocumentsParent.edit',compact('studentInformation','studentAppliance','checkStudentApplianceStatus','bloodGroups','guardianStudentRelationships','countries','nationalities'));

    }
}
