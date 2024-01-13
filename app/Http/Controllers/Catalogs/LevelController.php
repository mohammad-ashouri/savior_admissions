<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
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
        $levels = Level::orderBy('name', 'asc')->paginate(10);
        return view('Catalogs.Levels.index', compact('levels'));
    }

    public function create()
    {
        $levels = Level::get();
        return view('Catalogs.Levels.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:schools,name',
        ]);

        $catalog = Level::create(['name' => $request->input('name')]);

        return redirect()->route('Levels.index')
            ->with('success', 'Level created successfully');
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
        $catalog = Level::find($id);
        return view('Catalogs.Levels.edit', compact('catalog'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $catalog = Level::find($id);
        $catalog->name = $request->input('name');
        $catalog->status = $request->input('status');
        $catalog->save();

        return redirect()->route('Levels.index')
            ->with('success', 'Level updated successfully');
    }
}
