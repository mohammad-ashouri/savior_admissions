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

    public function index()
    {
        $levels = Level::orderBy('id', 'asc')->paginate(10);
        $this->logActivity(json_encode(['activity' => 'Getting Levels']), request()->ip(), request()->userAgent());

        return view('Catalogs.Levels.index', compact('levels'));
    }

    public function create()
    {
        $levels = Level::get();

        return view('Catalogs.Levels.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:schools,name',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Saving Level Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $level = Level::create(['name' => $request->input('name')]);
        $this->logActivity(json_encode(['activity' => 'Level Saved', 'id' => $level->id]), request()->ip(), request()->userAgent());

        return redirect()->route('Levels.index')
            ->with('success', 'Level created successfully');
    }

    public function edit($id)
    {
        $catalog = Level::find($id);
        $this->logActivity(json_encode(['activity' => 'Getting Level Information For Edit', 'id' => $catalog->id]), request()->ip(), request()->userAgent());

        return view('Catalogs.Levels.edit', compact('catalog'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Saving Level Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $catalog = Level::find($id);
        $catalog->name = $request->input('name');
        $catalog->status = $request->input('status');
        $catalog->save();
        $this->logActivity(json_encode(['activity' => 'Level Updated', 'id' => $catalog->id]), request()->ip(), request()->userAgent());

        return redirect()->route('Levels.index')
            ->with('success', 'Level updated successfully');
    }

    public function show(Request $request)
    {
        $name = $request->name;
        $levels = Level::where('name', 'LIKE', "%$name%")->paginate(10);
        $levels->appends(request()->query())->links();
        if ($levels->isEmpty()) {
            $this->logActivity(json_encode(['activity' => 'Getting Level Informations', 'entered_name' => $request->name, 'status' => 'Not Found']), request()->ip(), request()->userAgent());

            return redirect()->route('Levels.index')->withErrors('Not Found!')->withInput();
        }
        $this->logActivity(json_encode(['activity' => 'Getting Level Informations', 'entered_name' => $request->name, 'status' => 'Founded']), request()->ip(), request()->userAgent());

        return view('Catalogs.Levels.index', compact('levels', 'name'));
    }
}
