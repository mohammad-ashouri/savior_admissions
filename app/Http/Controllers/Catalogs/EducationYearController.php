<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\EducationYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EducationYearController extends Controller
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
        $years = EducationYear::with('starterInfo')->with('finisherInfo')->orderBy('start', 'asc')->paginate(10);
        return view('Catalogs.EducationYears.index', compact('years'));
    }

    public function create()
    {
        return view('Catalogs.EducationYears.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => [
                'required',
                'date',
                'unique:education_years,start'
            ],
        ]);
        if ($validator->fails()) {
            $this->logActivity('Failed Start New Education Year=> ' . $validator->errors()->first(), request()->ip(), request()->userAgent(), session('id'));
            return redirect()->route('EducationYears.create')
                ->with('error', $validator->errors()->first());
        }
        EducationYear::query()->update(['active' => 0]);
        $catalog = EducationYear::create(['start' => $request->input('start_date'), 'starter' => session('id')]);

        if ($catalog) {
            $this->logActivity('Start New Education Year=> ' . $catalog, request()->ip(), request()->userAgent(), session('id'));
            return redirect()->route('EducationYears.index')
                ->with('success', 'Education year created successfully');
        }
        return response()->json(['error' => 'Server error'], 500);

    }

    public function finish(Request $request)
    {
        $year = EducationYear::find($request->input('yearID'));
        if ($year) {
            $year->finish = now();
            $year->finisher = session('id');
            $year->active = 0;
            $year->save();
            $this->logActivity('Finish Education Year=> ' . $year->id, request()->ip(), request()->userAgent(), session('id'));
            return redirect()->route('EducationYears.index')
                ->with('success', 'Education year finished successfully');
        }
        $this->logActivity('Failed To Finish Education Year=> ' . $request->input('yearID'), request()->ip(), request()->userAgent(), session('id'));
        return response()->json(['error' => 'Server error'], 500);

    }

}
