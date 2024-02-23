<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Branch\AcademicYearClass;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\Level;
use App\Models\Catalogs\School;
use App\Models\Finance\Tuition;
use App\Models\User;
use App\Models\UserAccessInformation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $academicYears = AcademicYear::with('schoolInfo')->orderBy('id', 'desc')->paginate(10);
        return view('Catalogs.AcademicYears.index', compact('academicYears'));
    }

    public function create()
    {
        $academicYears = AcademicYear::get();
        $schools = School::where('status', 1)->orderBy('name', 'asc')->get();
        $levels = Level::where('status', 1)->orderBy('id', 'asc')->get();
        $users = User::where('status', 1)->with('generalInformationInfo')->orderBy('id')->get();
        return view('Catalogs.AcademicYears.create', compact('academicYears', 'schools', 'levels', 'users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:academic_years,name',
            'school' => 'required|exists:schools,id',
            'start_date' => 'required|date',
            'end_date' => 'required|after_or_equal:start_date',
            'Principal' => 'required',
            'Admissions_Officer' => 'required',
            'Financial_Manager' => 'required',
            'Interviewer' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $prevAcademicYear = AcademicYear::where('school_id', $request->input('school'))->where('status', 1)->first();
        if ($prevAcademicYear) {
            $prevAcademicYear->status = 0;
            $prevAcademicYear->save();
        }
        $principals = $request->input('Principal');
        $admissionsOfficers = $request->input('Admissions_Officer');
        $financialManagers = $request->input('Financial_Manager');
        $interviewers = $request->input('Interviewer');
        $school = (int)$request->school;

        foreach ($principals as $principal) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $principal
                ]
            );
            if (!$user->principal) {
                $user->principal = $school;
            } else {
                $schools = explode('|', $user->principal);
                if (!in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->principal = implode('|', $schools);
            }
            $user->save();
        }
        foreach ($admissionsOfficers as $admissionsOfficer) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $admissionsOfficer
                ]
            );
            if (!$user->admissions_officer) {
                $user->admissions_officer = $school;
            } else {
                $schools = explode('|', $user->admissions_officer);
                if (!in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->admissions_officer = implode('|', $schools);
            }
            $user->save();
        }
        foreach ($financialManagers as $financialManager) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $financialManager
                ]
            );
            if (!$user->financial_manager) {
                $user->financial_manager = $school;
            } else {
                $schools = explode('|', $user->financial_manager);
                if (!in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->financial_manager = implode('|', $schools);
            }
            $user->save();
        }
        foreach ($interviewers as $interviewer) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $interviewer
                ]
            );
            if (!$user->interviewer) {
                $user->interviewer = $school;
            } else {
                $schools = explode('|', $user->interviewer);
                if (!in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->interviewer = implode('|', $schools);
            }
            $user->save();
        }
        $employeesData = [
            'Principal' => [$principals],
            'Admissions_Officer' => [$admissionsOfficers],
            'Financial_Manager' => [$financialManagers],
            'Interviewer' => [$interviewers],
        ];

        $academicYear=AcademicYear::create([
            'name' => $request->name,
            'school_id' => $request->school,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'levels' => json_encode($request->levels, true),
            'employees' => json_encode($employeesData, true),
        ]);

        $tuition=Tuition::create([
            'academic_year' => $academicYear->id
        ]);

        $tuitionDetail=Tuition::create([
            'academic_year' => $academicYear->id
        ]);
        return redirect()->route('AcademicYears.index')
            ->with('success', 'Academic year created successfully');
    }

    public function edit($id)
    {
        $catalog = AcademicYear::with('schoolInfo')->find($id);
        $levels = Level::where('status', 1)->orderBy('id', 'asc')->get();
        $schools = School::where('status', 1)->orderBy('name', 'asc')->get();
        $users = User::where('status', 1)->with('generalInformationInfo')->orderBy('id')->get();
        return view('Catalogs.AcademicYears.edit', compact('catalog', 'schools', 'levels', 'users'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'school' => 'required|exists:schools,id',
            'start_date' => 'required|date',
            'end_date' => 'required|after_or_equal:start_date',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $principals = $request->input('Principal');
        $admissionsOfficers = $request->input('Admissions_Officer');
        $financialManagers = $request->input('Financial_Manager');
        $interviewers = $request->input('Interviewer');
        $school = $request->school;

        $results = UserAccessInformation::pluck('principal');
        foreach ($results as $result) {
            $values = explode('|', $result);

            $key = array_search($school, $values);

            if ($key !== false) {
                unset($values[$key]);
            }
            UserAccessInformation::where('principal', $result)->update(['principal' => implode('|', $values)]);
        }
        $results = UserAccessInformation::pluck('admissions_officer');
        foreach ($results as $result) {
            $values = explode('|', $result);

            $key = array_search($school, $values);

            if ($key !== false) {
                unset($values[$key]);
            }
            UserAccessInformation::where('admissions_officer', $result)->update(['admissions_officer' => implode('|', $values)]);
        }
        $results = UserAccessInformation::pluck('financial_manager');
        foreach ($results as $result) {
            $values = explode('|', $result);

            $key = array_search($school, $values);

            if ($key !== false) {
                unset($values[$key]);
            }
            UserAccessInformation::where('financial_manager', $result)->update(['financial_manager' => implode('|', $values)]);
        }
        $results = UserAccessInformation::pluck('interviewer');
        foreach ($results as $result) {
            $values = explode('|', $result);

            $key = array_search($school, $values);

            if ($key !== false) {
                unset($values[$key]);
            }
            UserAccessInformation::where('interviewer', $result)->update(['interviewer' => implode('|', $values)]);
        }

        foreach ($principals as $principal) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $principal
                ]
            );
            if (!$user->principal) {
                $user->principal = $school;
            } else {
                $schools = explode('|', $user->principal);
                if (!in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->principal = implode('|', $schools);
            }
            $user->save();
        }
        foreach ($admissionsOfficers as $admissionsOfficer) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $admissionsOfficer
                ]
            );
            if (!$user->admissions_officer) {
                $user->admissions_officer = $school;
            } else {
                $schools = explode('|', $user->admissions_officer);
                if (!in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->admissions_officer = implode('|', $schools);
            }
            $user->save();
        }
        foreach ($financialManagers as $financialManager) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $financialManager
                ]
            );
            if (!$user->financial_manager) {
                $user->financial_manager = $school;
            } else {
                $schools = explode('|', $user->financial_manager);
                if (!in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->financial_manager = implode('|', $schools);
            }
            $user->save();
        }
        foreach ($interviewers as $interviewer) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $interviewer
                ]
            );
            if (!$user->interviewer) {
                $user->interviewer = $school;
            } else {
                $schools = explode('|', $user->interviewer);
                if (!in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->interviewer = implode('|', $schools);
            }
            $user->save();
        }

        $employeesData = [
            'Principal' => [$principals],
            'Admissions_Officer' => [$admissionsOfficers],
            'Financial_Manager' => [$financialManagers],
            'Interviewer' => [$interviewers],
        ];
        $catalog = AcademicYear::find($id);
        $catalog->name = $request->input('name');
        $catalog->status = $request->input('status');

        $academicYearClasses=AcademicYearClass::where('academic_year',$catalog->id)->get();
        foreach ($academicYearClasses as $academicYearClass){
            $academicYearClass=AcademicYearClass::find($academicYearClass->id);
            $academicYearClass->status=$catalog->status;
            $academicYearClass->save();
        }

        $applicationTimings=ApplicationTiming::where('academic_year',$catalog->id)->get();
        foreach ($applicationTimings as $applicationTiming){
            $applicationTiming=ApplicationTiming::find($applicationTiming->id);
            $applicationTiming->status=$catalog->status;
            $applicationTiming->save();

            $applications=Applications::where('application_timing_id',$applicationTiming->id)->where('reserved',0)->where('status',1)->get();
            foreach ($applications as $application){
                $application=Applications::find($application->id)->first();
                $application->status=0;
                $application->save();
            }
        }

        $catalog->start_date = $request->input('start_date');
        $catalog->end_date = $request->input('end_date');
        $catalog->levels = json_encode($request->input('levels'), true);
        $catalog->employees = json_encode($employeesData, true);;
        $catalog->save();
        return redirect()->route('AcademicYears.index')
            ->with('success', 'Academic year edited successfully');
    }

    public function search()
    {
        dd('go');
    }
}
