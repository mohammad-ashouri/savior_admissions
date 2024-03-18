<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:level-list|level-create|level-edit|level-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:level-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:level-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:level-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $levels = Level::orderBy('id', 'asc')->paginate(10);
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

    public function show(Request $request)
    {
        $name=$request->name;
        $levels=Level::where('name','LIKE', "%$name%")->paginate(10);
        if ($levels->isEmpty()){
            return redirect()->route('Levels.index')->withErrors('Not Found!')->withInput();
        }
        return view('Catalogs.Levels.index', compact('levels','name'));
    }
}
