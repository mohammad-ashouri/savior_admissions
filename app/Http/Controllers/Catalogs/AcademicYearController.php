<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Branch\AcademicYearClass;
use App\Models\Branch\Applications;
use App\Models\Branch\ApplicationTiming;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\Level;
use App\Models\Catalogs\School;
use App\Models\Finance\Discount;
use App\Models\Finance\Tuition;
use App\Models\Finance\TuitionDetail;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademicYearController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:academic-year-list', ['only' => ['index']]);
        $this->middleware('permission:academic-year-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:academic-year-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:academic-year-delete', ['only' => ['destroy']]);
        $this->middleware('permission:academic-year-search', ['only' => ['search']]);
    }

    public function getAllPrincipals()
    {
        return User::whereStatus(1)->with('generalInformationInfo')->WhereHas('roles', function ($query) {
            $query->whereName('Principal');
        })->orderBy('id')->get();
    }
    public function getAllAdmissionsOfficers()
    {
        return User::whereStatus(1)->with('generalInformationInfo')->WhereHas('roles', function ($query) {
            $query->whereName('Admissions Officer');
        })->orderBy('id')->get();
    }
    public function getAllFinancialManagers()
    {
        return User::whereStatus(1)->with('generalInformationInfo')->WhereHas('roles', function ($query) {
            $query->whereName('Financial Manager');
        })->orderBy('id')->get();
    }
    public function getAllInterviewers()
    {
        return User::whereStatus(1)->with('generalInformationInfo')->WhereHas('roles', function ($query) {
            $query->whereName('Interviewer');
        })->orderBy('id')->get();
    }
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $academicYears = AcademicYear::with('schoolInfo')->orderBy('id', 'desc')->paginate(10);
        return view('Catalogs.AcademicYears.index', compact('academicYears'));
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $academicYears = AcademicYear::get();
        $schools = School::whereStatus(1)->orderBy('name', 'asc')->get();
        $levels = Level::whereStatus(1)->orderBy('id', 'asc')->get();

        $principals=$this->getAllPrincipals();
        $admissionOfficers=$this->getAllAdmissionsOfficers();
        $financialManagers=$this->getAllFinancialManagers();
        $interviewers=$this->getAllInterviewers();
        return view('Catalogs.AcademicYears.create', compact('academicYears', 'schools', 'levels', 'admissionOfficers','principals','financialManagers','interviewers'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:academic_years,name',
            'persian_name' => 'required|unique:academic_years,persian_name',
            'school' => 'required|exists:schools,id',
            'start_date' => 'required|date',
            'end_date' => 'required|after_or_equal:start_date',
            'Principal' => 'required',
            'Admissions_Officer' => 'required',
            'Financial_Manager' => 'required',
            'Interviewer' => 'required',
            'financial_file' => 'required|file|mimes:pdf',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $prevAcademicYear = AcademicYear::where('school_id', $request->input('school'))->whereStatus(1)->first();
        if ($prevAcademicYear) {
            $prevAcademicYear->status = 0;
            $prevAcademicYear->save();
        }
        $principals = $request->input('Principal');
        $admissionsOfficers = $request->input('Admissions_Officer');
        $financialManagers = $request->input('Financial_Manager');
        $interviewers = $request->input('Interviewer');
        $school = (int) $request->school;

        foreach ($principals as $principal) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $principal,
                ]
            );
            if (! $user->principal) {
                $user->principal = $school;
            } else {
                $schools = explode('|', $user->principal);
                if (! in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->principal = implode('|', $schools);
            }
            $user->save();
        }
        foreach ($admissionsOfficers as $admissionsOfficer) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $admissionsOfficer,
                ]
            );
            if (! $user->admissions_officer) {
                $user->admissions_officer = $school;
            } else {
                $schools = explode('|', $user->admissions_officer);
                if (! in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->admissions_officer = implode('|', $schools);
            }
            $user->save();
        }
        foreach ($financialManagers as $financialManager) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $financialManager,
                ]
            );
            if (! $user->financial_manager) {
                $user->financial_manager = $school;
            } else {
                $schools = explode('|', $user->financial_manager);
                if (! in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->financial_manager = implode('|', $schools);
            }
            $user->save();
        }
        foreach ($interviewers as $interviewer) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $interviewer,
                ]
            );
            if (! $user->interviewer) {
                $user->interviewer = $school;
            } else {
                $schools = explode('|', $user->interviewer);
                if (! in_array($school, $schools)) {
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

        $academicYear = AcademicYear::create([
            'name' => $request->name,
            'persian_name' => $request->persian_name,
            'school_id' => $request->school,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'levels' => json_encode($request->levels, true),
            'employees' => json_encode($employeesData, true),
        ]);

        $financialFileName = 'financial_file_'.now()->format('Y-m-d_H-i-s');
        $academicYear = $academicYear->find($academicYear->id);
        $financialFileName = $request->file('financial_file')->storeAs('public/uploads/Documents/AcademicYears/'.$academicYear->id.'/Financial_File', "$financialFileName.pdf");
        $academicYear->financial_roles = $financialFileName;
        $academicYear->save();

        $discount = Discount::create([
            'academic_year' => $academicYear->id,
        ]);

        $tuition = Tuition::create([
            'academic_year' => $academicYear->id,
        ]);

        foreach ($request->levels as $level) {
            TuitionDetail::create([
                'tuition_id' => $tuition->id,
                'level' => $level,
            ]);
        }

        return redirect()->route('AcademicYears.index')
            ->with('success', 'Academic year created successfully');
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $catalog = AcademicYear::with('schoolInfo')->find($id);
        $levels = Level::whereStatus(1)->orderBy('id', 'asc')->get();
        $schools = School::whereStatus(1)->orderBy('name', 'asc')->get();
        $principals=$this->getAllPrincipals();
        $admissionOfficers=$this->getAllAdmissionsOfficers();
        $financialManagers=$this->getAllFinancialManagers();
        $interviewers=$this->getAllInterviewers();
        return view('Catalogs.AcademicYears.edit', compact('catalog', 'schools', 'levels', 'admissionOfficers','principals','financialManagers','interviewers'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'persian_name' => 'required',
            'school' => 'required|exists:schools,id',
            'start_date' => 'required|date',
            'end_date' => 'required|after_or_equal:start_date',
            'financial_file' => 'required|file|mimes:pdf',
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
                    'user_id' => $principal,
                ]
            );
            if (! $user->principal) {
                $user->principal = $school;
            } else {
                $schools = explode('|', $user->principal);
                if (! in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->principal = implode('|', $schools);
            }
            $user->save();
        }
        foreach ($admissionsOfficers as $admissionsOfficer) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $admissionsOfficer,
                ]
            );
            if (! $user->admissions_officer) {
                $user->admissions_officer = $school;
            } else {
                $schools = explode('|', $user->admissions_officer);
                if (! in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->admissions_officer = implode('|', $schools);
            }
            $user->save();
        }
        foreach ($financialManagers as $financialManager) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $financialManager,
                ]
            );
            if (! $user->financial_manager) {
                $user->financial_manager = $school;
            } else {
                $schools = explode('|', $user->financial_manager);
                if (! in_array($school, $schools)) {
                    $schools[] = $school;
                }
                $user->financial_manager = implode('|', $schools);
            }
            $user->save();
        }
        foreach ($interviewers as $interviewer) {
            $user = UserAccessInformation::firstOrCreate(
                [
                    'user_id' => $interviewer,
                ]
            );
            if (! $user->interviewer) {
                $user->interviewer = $school;
            } else {
                $schools = explode('|', $user->interviewer);
                if (! in_array($school, $schools)) {
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
        $academicYear = AcademicYear::find($id);
        $academicYear->name = $request->input('name');
        $academicYear->persian_name = $request->input('persian_name');
        $academicYear->status = $request->input('status');

        $academicYearClasses = AcademicYearClass::whereAcademicYear($academicYear->id)->get();
        foreach ($academicYearClasses as $academicYearClass) {
            $academicYearClass = AcademicYearClass::find($academicYearClass->id);
            $academicYearClass->status = $academicYear->status;
            $academicYearClass->save();
        }

        $applicationTimings = ApplicationTiming::whereAcademicYear($academicYear->id)->get();
        foreach ($applicationTimings as $applicationTiming) {
            $applicationTiming = ApplicationTiming::find($applicationTiming->id);
            $applicationTiming->status = $academicYear->status;
            $applicationTiming->save();

            $applications = Applications::whereApplicationTimingId($applicationTiming->id)->whereReserved(0)->whereStatus(1)->get();
            foreach ($applications as $application) {
                $application = Applications::find($application->id)->first();
                $application->status = 0;
                $application->save();
            }
        }

        $academicYear->start_date = $request->input('start_date');
        $academicYear->end_date = $request->input('end_date');
        $academicYear->levels = json_encode($request->input('levels'), true);
        $academicYear->employees = json_encode($employeesData, true);
        $academicYear->save();

        $financialFileName = 'financial_file_'.now()->format('Y-m-d_H-i-s');
        $academicYear = $academicYear->find($academicYear->id);
        $financialFileName = $request->file('financial_file')->storeAs('public/uploads/Documents/AcademicYears/'.$academicYear->id.'/Financial_File', "$financialFileName.pdf");
        $academicYear->financial_roles = $financialFileName;
        $academicYear->save();

        Discount::firstOrCreate([
            'academic_year' => $academicYear->id,
        ]);

        $tuition = Tuition::firstOrCreate([
            'academic_year' => $academicYear->id,
        ]);

        //For deactivate all tuitions
        TuitionDetail::whereTuitionId($tuition->id)->update(['status' => 0]);
        foreach ($request->levels as $level) {
            TuitionDetail::firstOrCreate([
                'tuition_id' => $tuition->id,
                'level' => $level,
            ]);
            TuitionDetail::whereTuitionId($tuition->id)->where('level', $level)->update(['status' => 1]);
        }

        return redirect()->route('AcademicYears.index')
            ->with('success', 'Academic year edited successfully');
    }

    public function show(Request $request): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $name = $request->name;
        $academicYears = AcademicYear::with('schoolInfo')->whereName('LIKE', "%$name%")->paginate(10);
        $academicYears->appends(request()->query())->links();
        if ($academicYears->isEmpty()) {
            return redirect()->route('AcademicYears.index')->withErrors('Not Found!')->withInput();
        }
        return view('Catalogs.AcademicYears.index', compact('academicYears', 'name'));
    }
}
