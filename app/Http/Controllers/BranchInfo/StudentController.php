<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
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
        $me = User::find(auth()->user()->id);

        if ($me->hasRole('Super Admin')) {
            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')
                ->where('tuition_payment_status', 'Paid')
                ->distinct('student_id')
                ->orderBy('academic_year', 'desc')->paginate(100);
            $academicYears = AcademicYear::get();
            $this->logActivity(json_encode(['activity' => 'Getting Students list']), request()->ip(), request()->userAgent());

            return view('Students.index', compact('students', 'academicYears', 'me'));
        } elseif ($me->hasRole('Parent')) {
            $students = StudentInformation::where('guardian', auth()->user()->id)
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->orderBy('student_id', 'asc')->paginate(100);
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
                ->orderBy('academic_year', 'desc')->paginate(100);
            $academicYears = AcademicYear::whereIn('id', $academicYears)->get();
            $this->logActivity(json_encode(['activity' => 'Getting Students list']), request()->ip(), request()->userAgent());

            return view('Students.index', compact('students', 'academicYears', 'me'));

        }

        if ($students->isEmpty()) {
            $students = [];
        }
        $this->logActivity(json_encode(['activity' => 'Getting Students list']), request()->ip(), request()->userAgent());

        return view('Students.index', compact('students', 'me'));

    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        abort('403');
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
            $this->logActivity(json_encode(['activity' => 'Saving Student Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $nationality = $request->nationality;
        //        $current_identification_type = $request->current_identification_type;
        $birthplace = $request->birthplace;
        $birthdate = $request->birthdate;
        //        $current_identification_code = $request->current_identification_code;
        $gender = $request->gender;

        $me = User::find(auth()->user()->id);
        $lastStudent = User::whereHas('roles', function ($query) {
            $query->where('name', 'Student');
        })->orderByDesc('id')->first();

        $user = new User();
        $user->id = $lastStudent->id + 1;
        $user->password = Hash::make('Aa12345678');
        $user->status = 0;
        $user->save();
        $user->assignRole('Student');

        $generalInformation = new GeneralInformation();
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

        $studentInformation = new StudentInformation();
        $studentInformation->student_id = $user->id;
        $studentInformation->guardian = auth()->user()->id;
        $studentInformation->current_nationality = $nationality;
        //        $studentInformation->current_identification_type = $current_identification_type;
        //        $studentInformation->current_identification_code = $current_identification_code;
        $studentInformation->save();
        $this->logActivity(json_encode(['activity' => 'Student Saved', 'id' => $studentInformation->user_id]), request()->ip(), request()->userAgent());

        return redirect()->route('Students.index')
            ->with('success', 'Student added successfully');
    }

    public function show($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);

        if ($me->hasRole('Parent')) {
            $studentInformations = StudentInformation::where('guardian', auth()->user()->id)
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->with('extraInformations')
                ->with('userInfo')
                ->where('student_id', $id)
                ->first();
            if (empty($studentInformations)) {
                $this->logActivity(json_encode(['activity' => 'Failed Access To Student Information', 'student_id' => $id]), request()->ip(), request()->userAgent());

                abort(403);
            }
            $this->logActivity(json_encode(['activity' => 'Getting Student Information', 'id' => $studentInformations->student_id]), request()->ip(), request()->userAgent());

            return view('Students.show', compact('studentInformations'));
        } elseif ($me->hasRole('Super Admin')) {
            $studentInformations = StudentInformation::with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->with('userInfo')
                ->with('extraInformations')
                ->where('student_id', $id)
                ->first();
            if (empty($studentInformations)) {
                $this->logActivity(json_encode(['activity' => 'Failed Access To Student Information', 'student_id' => $id]), request()->ip(), request()->userAgent());

                abort(403);
            }
            $this->logActivity(json_encode(['activity' => 'Getting Student Information', 'id' => $studentInformations->student_id]), request()->ip(), request()->userAgent());

            return view('Students.show', compact('studentInformations'));
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
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
                $this->logActivity(json_encode(['activity' => 'Failed Access To Student Information', 'student_id' => $id]), request()->ip(), request()->userAgent());

                abort(403);
            }
            $this->logActivity(json_encode(['activity' => 'Getting Student Information', 'id' => $studentInformations->student_id]), request()->ip(), request()->userAgent());

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
            $this->logActivity(json_encode(['activity' => 'Saving Student Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return response()->json(['error' => $validator], 422);
        }
        $extraInformationTitles = $request->title;
        $extraInformationDescriptions = $request->description;
        if (isset($request->title) and count($extraInformationTitles) != count($extraInformationDescriptions)) {
            $this->logActivity(json_encode(['activity' => 'Extras Count Values Is Not Same', 'student_id' => $request->user_id]), request()->ip(), request()->userAgent());

            return response()->json(['error' => 'Extras count values is not same'], 422);
        }

        $checkUser = User::find($request->user_id);
        if (empty($checkUser)) {
            $user = User::create(['id' => $request->user_id, 'password' => bcrypt('Aa16001600')]);
            $role = Role::where('name', 'Student')->first();
            $user->assignRole([$role->id]);
            $this->logActivity(json_encode(['activity' => 'Student created successfully', 'user_id' => $request->user_id]), request()->ip(), request()->userAgent());
        }

        $studentInformation = StudentInformation::where('student_id', $checkUser->id)->first();

        if (empty($studentInformation)) {
            $studentInformation = new StudentInformation();
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
                    $studentExtraInformation = new StudentExtraInformation();
                    $studentExtraInformation->student_informations_id = $studentInformation->id;
                    $studentExtraInformation->name = $titles;
                    $studentExtraInformation->description = $extraInformationDescriptions[$index];
                    $studentExtraInformation->save();
                }
            }
        }

        $this->logActivity(json_encode(['activity' => 'Student information saved successfully', 'user_id' => $request->user_id]), request()->ip(), request()->userAgent());

        return response()->json(['success' => 'Student information saved successfully!'], 200);
    }

    public function studentStatusIndex()
    {
        $me = User::find(auth()->user()->id);

        $students = [];
        if ($me->hasRole('Super Admin')) {
            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
                ->distinct('student_id')
                ->orderBy('academic_year', 'desc')->paginate(150);
            $academicYears = AcademicYear::get();
            $this->logActivity(json_encode(['activity' => 'Getting Students list']), request()->ip(), request()->userAgent());

            return view('BranchInfo.StudentStatuses.index', compact('students', 'academicYears', 'me'));
        } elseif ($me->hasRole('Parent')) {
            $students = StudentInformation::where('guardian', auth()->user()->id)
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations')
                ->orderBy('student_id', 'asc')->paginate(10);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
                ->whereIn('academic_year', $academicYears)
                ->distinct('student_id')
                ->orderBy('academic_year', 'desc')->paginate(150);
            $academicYears = AcademicYear::whereIn('id', $academicYears)->get();
            $this->logActivity(json_encode(['activity' => 'Getting Students list']), request()->ip(), request()->userAgent());

            return view('BranchInfo.StudentStatuses.index', compact('students', 'academicYears', 'me'));

        }

        if ($students->isEmpty() or empty($students)) {
            $students = [];
        }
        $this->logActivity(json_encode(['activity' => 'Getting Students list']), request()->ip(), request()->userAgent());

        return view('BranchInfo.StudentStatuses.index', compact('students', 'me'));
    }

    public function uploadPersonalPicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:users,id',
            'personal_picture' => 'required|image|mimes:jpeg,png,jpg,bmp|max:2048',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Upload Personal Image Failed', 'errors' => json_encode($validator), 'values' => json_encode($request->all(), true)]), request()->ip(), request()->userAgent());

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

        $userPersonalPictureDocument = new Document();
        $userPersonalPictureDocument->user_id = $userId;
        $userPersonalPictureDocument->document_type_id = 2;
        $userPersonalPictureDocument->src = $destinationPath;
        $userPersonalPictureDocument->save();

        return redirect()->back()->with('success', "Student's personal picture uploaded successfully");

    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'student_id' => 'nullable|exists:student_appliance_statuses,student_id',
            'academic_year' => 'nullable|exists:academic_years,id',
            'student_first_name' => 'nullable|string',
            'student_last_name' => 'nullable|string',
            'gender' => 'nullable|string|in:Male,Female',
        ]);

        $me = User::find(auth()->user()->id);
        $studentId = $request->student_id;
        $studentFirstName = $request->student_first_name;
        $studentLastName = $request->student_last_name;
        $gender = $request->gender;
        $academicYear = $request->academic_year;

        $students = [];
        if ($me->hasRole('Super Admin')) {
            $data = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
                ->distinct('student_id');
            if (! empty($studentId)) {
                $data->where('student_id', $studentId);
            }
            if (! empty($studentFirstName)) {
                $data->whereHas('studentInfo',function($query) use ($studentFirstName){
                    $query->whereHas('generalInformationInfo',function($query) use ($studentFirstName){
                        $query->where('first_name_en','like',"%$studentFirstName%");
                    });
                });
            }
            if (! empty($studentLastName)) {
                $data->whereHas('studentInfo',function($query) use ($studentLastName){
                    $query->whereHas('generalInformationInfo',function($query) use ($studentLastName){
                        $query->where('first_last_en','like',"%$studentLastName%");
                    });
                });
            }
            if (! empty($gender)) {
                $data->whereHas('studentInfo',function($query) use ($gender){
                    $query->whereHas('generalInformationInfo',function($query) use ($gender){
                        $query->where('gender',$gender);
                    });
                });
            }
            if (! empty($academicYear)) {
                $data->where('academic_year', $academicYear);
            }
            $students = $data->orderBy('academic_year', 'desc')->paginate(150);
            $academicYears = AcademicYear::get();

            return view('BranchInfo.StudentStatuses.index', compact('students', 'academicYears', 'me'));
        } elseif ($me->hasRole('Parent')) {
            $data = StudentInformation::where('guardian', auth()->user()->id)
                ->with('studentInfo')
                ->with('nationalityInfo')
                ->with('identificationTypeInfo')
                ->with('generalInformations');
            if (! empty($studentId)) {
                $data->where('student_id', $studentId);
            }
            if (! empty($studentFirstName)) {
                $data->whereHas('studentInfo',function($query) use ($studentFirstName){
                    $query->whereHas('generalInformationInfo',function($query) use ($studentFirstName){
                        $query->where('first_name_en','like',"%$studentFirstName%");
                    });
                });
            }
            if (! empty($studentLastName)) {
                $data->whereHas('studentInfo',function($query) use ($studentLastName){
                    $query->whereHas('generalInformationInfo',function($query) use ($studentLastName){
                        $query->where('first_last_en','like',"%$studentLastName%");
                    });
                });
            }
            if (! empty($gender)) {
                $data->whereHas('studentInfo',function($query) use ($gender){
                    $query->whereHas('generalInformationInfo',function($query) use ($gender){
                        $query->where('gender',$gender);
                    });
                });
            }
            if (! empty($academicYear)) {
                $data->where('academic_year', $academicYear);
            }
            $students = $data->orderBy('academic_year', 'desc')->paginate(150);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $data = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
                ->whereIn('academic_year', $academicYears)
                ->distinct('student_id');
            if (! empty($studentId)) {
                $data->where('student_id', $studentId);
            }
            if (! empty($studentFirstName)) {
                $data->whereHas('studentInfo',function($query) use ($studentFirstName){
                    $query->whereHas('generalInformationInfo',function($query) use ($studentFirstName){
                        $query->where('first_name_en','like',"%$studentFirstName%");
                    });
                });
            }
            if (! empty($studentLastName)) {
                $data->whereHas('studentInfo',function($query) use ($studentLastName){
                    $query->whereHas('generalInformationInfo',function($query) use ($studentLastName){
                        $query->where('first_last_en','like',"%$studentLastName%");
                    });
                });
            }
            if (! empty($gender)) {
                $data->whereHas('studentInfo',function($query) use ($gender){
                    $query->whereHas('generalInformationInfo',function($query) use ($gender){
                        $query->where('gender',$gender);
                    });
                });
            }
            if (! empty($academicYear)) {
                $data->where('academic_year', $academicYear);
            }
            $students = $data->orderBy('academic_year', 'desc')->paginate(150);
            $academicYears = AcademicYear::whereIn('id', $academicYears)->get();
            $this->logActivity(json_encode(['activity' => 'Getting Students list']), request()->ip(), request()->userAgent());

            return view('BranchInfo.StudentStatuses.index', compact('students', 'academicYears', 'me'));

        }

        if ($students->isEmpty() or empty($students)) {
            $students = [];
        }
        $this->logActivity(json_encode(['activity' => 'Getting Students list']), request()->ip(), request()->userAgent());

        return view('BranchInfo.StudentStatuses.index', compact('students', 'me'));
    }
}
