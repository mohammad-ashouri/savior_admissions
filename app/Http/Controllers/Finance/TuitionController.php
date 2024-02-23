<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\AcademicYear;
use App\Models\Finance\Tuition;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;

class TuitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:tuition-list', ['only' => ['index']]);
        $this->middleware('permission:tuition-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tuition-show', ['only' => ['show']]);
        $this->middleware('permission:tuition-change-status', ['only' => ['changeTuitionStatus']]);
    }

    public function index()
    {
        $me = User::find(session('id'));
        $tuitions = [];
        if ($me->hasRole('Super Admin')) {
            $tuitions = Tuition::with('academicYearInfo')->orderBy('academic_year', 'desc')->paginate(10);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $principalAccess = explode('|', $myAllAccesses->principal);
            $financialManagerAccess = explode('|', $myAllAccesses->financial_manager);
            $filteredArray = array_filter(array_unique(array_merge($principalAccess, $financialManagerAccess)));

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $tuitions = Tuition::with('academicYearInfo')->whereIn('academic_year', $academicYears)->orderBy('academic_year', 'desc')->paginate(10);
        }

        if ($tuitions->isEmpty()) {
            $tuitions = [];
        }

        return view('Finance.Tuition.index', compact('tuitions'));
    }

    public function edit($id)
    {
        $me = User::find(session('id'));
        $tuitions = [];
        if ($me->hasRole('Super Admin')) {
            $tuitions = Tuition::with('academicYearInfo')->with('allTuitions')->orderBy('academic_year', 'desc')->find($id);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $principalAccess = explode('|', $myAllAccesses->principal);
            $financialManagerAccess = explode('|', $myAllAccesses->financial_manager);
            $filteredArray = array_filter(array_unique(array_merge($principalAccess, $financialManagerAccess)));

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $tuitions = Tuition::with('academicYearInfo')->with('allTuitions')->whereIn('academic_year', $academicYears)->orderBy('academic_year', 'desc')->find($id);
        }

        if (empty($tuitions)) {
            $tuitions = [];
        }

        return view('Finance.Tuition.edit', compact('tuitions'));

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
