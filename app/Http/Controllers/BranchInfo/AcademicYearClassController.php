<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\AcademicYearClass;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\EducationType;
use App\Models\Catalogs\Level;
use App\Models\Gender;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademicYearClassController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:academic-year-class-list', ['only' => ['index']]);
        $this->middleware('permission:academic-year-class-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:academic-year-class-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:academic-year-class-delete', ['only' => ['destroy']]);
        $this->middleware('permission:academic-year-class-search', ['only' => ['search']]);
    }

    public function index()
    {
        $academicYearClasses = AcademicYearClass::with('academicYearInfo')->with('levelInfo')->with('educationTypeInfo')->with('educationGenderInfo')->orderBy('id', 'desc')->paginate(10);
        return view('BranchInfo.AcademicYearClasses.index', compact('academicYearClasses'));
    }

    public function create()
    {
        $me=User::find(session('id'));
        if ($me->hasRole('Super Admin')) {
            $academicYears = AcademicYear::where('status', 1)->get();
            $levels=Level::where('status',1)->get();
            $educationTypes=EducationType::where('status',1)->get();
            $educationGenders=Gender::get();
        }elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')){
            $academicYears = AcademicYear::where('status', 1)->get();
            $levels=Level::where('status',1)->get();
            $educationTypes=EducationType::where('status',1)->get();
            $educationGenders=Gender::get();
        }
        return view('BranchInfo.AcademicYearClasses.create', compact('academicYears','levels','educationTypes','educationGenders'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'academic_year' => 'required|exists:academic_years,id',
            'level' => 'required|exists:levels,id',
            'education_type' => 'required|exists:education_types,id',
            'capacity' => 'required|integer|max:60|min:1',
            'education_gender' => 'required|exists:genders,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $class=new AcademicYearClass();
        $class->name=$request->name;
        $class->academic_year=$request->academic_year;
        $class->level=$request->level;
        $class->education_type=$request->education_type;
        $class->capacity=$request->capacity;
        $class->education_gender=$request->education_gender;
        $class->save();

        return redirect()->route('AcademicYearClasses.index')
            ->with('success', 'Academic year class added successfully');
    }

}
