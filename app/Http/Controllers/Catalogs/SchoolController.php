<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\School;
use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:school-list', ['only' => ['index']]);
        $this->middleware('permission:school-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:school-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:school-delete', ['only' => ['destroy']]);
        $this->middleware('permission:school-search', ['only' => ['search']]);
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $schools = School::with('genderInfo')->orderBy('name')->paginate(10);
        return view('Catalogs.Schools.index', compact('schools'));
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $schools = School::get();
        $genders = Gender::get();

        return view('Catalogs.Schools.create', compact('schools', 'genders'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:schools,name',
            'persian_name' => 'required|unique:schools,persian_name',
            'gender' => 'required',
            'educational_charter' => 'required',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $school = School::create(['name' => $request->input('name'),'persian_name' => $request->input('persian_name'), 'gender' => $request->input('gender'), 'educational_charter' => $request->input('educational_charter'), 'address' => $request->input('address'), 'address_fa' => $request->input('address_fa')]);
        return redirect()->route('Schools.index')
            ->with('success', 'School created successfully');
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $catalog = School::find($id);
        $genders = Gender::get();
        return view('Catalogs.Schools.edit', compact('catalog', 'genders'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'persian_name' => 'required',
            'gender' => 'required',
            'educational_charter' => 'required',
            'address' => 'required|string',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $catalog = School::find($id);
        $catalog->name = $request->input('name');
        $catalog->persian_name = $request->input('persian_name');
        $catalog->gender = $request->input('gender');
        $catalog->educational_charter = $request->input('educational_charter');
        $catalog->address = $request->input('address');
        $catalog->address_fa = $request->input('address_fa');
        $catalog->status = $request->input('status');
        $catalog->save();
        return redirect()->route('Schools.index')
            ->with('success', 'School updated successfully');
    }

    public function show(Request $request): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $name = $request->name;
        $schools = School::whereName('LIKE', "%$name%")->paginate(10);
        $schools->appends(request()->query())->links();
        if ($schools->isEmpty()) {
            return redirect()->route('Schools.index')->withErrors('Not Found!')->withInput();
        }
        return view('Catalogs.Schools.index', compact('schools', 'name'));
    }

    public function EducationalCharter(Request $request)
    {
        $academicYear = $request->input('academic_year');
        $checkAcademicYear = AcademicYear::where('id', $academicYear)->whereStatus(1)->first();
        if (!empty($checkAcademicYear)) {
            return School::where('id', $checkAcademicYear->school_id)->whereStatus(1)->value('educational_charter');
        } else {
            abort(403, 'Access Denied');
        }
    }
}
