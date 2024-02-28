<?php

namespace App\Http\Controllers;

use App\Imports\Documents;
use App\Imports\DocumentTypesImport;
use App\Imports\ParentsGeneralInformationsImport;
use App\Imports\ParentsFatherImport;
use App\Imports\ParentsMotherImport;
use App\Imports\StudentsImport;
use App\Imports\StudentsImport2;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function index()
    {
        return view('Temporary.excelimporter');
    }
    public function importUsers(Request $request)
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new StudentsImport, $file);
        Excel::import(new StudentsImport2, $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }
    public function importDocumentTypes(Request $request)
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new DocumentTypesImport, $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }
    public function importDocuments(Request $request)
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new Documents, $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }
    public function importParentFathers(Request $request)
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new ParentsFatherImport(), $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }
    public function importParentMothers(Request $request)
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new ParentsMotherImport(), $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }
}
