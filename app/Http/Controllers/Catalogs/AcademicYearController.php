<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\School;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:catalogs-list|catalogs-create|catalogs-edit|catalogs-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:catalogs-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:catalogs-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:catalogs-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $academicYears = AcademicYear::orderBy('name', 'asc')->paginate(10);
        return view('Catalogs.AcademicYears.index', compact('academicYears'));
    }

    public function create()
    {
        $academicYears = AcademicYear::get();
        $schools=School::where('status',1)->orderBy('name','asc')->get();
        return view('Catalogs.AcademicYears.create', compact('academicYears','schools'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:schools,name',
        ]);

        $catalog = AcademicYear::create(['name' => $request->input('name')]);

        return redirect()->route('AcademicYears.index')
            ->with('success', 'AcademicYear created successfully');
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
        $catalog = AcademicYear::find($id);
        return view('Catalogs.AcademicYears.edit', compact('catalog'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $catalog = AcademicYear::find($id);
        $catalog->name = $request->input('name');
        $catalog->status = $request->input('status');
        $catalog->save();

        return redirect()->route('AcademicYears.index')
            ->with('success', 'AcademicYear updated successfully');
    }
}
