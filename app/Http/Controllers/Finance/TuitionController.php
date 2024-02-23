<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Tuition;
use Illuminate\Http\Request;

class TuitionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:tuition-list', ['only' => ['index']]);
        $this->middleware('permission:tuition-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:tuition-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tuition-show', ['only' => ['show']]);
        $this->middleware('permission:tuition-change-status', ['only' => ['changeTuitionStatus']]);
    }

    public function index()
    {
        $tuitions = Tuition::with('academicYearInfo')->with('levelInfo')->orderBy('name')->paginate(10);
        return view('Finance.Tuition.index', compact('tuitions'));
    }

    public function create()
    {
        $schools = School::get();
        $genders=Gender::get();
        return view('Catalogs.Schools.create', compact('schools','genders'));
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

    public function edit($id)
    {
        $catalog = School::find($id);
        $genders=Gender::get();
        return view('Catalogs.Schools.edit', compact('catalog','genders' ));
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
