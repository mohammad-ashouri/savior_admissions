<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\DocumentType;
use App\Models\Catalogs\School;
use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $schools = School::with('genderInfo')->orderBy('name')->paginate(10);
        $this->logActivity(json_encode(['activity' => 'Getting Schools']), request()->ip(), request()->userAgent(), session('id'));

        return view('Catalogs.Schools.index', compact('schools'));
    }

    public function create()
    {
        $schools = School::get();
        $genders=Gender::get();
        return view('Catalogs.Schools.create', compact('schools','genders'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:schools,name',
            'gender' => 'required',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Saving School Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent(), session('id'));

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $school = School::create(['name' => $request->input('name'),'gender' => $request->input('gender')]);
        $this->logActivity(json_encode(['activity' => 'School Saved', 'id' => $school->id]), request()->ip(), request()->userAgent(), session('id'));

        return redirect()->route('Schools.index')
            ->with('success', 'School created successfully');
    }

    public function edit($id)
    {
        $catalog = School::find($id);
        $genders=Gender::get();
        $this->logActivity(json_encode(['activity' => 'Getting School Information For Edit', 'id' => $catalog->id]), request()->ip(), request()->userAgent(), session('id'));

        return view('Catalogs.Schools.edit', compact('catalog','genders' ));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Saving School Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent(), session('id'));

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $catalog = School::find($id);
        $catalog->name = $request->input('name');
        $catalog->gender = $request->input('gender');
        $catalog->status = $request->input('status');
        $catalog->save();
        $this->logActivity(json_encode(['activity' => 'School Updated', 'id' => $catalog->id]), request()->ip(), request()->userAgent(), session('id'));

        return redirect()->route('Schools.index')
            ->with('success', 'School updated successfully');
    }
    public function show(Request $request)
    {
        $name=$request->name;
        $schools=School::where('name','LIKE', "%$name%")->paginate(10);
        $schools->appends(request()->query())->links();
        if ($schools->isEmpty()){
            $this->logActivity(json_encode(['activity' => 'Getting School Informations', 'entered_name' => $request->name, 'status' => 'Not Found']), request()->ip(), request()->userAgent(), session('id'));

            return redirect()->route('Schools.index')->withErrors('Not Found!')->withInput();
        }
        $this->logActivity(json_encode(['activity' => 'Getting School Informations', 'entered_name' => $request->name, 'status' => 'Founded']), request()->ip(), request()->userAgent(), session('id'));

        return view('Catalogs.Schools.index', compact('schools','name'));
    }
}
