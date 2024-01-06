<?php

namespace App\Http\Controllers;

use App\Models\Catalogs\School;
use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-users|create-users|edit-users|delete-users', ['only' => ['index', 'store']]);
        $this->middleware('permission:create-users', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-users', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-users', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = User::orderBy('id', 'DESC')->paginate(
            $perPage = 15, $columns = ['*'], $pageName = 'users'
        );
        return view('users.index', compact('data'));
    }

    public function create()
    {
        $roles = Role::get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'family' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|integer|unique:users,mobile',
            'roles' => 'required'
        ]);

        $input = $request->all();
//        $input['password'] = Hash::make($input['password']);
        $input['password'] = Hash::make(12345678);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $generalInformation = GeneralInformation::create(
            [
                'user_id' => $user->id
            ]
        );

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $generalInformation = GeneralInformation::where('user_id', $user->id)->first();
        $schools = School::where('status', 1)->get();
        return view('users.edit', compact('user', 'roles', 'userRole', 'generalInformation', 'schools'));
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

        $user->syncPermissions($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
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

    public function changeStudentInformation(Request $request)
    {
        $user = User::find($request->user_id);
        $studentInformation = [
            'school_id' => $request->school,
        ];
        $userAdditionalInformation = json_decode($user->additional_information, true) ?? [];
        $userAdditionalInformation = array_merge($userAdditionalInformation, $studentInformation);
        $user->additional_information = json_encode($userAdditionalInformation);
        $user->save();
        $this->logActivity('Student information saved successfully => ' . $request->user_id, request()->ip(), request()->userAgent(), session('id'));
        return response()->json(['success' => 'Student information saved successfully!'], 200);
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
}
