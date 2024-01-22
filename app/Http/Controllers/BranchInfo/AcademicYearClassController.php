<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\AcademicYearClass;
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
}
