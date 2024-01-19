<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\School;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AcademicYearController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:academic-year-list', ['only' => ['index']]);
        $this->middleware('permission:academic-year-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:academic-year-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:academic-year-delete', ['only' => ['destroy']]);
        $this->middleware('permission:academic-year-search', ['only' => ['search']]);
    }

    public function index()
    {
        $academicYears = AcademicYear::with('schoolInfo')->orderBy('start_date', 'asc')->paginate(10);
        return view('Catalogs.AcademicYears.index', compact('academicYears'));
    }

    public function create()
    {
        $academicYears = AcademicYear::get();
        $schools = School::where('status', 1)->orderBy('name', 'asc')->get();
        return view('Catalogs.AcademicYears.create', compact('academicYears', 'schools'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:academic_years,name',
            'school' => 'required|exists:schools,id',
            'start_date' => 'required|date',
            'finish_date' => 'required|date',
            'max_discount_percentage' => 'required|integer|max:100',
            'max_installments' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $firstDate = Carbon::parse($request->start_date);
        $secondDate = Carbon::parse($request->finish_date);

        if ($firstDate->isAfter($secondDate)) {
            return redirect()->back()->withErrors('The first date is after the second date')->withInput();
        } elseif ($request->start_date == $request->finish_date) {
            return redirect()->back()->withErrors('The first date and the second date are equal')->withInput();
        } else {
            $catalog = AcademicYear::create([
                'name' => $request->name,
                'school_id' => $request->school,
                'start_date' => $request->start_date,
                'finish_date' => $request->finish_date,
                'max_discount_percentage' => $request->max_discount_percentage,
                'max_installments' => $request->max_installments,
            ]);
            return redirect()->route('AcademicYears.index')
                ->with('success', 'Academic year created successfully');
        }

    }

    public function edit($id)
    {
        $catalog = AcademicYear::with('schoolInfo')->find($id);
        $schools = School::where('status', 1)->orderBy('name', 'asc')->get();
        return view('Catalogs.AcademicYears.edit', compact('catalog', 'schools'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'school' => 'required|exists:schools,id',
            'start_date' => 'required|date',
            'finish_date' => 'required|date',
            'max_discount_percentage' => 'required|integer|max:100',
            'max_installments' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $firstDate = Carbon::parse($request->start_date);
        $secondDate = Carbon::parse($request->finish_date);

        if ($firstDate->isAfter($secondDate)) {
            return redirect()->back()->withErrors('The first date is after the second date')->withInput();
        } elseif ($request->start_date == $request->finish_date) {
            return redirect()->back()->withErrors('The first date and the second date are equal')->withInput();
        } else {
            $catalog = AcademicYear::find($id);
            $catalog->name = $request->input('name');
            $catalog->status = $request->input('status');
            $catalog->save();
            return redirect()->route('AcademicYears.index')
                ->with('success', 'Academic year edited successfully');
        }
    }

    public function search()
    {
        dd('go');
    }
}
