<?php

namespace App\Http\Controllers;

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
    public function import(Request $request)
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

//        Excel::import(new StudentsImport, $file);
        Excel::import(new StudentsImport2, $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }
}
