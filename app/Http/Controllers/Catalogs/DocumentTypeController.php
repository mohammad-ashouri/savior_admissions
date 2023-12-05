<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentTypeController extends Controller
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
        $types = DocumentType::orderBy('name', 'asc')->paginate(10);
        return view('Catalogs.DocumentTypes.index', compact('types'));
    }

    public function create()
    {
        $catalog = DocumentType::get();
        return view('Catalogs.DocumentTypes.create', compact('catalog'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:document_types,name',
        ]);

        $catalog = DocumentType::create(['name' => $request->input('name')]);

        return redirect()->route('DocumentTypes.index')
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
        $catalog = DocumentType::find($id);
        return view('Catalogs.DocumentTypes.edit', compact('catalog'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $catalog = DocumentType::find($id);
        $catalog->name = $request->input('name');
        $catalog->status = $request->input('status');
        $catalog->save();

        return redirect()->route('DocumentTypes.index')
            ->with('success', 'Document type updated successfully');
    }

    public function destroy($id)
    {
        DB::table("document_types")->where('id', $id)->delete();
        return redirect()->route('DocumentTypes.index')
            ->with('success', 'Document type deleted successfully');
    }
}
