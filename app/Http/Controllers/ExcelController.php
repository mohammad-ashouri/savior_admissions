<?php

namespace App\Http\Controllers;

use App\ExcelExports\AllStudentsWithGuardians;
use App\ExcelExports\UsersWithMobile;
use App\Imports\Documents;
use App\Imports\DocumentTypesImport;
use App\Imports\ParentsFatherImport;
use App\Imports\ParentsMotherImport;
use App\Imports\StudentsImport;
use App\Imports\StudentsImport2;
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

        Excel::import(new ParentsFatherImport(), $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }

    public function importParentMothers(Request $request): \Illuminate\Http\RedirectResponse
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new ParentsMotherImport(), $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }

    public function exportExcelFromUsersMobile()
    {
        return Excel::download(new UsersWithMobile(), 'users.xlsx');
    }

    public function exportExcelFromAllStudents()
    {
        return Excel::download(new AllStudentsWithGuardians(), 'students.xlsx');
    }
}
