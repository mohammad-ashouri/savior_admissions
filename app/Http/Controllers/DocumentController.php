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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:document-list', ['only' => ['index', 'showUserDocuments']]);
        $this->middleware('permission:document-create', ['only' => ['createDocument', 'createDocumentForUser']]);
        $this->middleware('permission:document-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:document-delete', ['only' => ['deleteUserDocument']]);
        ini_set('post_max_size', '8M');
        ini_set('upload_max_filesize', '8M');
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $documentTypes = DocumentType::whereStatus('1')->orderBy('name')->get();
        $myDocuments = Document::with('documentType')->whereUserId(auth()->user()->id)->orderBy('id', 'desc')->get();
        $myDocumentTypes = Document::with('documentType')->whereUserId(auth()->user()->id)->pluck('document_type_id')->all();
        $myDocumentTypes = array_unique($myDocumentTypes);

        return view('Documents.index', compact('documentTypes', 'myDocuments', 'myDocumentTypes'));
    }

    public function createDocument(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'document_type' => 'exists:document_types,id',
            'document_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
        ]);
        $path = $request->file('document_file')->store('public/uploads/Documents/'.auth()->user()->id);
        $document = new Document;
        $document->user_id = auth()->user()->id;
        $document->document_type_id = $request->document_type;
        $document->src = $path;
        $document->save();

        return response()->json(['success' => 'Document added!'], 200);
    }

    public function createDocumentForUser(Request $request, $user_id): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'document_type' => 'exists:document_types,id',
            'document_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'user_id' => Rule::exists('users', 'id')->where(function ($query) use ($user_id) {
                $query->whereId((int) $user_id);
            }),
        ]);
        $path = $request->file('document_file')->store('public/uploads/Documents/'.auth()->user()->id);
        $document = new Document;
        $document->user_id = $user_id;
        $document->document_type_id = $request->document_type;
        $document->src = $path;
        $document->save();

        return response()->json(['success' => 'Document added!'], 200);
    }

    public function showUserDocuments($user_id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $documentTypes = DocumentType::orderBy('name', 'asc')->get();
        $myDocuments = Document::with('documentType')->whereUserId($user_id)->orderBy('id', 'desc')->get();
        $documentOwner = User::find($user_id);
        $myDocumentTypes = Document::with('documentType')->whereUserId($user_id)->pluck('document_type_id')->all();

        return view('Documents.index', compact('documentTypes', 'myDocuments', 'myDocumentTypes', 'documentOwner', 'user_id'));

    }

    public function uploadStudentDocumentByParent($student_id): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $studentInformation = StudentInformation::with('generalInformations')->whereStudentId($student_id)->whereGuardian(auth()->user()->id)->first();
        if (empty($studentInformation)) {
            abort(403);
        }
        $checkStudentApplianceStatus = StudentApplianceStatus::whereStudentId($student_id)->whereDocumentsUploaded(0)->first();
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
            'father_email' => 'nullable|email',
            'father_occupation' => 'required|string',
            'father_qualification' => 'required|string',
            'father_passport_number' => 'required|string',
            'father_nationality' => 'required|exists:countries,id',
            'mother_name' => 'required|string',
            'mother_family' => 'required|string',
            'mother_mobile' => 'required',
            'mother_email' => 'nullable|email',
            'mother_occupation' => 'required|string',
            'mother_qualification' => 'required|string',
            'mother_passport_number' => 'required|string',
            'mother_nationality' => 'required|exists:countries,id',
            'previous_school_name' => 'nullable|string',
            'previous_school_country' => 'nullable|exists:countries,id',
            'student_skills' => 'nullable|string',
            'miscellaneous' => 'nullable|string',
            'student_passport_number' => 'required|string',
            'passport_expiry_date' => 'required|date',
            'student_iranian_visa' => 'required|string',
            'iranian_residence_expiry' => 'required|date',
            'student_iranian_faragir_code' => 'required|string',
            'student_iranian_sanad_code' => 'required|string',
            'student_id' => 'required|exists:users,id',
            'father_passport_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'mother_passport_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'student_passport_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'latest_report_card' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'residence_document_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $studentInformation = StudentInformation::whereStudentId($request->student_id)->whereGuardian(auth()->user()->id)->first();
        if (empty($studentInformation)) {
            abort(403);
        }
        $checkStudentApplianceStatus = StudentApplianceStatus::whereStudentId($request->student_id)->whereDocumentsUploaded(0)->latest()->first();
        if (empty($checkStudentApplianceStatus)) {
            abort(403);
        }

        $fatherPassportFileName = 'FatherPassportScan_'.now()->format('Y-m-d_H-i-s');
        $fatherPassportFileExtension = $request->file('father_passport_file')->getClientOriginalExtension();
        $fatherPassportFile = $request->file('father_passport_file')->storeAs(
            'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
            "$fatherPassportFileName.$fatherPassportFileExtension"
        );

        $document = new Document;
        $document->user_id = auth()->user()->id;
        $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
        $document->src = $fatherPassportFile;
        $document->save();

        $document = new Document;
        $document->user_id = $checkStudentApplianceStatus->student_id;
        $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
        $document->src = $fatherPassportFile;
        $document->save();

        $motherPassportFileName = 'MotherPassportScan_'.now()->format('Y-m-d_H-i-s');
        $motherPassportFileExtension = $request->file('mother_passport_file')->getClientOriginalExtension();
        $motherPassportFileName = $request->file('mother_passport_file')->storeAs(
            'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
            "$motherPassportFileName.$motherPassportFileExtension"
        );

        $document = new Document;
        $document->user_id = auth()->user()->id;
        $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
        $document->src = $motherPassportFileName;
        $document->save();

        $document = new Document;
        $document->user_id = $checkStudentApplianceStatus->student_id;
        $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
        $document->src = $motherPassportFileName;
        $document->save();

        $studentPassportFileName = 'StudentPassportFile_'.now()->format('Y-m-d_H-i-s');
        $studentPassportFileExtension = $request->file('student_passport_file')->getClientOriginalExtension();
        $studentPassportFileName = $request->file('student_passport_file')->storeAs(
            'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
            "$studentPassportFileName.$studentPassportFileExtension"
        );

        $document = new Document;
        $document->user_id = auth()->user()->id;
        $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
        $document->src = $studentPassportFileName;
        $document->save();

        $document = new Document;
        $document->user_id = $checkStudentApplianceStatus->student_id;
        $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
        $document->src = $studentPassportFileName;
        $document->save();

        $latestReportCard_FileName = '';
        if ($request->hasFile('latest_report_card')) {
            $latestReportCard_FileName = 'LatestReportCard_'.now()->format('Y-m-d_H-i-s');
            $latestReportCard_FileExtension = $request->file('latest_report_card')->getClientOriginalExtension();
            $latestReportCard_FileName = $request->file('latest_report_card')->storeAs(
                'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
                "$latestReportCard_FileName.$latestReportCard_FileExtension"
            );
            $document = new Document;
            $document->user_id = auth()->user()->id;
            $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
            $document->src = $latestReportCard_FileName;
            $document->save();

            $document = new Document;
            $document->user_id = $checkStudentApplianceStatus->student_id;
            $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
            $document->src = $latestReportCard_FileName;
            $document->save();
        }

        $residenceDocumentFile_FileName = '';
        if ($request->hasFile('residence_document_file')) {
            $residenceDocumentFile_FileName = 'LatestReportCard_'.now()->format('Y-m-d_H-i-s');
            $residenceDocument_FileExtension = $request->file('residence_document_file')->getClientOriginalExtension();
            $residenceDocumentFile_FileName = $request->file('residence_document_file')->storeAs(
                'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
                "$residenceDocumentFile_FileName.$residenceDocument_FileExtension"
            );
            $document = new Document;
            $document->user_id = auth()->user()->id;
            $document->document_type_id = DocumentType::whereName('Residence Document')->first()->id;
            $document->src = $residenceDocumentFile_FileName;
            $document->save();

            $document = new Document;
            $document->user_id = $checkStudentApplianceStatus->student_id;
            $document->document_type_id = DocumentType::whereName('Residence Document')->first()->id;
            $document->src = $residenceDocumentFile_FileName;
            $document->save();
        }

        $files = json_encode(
            [
                'father_passport_file' => $fatherPassportFile,
                'mother_passport_file' => $motherPassportFileName,
                'latest_report_card' => $latestReportCard_FileName,
                'student_passport_file' => $studentPassportFileName,
                'residence_document_file' => $residenceDocumentFile_FileName,
            ], true);

        $evidences = new Evidence;
        $evidences->appliance_id = $checkStudentApplianceStatus->id;
        $evidences->informations = json_encode($request->all(), true);
        $evidences->files = $files;
        $evidences->save();

        $studentAppliance = StudentApplianceStatus::whereStudentId($request->student_id)->first();
        $studentAppliance->documents_uploaded = 2;
        $studentAppliance->description = null;
        $studentAppliance->save();

        $this->sendSMS($studentInformation->guardianInfo->mobile, "Documents uploaded successfully. Please wait for the confirmation of the documents sent.\nSavior Schools");

        return redirect()->route('dashboard')->with('success', 'Documents Uploaded Successfully!');
    }

    public function editUploadedEvidences($student_id)
    {
        $studentInformation = StudentInformation::with('generalInformations')->whereStudentId($student_id)->whereGuardian(auth()->user()->id)->first();
        if (empty($studentInformation)) {
            abort(403);
        }
        $checkStudentApplianceStatus = StudentApplianceStatus::whereStudentId($student_id)->whereDocumentsUploaded(3)->where('documents_uploaded_approval', 2)->first();
        if (empty($checkStudentApplianceStatus)) {
            return redirect()->back()->withErrors('Student documents are uploaded or under review');
        }

        $studentAppliance = StudentApplianceStatus::with('evidences')->whereStudentId($student_id)->latest()->first();

        $bloodGroups = BloodGroup::get();
        $guardianStudentRelationships = GuardianStudentRelationship::get();
        $countries = Country::orderBy('en_short_name', 'asc')->get();
        $nationalities = Country::orderBy('nationality', 'asc')->get();

        return view('Documents.UploadDocumentsParent.edit', compact('studentInformation', 'studentAppliance', 'checkStudentApplianceStatus', 'bloodGroups', 'guardianStudentRelationships', 'countries', 'nationalities'));

    }

    public function updateStudentDocuments(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'blood_group' => 'required|exists:blood_groups,id',
            'relationship' => 'required|exists:guardian_student_relationships,id',
            'marital_status' => 'required|in:Married,Divorced',
            'father_name' => 'required|string',
            'father_family' => 'required|string',
            'father_mobile' => 'required',
            'father_email' => 'nullable|email',
            'father_occupation' => 'required|string',
            'father_qualification' => 'required|string',
            'father_passport_number' => 'required|string',
            'father_nationality' => 'required|exists:countries,id',
            'mother_name' => 'required|string',
            'mother_family' => 'required|string',
            'mother_mobile' => 'required',
            'mother_email' => 'nullable|email',
            'mother_occupation' => 'required|string',
            'mother_qualification' => 'required|string',
            'mother_passport_number' => 'required|string',
            'mother_nationality' => 'required|exists:countries,id',
            'previous_school_name' => 'nullable|string',
            'previous_school_country' => 'nullable|exists:countries,id',
            'student_skills' => 'nullable|string',
            'miscellaneous' => 'nullable|string',
            'student_passport_number' => 'required|string',
            'passport_expiry_date' => 'required|date',
            'student_iranian_visa' => 'required|string',
            'iranian_residence_expiry' => 'required|date',
            'student_iranian_faragir_code' => 'required|string',
            'student_iranian_sanad_code' => 'required|string',
            'student_id' => 'required|exists:users,id',
            'father_passport_file' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'mother_passport_file' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'student_passport_file' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'latest_report_card' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $studentInformation = StudentInformation::whereStudentId($request->student_id)->whereGuardian(auth()->user()->id)->first();
        if (empty($studentInformation)) {
            abort(403);
        }
        $checkStudentApplianceStatus = StudentApplianceStatus::with('evidences')->whereStudentId($request->student_id)->whereIn('academic_year', $this->getActiveAcademicYears())->whereDocumentsUploaded(3)->latest()->first();
        if (empty($checkStudentApplianceStatus)) {
            abort(403);
        }

        $evidences = Evidence::find($checkStudentApplianceStatus->evidences->id);
        $files = json_decode($checkStudentApplianceStatus->evidences->files, true);

        if ($request->hasFile('father_passport_file')) {
            $fatherPassportFileName = 'FatherPassportScan_'.now()->format('Y-m-d_H-i-s');
            $fatherPassportFileExtension = $request->file('father_passport_file')->getClientOriginalExtension();
            $fatherPassportFile = $request->file('father_passport_file')->storeAs(
                'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
                "$fatherPassportFileName.$fatherPassportFileExtension"
            );
            $document = new Document;
            $document->user_id = auth()->user()->id;
            $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
            $document->src = $fatherPassportFile;
            $document->save();

            $document = new Document;
            $document->user_id = $checkStudentApplianceStatus->student_id;
            $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
            $document->src = $fatherPassportFile;
            $document->save();
        } else {
            $fatherPassportFile = $files['father_passport_file'];
        }

        if ($request->hasFile('mother_passport_file')) {
            $motherPassportFileName = 'MotherPassportScan_'.now()->format('Y-m-d_H-i-s');
            $motherPassportFileExtension = $request->file('mother_passport_file')->getClientOriginalExtension();
            $motherPassportFileName = $request->file('mother_passport_file')->storeAs(
                'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
                "$motherPassportFileName.$motherPassportFileExtension"
            );

            $document = new Document;
            $document->user_id = auth()->user()->id;
            $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
            $document->src = $motherPassportFileName;
            $document->save();

            $document = new Document;
            $document->user_id = $checkStudentApplianceStatus->student_id;
            $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
            $document->src = $motherPassportFileName;
            $document->save();
        } else {
            $motherPassportFileName = $files['mother_passport_file'];
        }

        if ($request->hasFile('student_passport_file')) {
            $studentPassportFileName = 'StudentPassportFile_'.now()->format('Y-m-d_H-i-s');
            $studentPassportFileExtension = $request->file('student_passport_file')->getClientOriginalExtension();
            $studentPassportFileName = $request->file('student_passport_file')->storeAs(
                'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
                "$studentPassportFileName.$studentPassportFileExtension"
            );

            $document = new Document;
            $document->user_id = auth()->user()->id;
            $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
            $document->src = $studentPassportFileName;
            $document->save();

            $document = new Document;
            $document->user_id = $checkStudentApplianceStatus->student_id;
            $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
            $document->src = $studentPassportFileName;
            $document->save();
        } else {
            $studentPassportFileName = $files['student_passport_file'];
        }

        $latestReportCard_FileName = '';
        if ($request->hasFile('latest_report_card')) {
            $latestReportCard_FileName = 'LatestReportCard_'.now()->format('Y-m-d_H-i-s');
            $latestReportCardFileExtension = $request->file('latest_report_card')->getClientOriginalExtension();
            $latestReportCard_FileName = $request->file('latest_report_card')->storeAs(
                'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
                "$latestReportCard_FileName.$latestReportCardFileExtension"
            );
            $document = new Document;
            $document->user_id = auth()->user()->id;
            $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
            $document->src = $latestReportCard_FileName;
            $document->save();

            $document = new Document;
            $document->user_id = $checkStudentApplianceStatus->student_id;
            $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
            $document->src = $latestReportCard_FileName;
            $document->save();
        } else {
            $latestReportCard_FileName = $files['latest_report_card'];
        }

        $residenceDocumentScan_FileName = '';
        if ($request->hasFile('residence_document_file')) {
            $residenceDocumentScan_FileName = 'ResidenceDocumentScan_'.now()->format('Y-m-d_H-i-s');
            $residenceDocumentScanFileExtension = $request->file('residence_document_file')->getClientOriginalExtension();
            $residenceDocumentScan_FileName = $request->file('residence_document_file')->storeAs(
                'public/uploads/Documents/'.$checkStudentApplianceStatus->student_id.'/Appliance_'.$checkStudentApplianceStatus->id,
                "$residenceDocumentScan_FileName.$residenceDocumentScanFileExtension"
            );
            $document = new Document;
            $document->user_id = auth()->user()->id;
            $document->document_type_id = DocumentType::whereName('Residence Document')->first()->id;
            $document->src = $residenceDocumentScan_FileName;
            $document->save();

            $document = new Document;
            $document->user_id = $checkStudentApplianceStatus->student_id;
            $document->document_type_id = DocumentType::whereName('Residence Document')->first()->id;
            $document->src = $residenceDocumentScan_FileName;
            $document->save();
        } else {
            $residenceDocumentScan_FileName = $files['residence_document_file'];
        }

        $files = json_encode(
            [
                'father_passport_file' => $fatherPassportFile,
                'mother_passport_file' => $motherPassportFileName,
                'latest_report_card' => $latestReportCard_FileName,
                'student_passport_file' => $studentPassportFileName,
                'residence_document_file' => $residenceDocumentScan_FileName,
            ], true);

        $evidences->appliance_id = $checkStudentApplianceStatus->id;
        $evidences->informations = json_encode($request->all(), true);
        $evidences->files = $files;
        $evidences->save();

        $studentAppliance = StudentApplianceStatus::whereId($checkStudentApplianceStatus->id)->first();
        $studentAppliance->documents_uploaded = 2;
        $studentAppliance->documents_uploaded_approval = null;
        $studentAppliance->save();

        $this->sendSMS($studentInformation->guardianInfo->mobile, "Documents uploaded successfully. Please wait for the confirmation of the documents sent.\nSavior Schools");

        return redirect()->route('dashboard')->with('success', 'Documents Uploaded Successfully!');
    }

    public function deleteUserDocument(Request $request)
    {
        $this->validate($request, [
            'documentId' => 'required|integer|exists:documents,id',
        ]);
        $document = Document::findOrFail($request->documentId);
        $document->update(['remover' => auth()->user()->id]);
        $document->delete();

        Session::flash('success', 'Document Deleted Successfully!');

        return true;
    }
}
