<?php

namespace App\Http\Controllers;

use App\Models\Catalogs\CurrentIdentificationType;
use App\Models\Catalogs\GuardianStudentRelationship;
use App\Models\Catalogs\School;
use App\Models\Catalogs\StudentStatus;
use App\Models\Country;
use App\Models\GeneralInformation;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:list-users', ['only' => ['index']]);
        $this->middleware('permission:create-users', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-users', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-users', ['only' => ['destroy']]);
        $this->middleware('permission:search-user', ['only' => ['searchUser']]);
    }

    public function index()
    {
        $me = User::find(session('id'));
        if ($me) {
            if ($me->hasRole('Super Admin')) {
                $data = User::orderBy('id', 'DESC')->paginate(20);
            } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
                $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
                if ($myAllAccesses != null) {
                    $principalAccess = explode("|", $myAllAccesses->principal);
                    $admissionsOfficerAccess = explode("|", $myAllAccesses->admissions_officer);
                    $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
                    $data = User::where('status', 1)->whereIn('additional_information->school_id', $filteredArray)->paginate(20);
                    if ($data->isEmpty()) {
                        $data = [];
                    }
                } else {
                    $data = [];
                }
            } else {
                $data = [];
            }
            return view('users.index', compact('data'));
        }
        abort(403);
    }

    public function create()
    {
        $me = User::find(session('id'));
        if ($me->hasRole('Super Admin')) {
            $schools = School::get();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            if ($myAllAccesses != null) {
                $principalAccess = explode("|", $myAllAccesses->principal);
                $admissionsOfficerAccess = explode("|", $myAllAccesses->admissions_officer);
                $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
                $schools = School::where('status', 1)->whereIn('id', $filteredArray)->get();
                if ($schools->count() == 0) {
                    $schools = [];
                }
            } else {
                abort(403);
            }
        } else {
            $schools = [];
        }
        $roles = Role::orderBy('name', 'asc')->get();

        return view('users.create', compact('roles', 'schools'));
    }

    public function store(Request $request)
    {
        $me = User::find(session('id'));
        $this->validate($request, [
            'name' => 'required',
            'family' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|integer|unique:users,mobile',
            'password' => 'required|unique:users,mobile',
            'role' => 'required',
            'school' => 'required|exists:schools,id'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->family = $request->family;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = Hash::make($request->password);
        if ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            $additionalInformation = [
                'school_id' => $request->school,
            ];
            $userAdditionalInformation = json_decode($user->additional_information, true) ?? [];
            $userAdditionalInformation = array_merge($userAdditionalInformation, $additionalInformation);
            $user->additional_information = json_encode($userAdditionalInformation);
        }
        if ($user->save()) {
            GeneralInformation::create(
                [
                    'user_id' => $user->id
                ]
            );
            $user->assignRole($request->input('role'));
        }
        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function edit($id)
    {
        $me = User::find(session('id'));
        $user = User::find($id);
        $userRole = $user->roles->pluck('name', 'name')->all();
        if ($me->hasRole('Super Admin')) {
            $schools = School::get();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            if ($myAllAccesses != null) {
                $principalAccess = explode("|", $myAllAccesses->principal);
                $admissionsOfficerAccess = explode("|", $myAllAccesses->admissions_officer);
                $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
                $schools = School::where('status', 1)->whereIn('id', $filteredArray)->paginate(20);
                if ($schools->count() == 0) {
                    $schools = [];
                }
            } else {
                abort(403);
            }
        } else {
            $schools = [];
        }
        $roles = Role::orderBy('name', 'asc')->pluck('name')->all();
        $generalInformation = GeneralInformation::where('user_id', $user->id)->first();
        $countries = Country::get();
        $nationalities = Country::orderBy('nationality', 'asc')->select('nationality','id')->distinct('nationality')->get();
        $parents = Role::where('name', 'Parent(Father)')->orWhere('name', 'Parent(Mother)')->with(['users' => function ($query) {
            $query->orderBy('family', 'desc');
        }])->get();
        $guardianStudentRelationships = GuardianStudentRelationship::get();
        $currentIdentificationTypes = CurrentIdentificationType::get();
        $statuses = StudentStatus::orderBy('name', 'asc')->get();
        $studentInformation = StudentInformation::where('student_id', $id)->first();
        if (empty($studentInformation)) {
            $studentInformation = [];
        }
        return view('users.edit', compact('user', 'roles', 'userRole', 'generalInformation', 'schools', 'countries', 'nationalities', 'parents', 'guardianStudentRelationships', 'currentIdentificationTypes', 'statuses', 'studentInformation'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->syncPermissions($request->input('role'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function changeUserPassword(Request $request)
    {
        $this->validate($request, [
            'New_password' => 'same:confirm-password|min:8|max:20|required',
            'user_id' => 'required|integer'
        ]);
        $input = $request->all();
        if (!empty($input['New_password'])) {
            $input['password'] = Hash::make($input['New_password']);
            $user = User::find($input['user_id']);
            $user->password = $input['password'];
            $user->save();
            $this->logActivity('Password Changed Successfully => ' . $user->password, request()->ip(), request()->userAgent(), session('id'));
        } else {
            $input = Arr::except($input, array('New_password'));
        }
    }

    public function searchUser(Request $request)
    {
        $activity = [];
        $data = User::where(function ($query) use ($request, &$activity) {
            $searchEduCode = $request->input('search-edu-code');
            $searchFirstName = $request->input('search-first-name');
            $searchLastName = $request->input('search-last-name');
            $activity['activity'] = 'search in users';

            if (!empty($searchEduCode)) {
                $query->where('id', $searchEduCode);
                $activity['edu_code'] = $searchEduCode;
            }

            if (!empty($searchFirstName)) {
                $query->where('name', 'LIKE', "%$searchFirstName%");
                $activity['first_name'] = $searchFirstName;
            }

            if (!empty($searchLastName)) {
                $query->where('family', 'LIKE', "%$searchLastName%");
                $activity['last_name'] = $searchLastName;
            }
        })
            ->orderBy('id', 'DESC')
            ->paginate($perPage = 15, $columns = ['*'], $pageName = 'users');

        $this->logActivity(json_encode($activity), request()->ip(), request()->userAgent(), session('id'));
        return view('users.index', compact('data'));
    }


    public function changePrincipalInformation(Request $request)
    {
        $this->validate($request, [
            'school' => 'required|exists:schools,id',
            'user_id' => 'required|integer|exists:users,id'
        ]);
        $user = User::find($request->user_id);
        $studentInformation = [
            'school_id' => $request->school,
        ];
        $userAdditionalInformation = json_decode($user->additional_information, true) ?? [];
        $userAdditionalInformation = array_merge($userAdditionalInformation, $studentInformation);
        $user->additional_information = json_encode($userAdditionalInformation);
        $user->save();
        $this->logActivity('Principal information saved successfully => ' . $request->user_id, request()->ip(), request()->userAgent(), session('id'));
        return response()->json(['success' => 'Principal information saved successfully!'], 200);
    }
}
