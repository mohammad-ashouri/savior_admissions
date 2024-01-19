<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\DocumentType;
use App\Models\Catalogs\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:school-list', ['only' => ['index']]);
        $this->middleware('permission:school-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:school-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:school-delete', ['only' => ['destroy']]);
        $this->middleware('permission:school-search', ['only' => ['search']]);
    }

    public function index()
    {
        $schools = School::orderBy('name', 'asc')->paginate(10);
        return view('Catalogs.Schools.index', compact('schools'));
    }

    public function create()
    {
        $schools = School::get();
        return view('Catalogs.Schools.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:schools,name',
            'gender' => 'required',
        ]);

        $catalog = School::create(['name' => $request->input('name'),'gender' => $request->input('gender')]);

        return redirect()->route('Schools.index')
            ->with('success', 'School created successfully');
    }

//    public function show($id)
//    {
//        $role = Role::find($id);
//        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
//            ->where("role_has_permissions.role_id", $id)
//            ->get();
//
//        return view('roles.show', compact('role', 'rolePermissions'));
//    }

    public function edit($id)
    {
        $catalog = School::find($id);
        return view('Catalogs.Schools.edit', compact('catalog'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
            'status' => 'required',
        ]);

        $catalog = School::find($id);
        $catalog->name = $request->input('name');
        $catalog->gender = $request->input('gender');
        $catalog->status = $request->input('status');
        $catalog->save();

        return redirect()->route('Schools.index')
            ->with('success', 'School updated successfully');
    }
}
