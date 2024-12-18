<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:level-list|level-create|level-edit|level-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:level-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:level-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:level-delete', ['only' => ['destroy']]);
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $levels = Level::orderBy('id', 'asc')->get();

        return view('Catalogs.Levels.index', compact('levels'));
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $levels = Level::get();

        return view('Catalogs.Levels.create', compact('levels'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:schools,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $level = Level::create(['name' => $request->input('name')]);

        return redirect()->route('Levels.index')
            ->with('success', 'Level created successfully');
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $catalog = Level::find($id);

        return view('Catalogs.Levels.edit', compact('catalog'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $catalog = Level::find($id);
        $catalog->name = $request->input('name');
        $catalog->status = $request->input('status');
        $catalog->save();

        return redirect()->route('Levels.index')
            ->with('success', 'Level updated successfully');
    }

    public function show(Request $request): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $name = $request->name;
        $levels = Level::whereName('LIKE', "%$name%")->get();
        $levels->appends(request()->query())->links();
        if ($levels->isEmpty()) {
            return redirect()->route('Levels.index')->withErrors('Not Found!')->withInput();
        }

        return view('Catalogs.Levels.index', compact('levels', 'name'));
    }
}
