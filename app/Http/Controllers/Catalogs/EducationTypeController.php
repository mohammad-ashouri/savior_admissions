<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\EducationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EducationTypeController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:education-type-list', ['only' => ['index']]);
        $this->middleware('permission:education-type-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:education-type-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:education-type-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $types = EducationType::orderBy('name', 'asc')->paginate(10);
        return view('Catalogs.EducationTypes.index', compact('types'));
    }

    public function create()
    {
        $catalog = EducationType::get();
        return view('Catalogs.EducationTypes.create', compact('catalog'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:document_types,name',
            'description' => 'required',
        ]);

        $catalog = EducationType::create(['name' => $request->input('name') , 'description'=>$request->input('description')]);

        return redirect()->route('EducationTypes.index')
            ->with('success', 'Document type created successfully');
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
        $catalog = EducationType::find($id);
        return view('Catalogs.EducationTypes.edit', compact('catalog'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $catalog = EducationType::find($id);
        $catalog->name = $request->input('name');
        $catalog->description = $request->input('description');
        $catalog->status = $request->input('status');
        $catalog->save();

        return redirect()->route('EducationTypes.index')
            ->with('success', 'Document type updated successfully');
    }
}
