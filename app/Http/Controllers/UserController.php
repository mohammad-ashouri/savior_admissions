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
    public function __construct()
    {
        $this->middleware('permission:list-users', ['only' => ['index']]);
        $this->middleware('permission:create-users', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-users', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-users', ['only' => ['destroy']]);
        $this->middleware('permission:search-user', ['only' => ['searchUser']]);
        $this->middleware('permission:change-users-password', ['only' => ['changeUserPassword']]);
    }

    public function index()
    {
        $me = User::find(auth()->user()->id);
        $roles = Role::orderBy('name', 'asc')->get();

        $data = [];
        if ($me) {
            if ($me->hasRole('Super Admin')) {
                $data = User::with('generalInformationInfo')->orderBy('id', 'DESC')->paginate(20);
            } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
                $data = User::where('status', 1)
                    ->WhereHas('roles', function ($query) {
                        $query->where('name', 'Parent');
                        $query->orWhere('name', 'Student');
                    })
                    ->paginate(20);
                if ($data->isEmpty()) {
                    $data = [];
                }
            }

            return view('users.index', compact('data', 'roles','me'));
        }
        abort(403);
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
        if ($me->hasRole('Super Admin')) {
            $schools = School::get();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            $schools = School::where('status', 1)->whereIn('id', $filteredArray)->get();
            if ($schools->count() == 0) {
                $schools = [];
            }
        } else {
            $schools = [];
        }
        $roles = Role::orderBy('name', 'asc')->get();

        return view('users.create', compact('roles', 'schools'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $me = User::find(auth()->user()->id);
        $this->validate($request, [
            'first_name_fa' => 'required',
            'last_name_fa' => 'required',
            'first_name_en' => 'required',
            'last_name_en' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|integer|unique:users,mobile',
            'password' => 'required|unique:users,mobile',
            'role' => 'required',
            //            'school' => 'required|exists:schools,id',
        ]);

        $user = new User;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = Hash::make($request->password);
        //        if (($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) and $request->role == 'Student') {
        //            $additionalInformation = [
        //                'school_id' => $request->school,
        //            ];
        //            $userAdditionalInformation = json_decode($user->additional_information, true) ?? [];
        //            $userAdditionalInformation = array_merge($userAdditionalInformation, $additionalInformation);
        //            $user->additional_information = json_encode($userAdditionalInformation);
        //        }
        if ($user->save()) {
            $generalInformation = new GeneralInformation();
            $generalInformation->user_id = $user->id;
            $generalInformation->first_name_fa = $request->first_name_fa;
            $generalInformation->last_name_fa = $request->last_name_fa;
            $generalInformation->first_name_en = $request->first_name_en;
            $generalInformation->last_name_en = $request->last_name_en;
            $generalInformation->save();
            $user->assignRole($request->input('role'));
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
        $user = User::find($id);
        $userRole = $user->roles->pluck('name', 'name')->all();
        if ($me->hasRole('Super Admin')) {
            $schools = School::get();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            $schools = School::where('status', 1)->whereIn('id', $filteredArray)->paginate(20);
            if ($schools->count() == 0) {
                $schools = [];
            }
        } else {
            $schools = [];
        }
        $roles = Role::orderBy('name', 'asc')->pluck('name')->all();
        $generalInformation = GeneralInformation::where('user_id', $user->id)->first();
        $countries = Country::get();
        $nationalities = Country::orderBy('nationality', 'asc')->select('nationality', 'id')->distinct('nationality')->get();
        $parents = Role::where('name', 'Student')->orWhere('name', 'Parent')->with(['users' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->get();
        $guardianStudentRelationships = GuardianStudentRelationship::get();
        $currentIdentificationTypes = CurrentIdentificationType::get();
        $statuses = StudentStatus::orderBy('name', 'asc')->get();
        $studentInformation = StudentInformation::with('generalInformations')->where('student_id', $id)->first();
        if (empty($studentInformation)) {
            $studentInformation = [];
        }

        return view('users.edit', compact('user', 'roles', 'userRole', 'generalInformation', 'schools', 'countries', 'nationalities', 'parents', 'guardianStudentRelationships', 'currentIdentificationTypes', 'statuses', 'studentInformation'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
        ]);

        $input = $request->all();
        if (! empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, ['password']);
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->syncPermissions($request->input('role'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function changeUserPassword(Request $request): void
    {
        $this->validate($request, [
            'New_password' => 'same:confirm-password|min:8|max:20|required',
            'user_id' => 'required|integer',
        ]);
        $input = $request->all();
        if (! empty($input['New_password'])) {
            $input['password'] = Hash::make($input['New_password']);
            $user = User::find($input['user_id']);
            $user->password = $input['password'];
            $user->save();
            $this->logActivity(json_encode(['activity' => 'Password Changed Successfully', 'user_id' => $user->id]), request()->ip(), request()->userAgent());

        } else {
            $input = Arr::except($input, ['New_password']);
        }
    }

    public function searchUser(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(auth()->user()->id);
        $roles = Role::orderBy('name', 'asc')->get();

        $searchEduCode = $request->input('search-user-code');
        $searchFirstName = $request->input('search-first-name');
        $searchLastName = $request->input('search-last-name');
        $searchMobile = $request->input('search-mobile');
        $selectedRole = $request->role;
        $query = GeneralInformation::where('first_name_en', 'like', "%$searchFirstName%");
        $users = $query->where(function ($query) use ($searchEduCode, $searchFirstName, $searchLastName) {
            if ($searchEduCode != null) {
                $query->where('user_id', 'like', "%$searchEduCode%");
            }
            if ($searchFirstName != null) {
                $query->where('first_name_en', 'like', "%$searchFirstName%");
            }
            if ($searchLastName != null) {
                $query->where('last_name_en', 'like', "%$searchLastName%");
            }
        })->get()->pluck('user_id')->toArray();

        if ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            $query = User::with('generalInformationInfo')
                ->where('status', 1)
                ->whereIn('id', $users)
                ->where(function ($query) {
                    $query->whereHas('roles', function ($query) {
                        $query->where('name', 'Parent');
                        $query->orWhere('name', 'Student');
                    });
                });
            if (! empty($selectedRole)) {
                $query->whereHas('roles', function ($query) use ($selectedRole) {
                    $query->where('name', $selectedRole);
                });
            }
        } else {
            $query = User::with('generalInformationInfo')
                ->whereIn('id', $users);
            if (! empty($selectedRole)) {
                $query->whereHas('roles', function ($query) use ($selectedRole) {
                    $query->where('name', $selectedRole);
                });
            }
        }
        if ($searchMobile != null) {
            $query->where('mobile', 'like', "%$searchMobile%");
        }
        $data = $query->paginate(20);
        $data->appends(request()->query())->links();
        if ($data->isEmpty()) {
            $data = [];
        }
        $this->logActivity(json_encode(['activity' => 'Search Users', 'values' => json_encode($request->all(),true)]), request()->ip(), request()->userAgent());

        return view('users.index', compact('data', 'roles', 'searchEduCode', 'searchFirstName', 'searchLastName', 'selectedRole'));
    }

    public function changePrincipalInformation(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'school' => 'required|exists:schools,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);
        $user = User::find($request->user_id);
        $studentInformation = [
            'school_id' => $request->school,
        ];
        $userAdditionalInformation = json_decode($user->additional_information, true) ?? [];
        $userAdditionalInformation = array_merge($userAdditionalInformation, $studentInformation);
        $user->additional_information = json_encode($userAdditionalInformation);
        $user->save();
        $this->logActivity(json_encode(['activity' => 'Principal Information Saved Successfully', 'user_id' => $request->user_id]), request()->ip(), request()->userAgent());

        return response()->json(['success' => 'Principal information saved successfully!'], 200);
    }
}
