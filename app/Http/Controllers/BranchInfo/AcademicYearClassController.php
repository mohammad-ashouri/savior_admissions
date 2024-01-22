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
        }
        return view('BranchInfo.AcademicYearClasses.create', compact('academicYears','levels','educationTypes','educationGenders'));
    }


}
