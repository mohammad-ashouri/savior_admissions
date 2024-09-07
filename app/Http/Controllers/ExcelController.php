<?php

namespace App\Http\Controllers;

use App\ExcelExports\AllGuardiansWithStudents;
use App\ExcelExports\AllStudentsWithGuardians;
use App\ExcelExports\StudentStatuses;
use App\ExcelExports\UsersWithMobile;
use App\Imports\Documents;
use App\Imports\DocumentTypesImport;
use App\Imports\NewUsers;
use App\Imports\ParentsFatherImport;
use App\Imports\ParentsMotherImport;
use App\Imports\StudentsImport;
use App\Imports\StudentsImport2;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Temporary.excelimporter');
    }

    public function importUsers(Request $request): \Illuminate\Http\RedirectResponse
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new StudentsImport, $file);
        Excel::import(new StudentsImport2, $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }

    public function importDocumentTypes(Request $request): \Illuminate\Http\RedirectResponse
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new DocumentTypesImport, $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }

    public function importDocuments(Request $request): \Illuminate\Http\RedirectResponse
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new Documents, $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }

    public function importParentFathers(Request $request): \Illuminate\Http\RedirectResponse
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new ParentsFatherImport, $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }

    public function importParentMothers(Request $request): \Illuminate\Http\RedirectResponse
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new ParentsMotherImport, $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }

    public function importNewUsers(Request $request)
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new NewUsers, $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }

    public function exportExcelFromUsersMobile()
    {
        return Excel::download(new UsersWithMobile, 'users.xlsx');
    }

    public function exportExcelFromAllStudents()
    {
        return Excel::download(new AllStudentsWithGuardians, 'students.xlsx');
    }

    public function exportExcelFromAllGuardianWithStudents()
    {
        return Excel::download(new AllGuardiansWithStudents, 'student_statuses.xlsx');
    }

    public function exportStudentStatuses(Request $request)
    {
        $request->validate([
            'academicYear' => 'required|integer|exists:academic_years,id',
        ]);

        $me = User::find(auth()->user()->id);
        $academicYear = $request->academicYear;
        $students = [];
        if ($me->hasRole('Super Admin')) {
            $students = StudentApplianceStatus::with(['studentInfo', 'academicYearInfo', 'documentSeconder'])
                ->whereAcademicYear($academicYear)
                ->get();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Admissions Officer')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId($me->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $students = StudentApplianceStatus::with(['studentInfo', 'academicYearInfo', 'documentSeconder'])
                ->whereIn('academic_year', $academicYears)
                ->whereAcademicYear($academicYear)
                ->get();
        }

        return Excel::download(new StudentStatuses($students), 'students.xlsx');
    }
}
