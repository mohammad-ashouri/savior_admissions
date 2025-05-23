<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplianceConfirmationInformation;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\CurrentIdentificationType;
use App\Models\Country;
use App\Models\Document;
use App\Models\GeneralInformation;
use App\Models\StudentExtraInformation;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:students-list', ['only' => ['index']]);
        $this->middleware('permission:students-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:students-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:students-delete', ['only' => ['destroy']]);
        $this->middleware('permission:students-search', ['only' => ['search']]);
        $this->middleware('permission:students-show', ['only' => ['show']]);
        $this->middleware('permission:change-student-information', ['only' => ['changeInformation']]);
        $this->middleware('permission:student-statuses-list', ['only' => ['studentStatusIndex']]);
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        if (auth()->user()->hasRole('Super Admin')) {
            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')
                ->whereTuitionPaymentStatus('Paid')
                ->orderBy('academic_year', 'desc')->get();
            $academicYears = AcademicYear::get();

            return view('Students.index', compact('students', 'academicYears'));
        } elseif (auth()->user()->hasExactRoles(['Parent'])) {
            $students = StudentInformation::whereGuardian(auth()->user()->id)
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->orderBy('student_id', 'asc')->get();
        } elseif (auth()->user()->hasRole(['Principal','Admissions Officer'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')
                ->whereIn('academic_year', $academicYears)
                ->whereTuitionPaymentStatus('Paid')
                ->orderBy('academic_year', 'desc')->get();
            $academicYears = AcademicYear::whereIn('id', $academicYears)->get();

            return view('Students.index', compact('students', 'academicYears'));

        }

        if ($students->isEmpty()) {
            $students = [];
        }

        return view('Students.index', compact('students'));

    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        //        abort('403');
        $birthplaces = Country::orderBy('en_short_name', 'asc')->get();
        $nationalities = Country::orderBy('nationality', 'asc')->select('nationality', 'id')->distinct()->get();
        $identificationTypes = CurrentIdentificationType::get();

        return view('Students.create', compact('birthplaces', 'identificationTypes', 'nationalities'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nationality' => 'required|exists:countries,id',
            'birthplace' => 'required|exists:countries,id',
            'faragir_code' => 'required|string',
            'first_name_fa' => 'required|string',
            'last_name_fa' => 'required|string',
            'first_name_en' => 'required|string',
            'last_name_en' => 'required|string',
            'birthdate' => 'required|date',
            'gender' => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $nationality = $request->nationality;
        //        $current_identification_type = $request->current_identification_type;
        $birthplace = $request->birthplace;
        $birthdate = $request->birthdate;
        $faragir_code = $request->faragir_code;
        $gender = $request->gender;

        //Check student information
        $allMyStudents = StudentInformation::whereGuardian(auth()->user()->id)->get()->pluck('student_id')->toArray();
        $allGeneralInformation = GeneralInformation::whereIn('user_id', $allMyStudents)
            ->whereFirstNameEn($request->first_name_en)
            ->whereLastNameEn($request->last_name_en)
            ->whereGender($request->gender)
            ->first();

        if (! empty($allGeneralInformation)) {
            return redirect()->back()->withErrors('Duplicate student entered. Please check your student list!')->withInput();
        }

        $lastStudent = User::whereHas('roles', function ($query) {
            $query->whereName('Student');
        })->orderByDesc('id')->first();

        $user = new User;
        $user->id = $lastStudent->id + 1;
        $user->password = Hash::make('Aa12345678');
        $user->status = 0;
        $user->save();
        $user->assignRole('Student');

        $generalInformation = new GeneralInformation;
        $generalInformation->user_id = $user->id;
        $generalInformation->first_name_fa = $request->first_name_fa;
        $generalInformation->last_name_fa = $request->last_name_fa;
        $generalInformation->first_name_en = $request->first_name_en;
        $generalInformation->last_name_en = $request->last_name_en;
        $generalInformation->birthdate = $birthdate;
        $generalInformation->birthplace = $birthplace;
        $generalInformation->nationality = $nationality;
        $generalInformation->gender = $gender;
        $generalInformation->save();

        $studentInformation = new StudentInformation;
        $studentInformation->student_id = $user->id;
        $studentInformation->guardian = auth()->user()->id;
        $studentInformation->current_nationality = $nationality;
        //        $studentInformation->current_identification_type = $current_identification_type;
        $studentInformation->current_identification_code = $faragir_code;
        $studentInformation->save();

        return redirect()->route('Students.index')
            ->with('success', 'Student added successfully');
    }

    public function show($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        if (auth()->user()->hasExactRoles(['Parent'])) {
            $studentInformations = StudentInformation::whereGuardian(auth()->user()->id)
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->with('extraInformations')
                ->with('userInfo')
                ->whereStudentId($id)
                ->first();
            if (empty($studentInformations)) {
                abort(403);
            }

            return view('Students.show', compact('studentInformations'));
        } elseif (auth()->user()->hasRole('Super Admin')) {
            $studentInformations = StudentInformation::with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->with('userInfo')
                ->with('extraInformations')
                ->whereStudentId($id)
                ->first();
            if (empty($studentInformations)) {
                abort(403);
            }

            return view('Students.show', compact('studentInformations'));
        } elseif (auth()->user()->hasRole(['Principal','Admissions Officer'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $studentInformations = StudentInformation::with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->with('extraInformations')
                ->with('userInfo')
                ->join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->join('applications', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
                ->where('student_appliance_statuses.student_id', $id)
                ->whereIn('application_timings.academic_year', $academicYears)
                ->first();
            if (empty($studentInformations)) {
                abort(403);
            }

            return view('Students.show', compact('studentInformations'));
        }

        abort(403);
    }

    public function changeInformation(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_identification_code' => 'nullable|string',
            'current_identification_type' => 'nullable|exists:current_identification_types,id',
            'current_nationality' => 'nullable|exists:countries,id',
            'father' => 'nullable|exists:users,id',
            'guardian' => 'required|exists:users,id',
            'guardian_student_relationship' => 'nullable|exists:guardian_student_relationships,id',
            'mother' => 'nullable|exists:users,id',
            'status' => 'nullable|exists:student_statuses,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator], 422);
        }
        $extraInformationTitles = $request->title;
        $extraInformationDescriptions = $request->description;
        if (isset($request->title) and count($extraInformationTitles) != count($extraInformationDescriptions)) {
            return response()->json(['error' => 'Extras count values is not same'], 422);
        }

        $checkUser = User::find($request->user_id);
        if (empty($checkUser)) {
            $user = User::create(['id' => $request->user_id, 'password' => bcrypt('Aa16001600')]);
            $role = Role::whereName('Student')->first();
            $user->assignRole([$role->id]);
        }

        $studentInformation = StudentInformation::whereStudentId($checkUser->id)->first();

        if (empty($studentInformation)) {
            $studentInformation = new StudentInformation;
            $studentInformation->student_id = $checkUser->id;
            $studentInformation->save();
        }

        if ($request->father) {
            $studentInformation->parent_father_id = $request->father;
        }
        if ($request->mother) {
            $studentInformation->parent_mother_id = $request->mother;
        }
        $studentInformation->guardian = $request->guardian;
        if ($request->guardian_student_relationship) {
            $studentInformation->guardian_student_relationship = $request->guardian_student_relationship;
        }
        if ($request->current_nationality) {
            $studentInformation->current_nationality = $request->current_nationality;
        }
        if ($request->current_identification_type) {
            $studentInformation->current_identification_type = $request->current_identification_type;
        }
        if ($request->current_identification_code) {
            $studentInformation->current_identification_code = $request->current_identification_code;
        }
        if ($request->status) {
            $studentInformation->status = $request->status;
        }
        $studentInformation->save();

        StudentExtraInformation::where('student_informations_id', $studentInformation->id)->delete();

        if (isset($request->title)) {
            foreach ($extraInformationTitles as $index => $titles) {
                if (! empty($titles)) {
                    $studentExtraInformation = new StudentExtraInformation;
                    $studentExtraInformation->student_informations_id = $studentInformation->id;
                    $studentExtraInformation->name = $titles;
                    $studentExtraInformation->description = $extraInformationDescriptions[$index];
                    $studentExtraInformation->save();
                }
            }
        }

        return response()->json(['success' => 'Student information saved successfully!'], 200);
    }

    public function studentStatusIndex()
    {
        $students = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $academicYears = AcademicYear::get();

            return view('BranchInfo.StudentStatuses.index', compact('students', 'academicYears'));
        } elseif (auth()->user()->hasExactRoles(['Parent'])) {
            $students = StudentInformation::whereGuardian(auth()->user()->id)
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->orderBy('student_id', 'asc')->get();
        } elseif (auth()->user()->hasRole(['Principal','Admissions Officer'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            //            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
            //                ->whereIn('academic_year', $academicYears)
            //                ->orderBy('academic_year', 'desc')->paginate(150);
            $academicYears = AcademicYear::whereIn('id', $academicYears)->get();

            return view('BranchInfo.StudentStatuses.index', compact('students', 'academicYears'));

        }

        if ($students->isEmpty() or empty($students)) {
            $students = [];
        }

        return view('BranchInfo.StudentStatuses.index', compact('students'));
    }

    public function uploadPersonalPicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:users,id',
            'personal_picture' => 'required|image|mimes:jpeg,png,jpg,bmp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userId = $request->id;

        // Get the file from the request
        $file = $request->file('personal_picture');

        $filename = time().'.'.$file->getClientOriginalExtension();
        $destinationPath = storage_path('app/public/uploads/Documents/'.$userId.'/personal_image/');
        $thumbnailPath = storage_path('app/public/uploads/Documents/'.$userId.'/personal_image/'.'/thumbnails');

        if (! File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        if (! File::exists($thumbnailPath)) {
            File::makeDirectory($thumbnailPath, 0755, true);
        }

        $file->move($destinationPath, $filename);

        $thumbnail = Image::make($destinationPath.'/'.$filename)->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $thumbnail->save($thumbnailPath.'/thumb_'.$filename);

        $destinationPath = 'public/uploads/Documents/'.$userId.'/personal_image/'.$filename;
        $thumbnailPath = 'public/uploads/Documents/'.$userId.'/personal_image/'.'thumbnails/thumb_'.$filename;

        $userThumbnail = User::find($userId);
        $userThumbnail->personal_image = $thumbnailPath;
        $userThumbnail->save();

        $userPersonalPictureDocument = new Document;
        $userPersonalPictureDocument->user_id = $userId;
        $userPersonalPictureDocument->document_type_id = 2;
        $userPersonalPictureDocument->src = $destinationPath;
        $userPersonalPictureDocument->save();

        return redirect()->back()->with('success', "Student's personal picture uploaded successfully");

    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'academic_year' => 'nullable|exists:academic_years,id',
        ]);
        $academicYear = $request->academic_year;

        $students = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $data = StudentApplianceStatus::with(['studentInfo','academicYearInfo','documentSeconder']);
            $students = $data
                ->where('academic_year',$academicYear)
                ->orderBy('academic_year', 'desc')->get();
            $academicYears = AcademicYear::get();

            return view('BranchInfo.StudentStatuses.index', compact('students', 'academicYears'));
        } elseif (auth()->user()->hasExactRoles(['Parent'])) {
            $data = StudentInformation::whereGuardian(auth()->user()->id)
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations');
            if (! empty($academicYear)) {
                $data->whereAcademicYear($academicYear);
            }
            $students = $data->orderBy('academic_year', 'desc')->get();
        } elseif (auth()->user()->hasRole(['Principal','Admissions Officer'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $data = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
                ->whereIn('academic_year', $academicYears);
            if (! empty($academicYear)) {
                $data->whereAcademicYear($academicYear);
            }
            $students = $data->orderBy('academic_year', 'desc')->get();
            $academicYears = AcademicYear::whereIn('id', $academicYears)->get();

            return view('BranchInfo.StudentStatuses.index', compact('students', 'academicYears'));

        }

        if ($students->isEmpty() or empty($students)) {
            $students = [];
        }

        return view('BranchInfo.StudentStatuses.index', compact('students'));
    }

    public function StudentStatisticsReportIndex()
    {
        $students = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $academicYears = AcademicYear::get();

            return view('BranchInfo.StudentStatisticsReport.index', compact('students', 'academicYears'));
        } elseif (auth()->user()->hasExactRoles(['Parent'])) {
            $students = StudentInformation::whereGuardian(auth()->user()->id)
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->orderBy('student_id', 'asc')->get();
        } elseif (auth()->user()->hasRole(['Principal','Admissions Officer'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            //            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
            //                ->whereIn('academic_year', $academicYears)
            //                ->orderBy('academic_year', 'desc')->paginate(150);
            $academicYears = AcademicYear::whereIn('id', $academicYears)->get();

            return view('BranchInfo.StudentStatisticsReport.index', compact('students', 'academicYears'));

        }

        if ($students->isEmpty() or empty($students)) {
            $students = [];
        }

        return view('BranchInfo.StudentStatisticsReport.index', compact('students'));
    }

    public function searchStudentStatisticsReport(Request $request)
    {
        $this->validate($request, [
            'student_id' => 'nullable|exists:student_appliance_statuses,student_id',
            'academic_year' => 'nullable|exists:academic_years,id',
            'student_first_name' => 'nullable|string',
            'student_last_name' => 'nullable|string',
            'gender' => 'nullable|string|in:Male,Female',
        ]);

        $academicYear = $request->academic_year;

        $students = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $data = StudentApplianceStatus::with(['studentInfo', 'levelInfo', 'academicYearInfo', 'documentSeconder']);
            if (! empty($academicYear)) {
                $data->whereAcademicYear($academicYear);
            }
            $students = $data->where('approval_status', '1')->orderBy('academic_year', 'desc')->get();

            $academicYears = AcademicYear::get();

            return view('BranchInfo.StudentStatisticsReport.index', compact('students', 'academicYears'));
        } elseif (auth()->user()->hasRole(['Principal','Admissions Officer'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $data = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
                ->whereIn('academic_year', $academicYears);
            if (! empty($academicYear)) {
                $data->whereAcademicYear($academicYear);
            }
            $students = $data->where('approval_status', '1')->orderBy('academic_year', 'desc')->get();

            $academicYears = AcademicYear::whereIn('id', $academicYears)->get();

            return view('BranchInfo.StudentStatisticsReport.index', compact('students', 'academicYears'));

        }

        if ($students->isEmpty() or empty($students)) {
            $students = [];
        }

        return view('BranchInfo.StudentStatisticsReport.index', compact('students'));
    }

    public function getApplianceConfirmationInformation(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|exists:appliance_confirmation_information,id',
        ]);

        //Todo => Check academic years permission!!!

        $applianceConfirmationInformation = ApplianceConfirmationInformation::with('seconderInfo','referrerInfo')->findOrFail($request->id);

        if (! $applianceConfirmationInformation) {
            return response()->json(['error' => 'Error on Getting Confirmation Data'], 422);
        }

        return response()->json(['data' => $applianceConfirmationInformation]);
    }
}
